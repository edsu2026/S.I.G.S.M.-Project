#!/bin/bash
### =========================================================================
### S.I.G.S.M. - Sistema Informático de Gestión de Servicios Médicos
### Script de Administración de Sistemas Operativos - SEGUNDA ENTREGA
### Consola de Operador - EDSU
### EDSU - Equipo de Desarrollo de Software del Uruguay - 2026
### Integrantes:
### - Mateo Ducasse
### - Valentin Correa
### - Marcos Salas
### - Ian Galli
###
### CHANGELOG respecto a la Primera Entrega:
###   [NUEVO] respaldo_sistema()        -> Backups automatizados y cifrados (Sección 13 - Ley 18.331)
###   [NUEVO] monitorear_recursos()     -> Métricas de salud del servidor (Sección 14)
###   [NUEVO] auditoria_accesos()       -> Visualización de logs de autenticación/SSH (Sección 14)
###   [NUEVO] hardening_ssh()           -> Endurecimiento de /etc/ssh/sshd_config (Sección 10, apartados A/B/D)
###   [NUEVO] configurar_firewall()     -> Política de UFW por defecto y apertura de puertos (Sección 11)
###   [NUEVO] permisos_directorio_app() -> chown/chmod sobre /var/www/html/sigsm (Sección 12)
###
### FUERA DE ALCANCE DE ESTA ENTREGA (no se incluyen por no haber sido dictados en clase
### al momento del cierre de esta entrega; quedan documentados como pendientes para la
### Tercera Entrega en el informe de Administración de Sistemas Operativos):
###   - Autenticación SSH por clave pública (Sección 10, apartado C - PubkeyAuthentication)
###   - Instalación y puesta a punto local de MySQL/MariaDB Server
###   - Archivo Docker Compose (Sección 18)
###   - Configuración avanzada de rotación de logs (logrotate)
### =========================================================================


### --- CONTROL DE PRIVILEGIOS DE ROOT ---
# La variable especial del sistema $EUID almacena el ID del usuario actual.
# En todos los sistemas Linux, el usuario Administrador (root) siempre tiene el ID 0.
# Si el ID no es igual a 0, detenemos el script de inmediato por seguridad.
if [ "$EUID" -ne 0 ]; then
    echo "[ - ] ERROR: Este script requiere privilegios de ADMINISTRADOR (ejecutar con sudo o sudo su)."
    exit 1
fi


### --- VARIABLES GLOBALES DE CONFIGURACIÓN ---
# Centralizamos aquí las rutas y valores que varias funciones reutilizan, para que si el
# día de mañana cambia una ruta (por ejemplo, dónde vive la app o dónde se guardan los
# respaldos) alcance con editar UNA sola línea y no haya que buscar por todo el script.
DIR_APP="/var/www/html/sigsm"                  # Directorio donde vive el código de la aplicación web
DIR_RESPALDOS="/respaldos"                     # Directorio dedicado y AISLADO para los backups cifrados
GRUPO_ADMINISTRATIVOS="SIGSM-administrativos"  # Grupo propietario de la app (personal médico/administrativo)
USUARIO_WEB="www-data"                         # Usuario con el que corre el servidor Apache
SSHD_CONFIG="/etc/ssh/sshd_config"             # Archivo de configuración del servicio SSH


### --- INICIALIZACIÓN SILENCIOSA DE GRUPOS ---
# Función para asegurar que los dos grupos del sistema existan antes de que el script opere.
# Al arrancar, creamos silenciosamente los grupos "SIGSM-administradores" y "SIGSM-administrativos".
inicializar_grupos_sistema() {
    # Lista de grupos definidos por el diseño de roles del sistema
    local grupos=("administradores" "administrativos")

    # Recorremos cada grupo mediante un bucle "for"
    for gp in "${grupos[@]}"; do
        # Agregamos el prefijo reglamentario "SIGSM-" para mantener un estricto orden en el servidor
        local grupo_completo="SIGSM-$gp"

        # "getent group" busca la existencia del grupo completo en el archivo '/etc/group' del sistema.
        # Redirigimos la salida normal y de error a "/dev/null" para que sea invisible al operador.
        if ! getent group "$grupo_completo" > /dev/null 2>&1; then
            # Si el grupo no existe en el sistema, lo creamos de forma segura utilizando groupadd
            groupadd "$grupo_completo"
        fi
    done
}

# Ejecutamos automáticamente la inicialización de grupos al arrancar el script
inicializar_grupos_sistema


### --- FUNCIONES DE GESTIÓN DE USUARIOS Y ASIGNACIÓN AUTOMÁTICA ---
# Función avanzada para crear un usuario, vincular su rol, registrar sus datos personales y asignarle un grupo de forma automática.
crear_usuario() {
    # CAPTURA EXPLICATIVA DE PARÁMETROS (BASH):
    # $1 = Nombre de usuario que usará para loguearse en el sistema (ej: mducasse)
    # $2 = Rol organizativo (admin / administrativo)
    # $3 = Primer nombre de pila de la persona (ej: Mateo)
    # $4 = Primer apellido de la persona (ej: Ducasse)
    local username=$1
    local rol=$2
    local nombre=$3
    local apellido=$4

    # 1. VALIDACIÓN DE CAMPOS VACÍOS:
    # El operador "-z" evalúa si las variables de caracteres están vacías.
    # Si falta alguno de los cuatro parámetros, mostramos el uso correcto en pantalla y frenamos la función.
    if [ -z "$username" ] || [ -z "$rol" ] || [ -z "$nombre" ] || [ -z "$apellido" ]; then
        echo "[ - ] ERROR: Sintaxis de creación incorrecta."
        echo "Uso correcto: crear_usuario <usuario> <rol> <Nombre> <Apellido>"
        echo "Ejemplo: crear_usuario lmateo administrativo Mateo Ducasse"
        return 1
    fi

    # 2. VALIDACIÓN DE USUARIOS DUPLICADOS:
    # "id" busca el login en las cuentas activas del sistema operativo.
    # Si ya existe, emitimos un aviso y terminamos para no generar inconsistencias.
    if id "$username" > /dev/null 2>&1; then
        echo "[ - ] ERROR: El usuario '$username' ya está registrado en el servidor."
        return 1
    fi

    # 3. DETERMINACIÓN AUTOMÁTICA DEL GRUPO SEGÚN EL ROL:
    # Usamos una estructura condicional "case" para encasillar al usuario en su grupo correspondiente.
    # Si el rol es admin, va al grupo administradores. Si es administrativo, va al grupo administrativos.
    local grupo_destino=""
    case "$rol" in
        "admin")
            grupo_destino="SIGSM-administradores"
            ;;
        "administrativo")
            grupo_destino="SIGSM-administrativos"
            ;;
        *)
            echo "[ - ] ERROR: El rol '$rol' no es válido en el diseño actual."
            echo "Roles del sistema: admin, administrativo."
            return 1
            ;;
    esac

    # 4. CREACIÓN SEGURA DE LA CUENTA EN LINUX:
    # -m: Genera automáticamente el directorio del usuario en /home/usuario.
    # -s /bin/bash: Configura la consola interactiva de comandos por defecto.
    # -g: Define el grupo PRIMARIO al que pertenece (vía herencia automática del rol).
    # -c: Registra los metadatos de identidad (nombre y apellido) en el archivo '/etc/passwd'.
    #
    # Explicación de seguridad: Reemplazamos los dos puntos (:) por un guion medio (-) para evitar
    # que useradd intente crear un campo fantasma en /etc/passwd, lo cual rompería la base de datos de usuarios.
    useradd -m -s /bin/bash -g "$grupo_destino" -c "SIGSM-$rol - $nombre $apellido" "$username"

    # Evaluamos si el comando "useradd" se ejecutó con éxito (código de salida $? igual a 0)
    if [ $? -eq 0 ]; then
        echo "[ + ] ÉXITO: Usuario '$username' creado y asignado al grupo '$grupo_destino'."

        # 5. ASIGNACIÓN Y CADUCIDAD DE CONTRASEÑA POR SEGURIDAD:
        # "chpasswd" permite asignar contraseñas de forma directa en bash usando la sintaxis "usuario:contraseña".
        # Por diseño de seguridad, la contraseña temporal inicial será idéntica al propio nombre de usuario.
        echo "$username:$username" | chpasswd

        # "passwd -e" fuerza la expiración inmediata de la clave recién generada.
        # Esto es obligatorio para que al conectarse por SSH desde Windows, el sistema les obligue a cambiar la clave.
        passwd -e "$username" > /dev/null 2>&1
        echo "[ + ] SEGURIDAD: Contraseña temporal configurada como '$username' (Expirada para exigir cambio en primer login)."
    else
        echo "[ - ] ERROR: Ocurrió un problema crítico al registrar el usuario en el sistema operativo."
        return 1
    fi
}

# Función para eliminar cuentas de usuario de manera LIMPIA.
eliminar_usuario() {
    local username=$1

    # Validamos que se haya provisto el usuario de login
    if [ -z "$username" ]; then
        echo "[ - ] ERROR: Debe indicar el nombre de usuario. Uso: eliminar_usuario <usuario>"
        return 1
    fi

    # Comprobamos si la cuenta existe en el sistema antes de iniciar el borrado
    if ! id "$username" > /dev/null 2>&1; then
        echo "[ - ] ERROR: El usuario '$username' no existe en este servidor."
        return 1
    fi

    # "userdel -r" destruye al usuario y elimina su carpeta personal (/home/usuario) de forma permanente.
    userdel -r "$username"
    if [ $? -eq 0 ]; then
        echo "[ + ] ÉXITO: El usuario '$username' y su directorio en /home han sido removidos."
    else
        echo "[ - ] ERROR: No se pudo proceder con la eliminación del usuario."
        return 1
    fi
}

# Función que lista únicamente los usuarios pertenecientes al ecosistema de S.I.G.S.M.
listar_usuarios_sigsm() {
    echo ""
    echo "========================================================================="
    echo "                 LISTADO DE USUARIOS ACTIVOS DEL S.I.G.S.M."
    echo "========================================================================="

    # Explicación técnica de la tubería de listado:
    # 1. "getent passwd" muestra en pantalla todas las cuentas del servidor.
    # 2. "grep 'SIGSM-'" aísla únicamente los usuarios filtrando por el prefijo.
    # 3. "awk -F:" usa los dos puntos como delimitador para obtener:
    #    - $1: El ID de usuario (login).
    #    - $5: El campo de metadatos (donde ahora se guarda "SIGSM-rol - Nombre Apellido").
    # 4. "sed" limpia el prefijo para mostrar la consola de forma mas cheta.
    getent passwd | grep "SIGSM-" | awk -F: '{print "ID/Login: " $1 "  -->  Detalles: " $5}' | sed 's/SIGSM-//g'
    echo "========================================================================="
}


### --- FUNCIONES DE GESTIÓN DE GRUPOS MANUALES ---
# Función para registrar un nuevo grupo personalizado en el sistema operativo
crear_grupo() {
    local grupo=$1

    # Validamos que se haya provisto el nombre del grupo
    if [ -z "$grupo" ]; then
        echo "[ - ] ERROR: Debe ingresar el nombre del grupo. Uso: crear_grupo <nombre_grupo>"
        return 1
    fi

    # Sumamos el prefijo para la coherencia de seguridad del hospital
    local grupo_completo="SIGSM-$grupo"

    # Validamos si el grupo ya se encuentra registrado en el archivo /etc/group
    if getent group "$grupo_completo" > /dev/null 2>&1; then
        echo "[ - ] ERROR: El grupo '$grupo_completo' ya existe en el sistema."
        return 1
    fi

    # Creamos el grupo utilizando groupadd de Linux
    groupadd "$grupo_completo"
    if [ $? -eq 0 ]; then
        echo "[ + ] ÉXITO: Grupo '$grupo_completo' creado correctamente en el servidor."
    else
        echo "[ - ] ERROR: No se pudo dar de alta al grupo."
        return 1
    fi
}

# Función para dar de baja un grupo personalizado
eliminar_grupo() {
    local grupo=$1

    # Validamos que se haya provisto el nombre del grupo a eliminar
    if [ -z "$grupo" ]; then
        echo "[ - ] ERROR: Debe indicar el grupo. Uso: eliminar_grupo <nombre_grupo>"
        return 1
    fi

    # Sumamos el prefijo para identificar de forma cheta el grupo del S.I.G.S.M.
    local grupo_completo="SIGSM-$grupo"

    # Validamos que el grupo exista en la base local antes de intentar removerlo
    if ! getent group "$grupo_completo" > /dev/null 2>&1; then
        echo "[ - ] ERROR: El grupo '$grupo_completo' no existe."
        return 1
    fi

    # Eliminamos el grupo utilizando groupdel
    groupdel "$grupo_completo"
    if [ $? -eq 0 ]; then
        echo "[ + ] ÉXITO: Grupo '$grupo_completo' eliminado de la base de datos."
    else
        echo "[ - ] ERROR: Falló la eliminación. No se puede borrar si es grupo primario de un usuario."
        return 1
    fi
}

# Función avanzada que lista los grupos registrados bajo el dominio de S.I.G.S.M. y sus miembros
listar_grupos_sigsm() {
    echo ""
    echo "========================================================================="
    echo "                 LISTADO DE GRUPOS ACTIVOS DEL S.I.G.S.M."
    echo "========================================================================="

    # Explicación técnica de la tubería de listado de grupos:
    # 1. "getent group" muestra todos los grupos locales del sistema.
    # 2. "grep 'SIGSM-'" aísla únicamente los grupos del hospital.
    # 3. "awk -F:" separa por dos puntos e imprime el nombre del grupo ($1) y los usuarios asociados ($4).
    # 4. "sed" remueve estéticamente los prefijos del sistema para mayor legibilidad/que quede mas cheto.
    # NOTA (Segunda Entrega): esto consulta el estado REAL del sistema operativo en el momento de la
    # ejecución, por lo que cumple el rol de "panel de grupos en
    # tiempo real" exigido por la letra del proyecto.
    getent group | grep "SIGSM-" | awk -F: '{print "Grupo: " $1 "  -->  Miembros: " $4}' | sed 's/SIGSM-//g'
    echo "========================================================================="
}


### --- FUNCIONES DE SEGURIDAD Y PROTECCIÓN DE DATOS (LEY 18.331) ---
# Función para cifrar de forma simétrica archivos confidenciales de administración.
cifrar_archivo() {
    local archivo=$1

    # Validamos que se haya indicado la ruta del archivo
    if [ -z "$archivo" ]; then
        echo "[ - ] ERROR: Debe ingresar la ruta del archivo a proteger."
        return 1
    fi

    # Comprobamos la existencia física del archivo antes de intentar cifrarlo
    if [ ! -f "$archivo" ]; then
        echo "[ - ] ERROR: El archivo '$archivo' no existe."
        return 1
    fi

    # Cifrado simétrico de nivel industrial usando OpenSSL:
    # enc -aes-256-cbc: Cifrado AES con clave de 256 bits en bloque encadenado.
    # -salt: Introduce un valor aleatorio de sal para robustecer la clave generada.
    # -pbkdf2: Utiliza el estándar de derivación de claves PBKDF2.
    # -in: Archivo de datos originales.
    # -out: Archivo protegido final con extensión .enc.
    openssl enc -aes-256-cbc -salt -pbkdf2 -in "$archivo" -out "$archivo.enc"

    if [ $? -eq 0 ]; then
        echo "[ + ] ÉXITO: Archivo '$archivo' cifrado bajo protocolo AES-256-CBC."
        echo "[ + ] SEGURIDAD: El documento protegido se guardó como '$archivo.enc'."
        # Removemos de forma definitiva el archivo de texto plano para evitar filtraciones de datos
        rm -f "$archivo"
        echo "[ + ] SEGURIDAD: Archivo original en texto plano eliminado del disco local."
    else
        echo "[ - ] ERROR: Ocurrió un fallo al intentar cifrar el archivo de datos."
        return 1
    fi
}

# Función para revertir el cifrado y permitir la lectura del archivo a administradores autorizados.
descifrar_archivo() {
    local archivo=$1

    # Validamos que se haya indicado el nombre del archivo protegido
    if [ -z "$archivo" ]; then
        echo "[ - ] ERROR: Debe indicar el archivo .enc a descifrar."
        return 1
    fi

    # Comprobamos la existencia de la versión cifrada
    if [ ! -f "$archivo" ]; then
        echo "[ - ] ERROR: El archivo cifrado '$archivo' no existe."
        return 1
    fi

    # Quitamos la extensión .enc para recrear el nombre original del archivo recuperado
    local archivo_salida="${archivo%.enc}"

    # Ejecutamos OpenSSL en sentido inverso incorporando el flag "-d" (descifrado)
    openssl enc -aes-256-cbc -d -salt -pbkdf2 -in "$archivo" -out "$archivo_salida"

    if [ $? -eq 0 ]; then
        echo "[ + ] ÉXITO: Archivo descifrado. El documento se encuentra disponible en '$archivo_salida'."
        # Eliminamos la versión cifrada para evitar archivos redundantes en el servidor
        rm -f "$archivo"
    else
        echo "[ - ] ERROR: Falló el descifrado del archivo. Contraseña incorrecta o corrupta."
        return 1
    fi
}


### --- FUNCIÓN DE RESPALDOS AUTOMATIZADOS Y CIFRADOS ---
### Cumple con la Ley N.º 18.331 de Protección de Datos Personales al no dejar NUNCA
### una copia de respaldo en texto plano sobre el disco: el paquete se cifra apenas
### se genera, reutilizando la función cifrar_archivo() ya existente en el script.

# Función que empaqueta, comprime y cifra los datos críticos del servidor.
respaldo_sistema() {
    # Recibimos por parámetro las rutas a respaldar; si no se indica ninguna, usamos
    # por defecto el código de la aplicación y la configuración de red del servidor.
    local rutas_a_respaldar=("$@")
    if [ ${#rutas_a_respaldar[@]} -eq 0 ]; then
        rutas_a_respaldar=("$DIR_APP" "/etc/netplan/")
    fi

    # 1. VALIDACIÓN Y CREACIÓN DEL DIRECTORIO DE RESPALDOS:
    # El directorio de respaldos se dimensiona de forma INDEPENDIENTE al de la aplicación
    # (ver Sección 4 del informe), justamente para que una eventual saturación de /respaldos
    # no arrastre ni afecte el espacio disponible de la aplicación web.
    if [ ! -d "$DIR_RESPALDOS" ]; then
        mkdir -p "$DIR_RESPALDOS"
        echo "[ + ] INFO: Se creó el directorio de respaldos en '$DIR_RESPALDOS'."
    fi

    # 2. GENERACIÓN DEL NOMBRE DE ARCHIVO CON TRAZABILIDAD TEMPORAL:
    # "date +%Y%m%d_%H%M%S" arma una marca de tiempo Año-Mes-Día_Hora-Minuto-Segundo,
    # de forma que cada respaldo tenga un nombre único y ordenable cronológicamente.
    local fecha
    fecha=$(date +%Y%m%d_%H%M%S)
    local archivo_backup="$DIR_RESPALDOS/backup_sigsm_${fecha}.tar.gz"

    echo "[ i ] Iniciando empaquetado de: ${rutas_a_respaldar[*]}"

    # 3. EMPAQUETADO Y COMPRESIÓN:
    # tar -czf: Crea (c) un archivo comprimido con gzip (z) hacia el destino indicado (f).
    # Redirigimos stderr a /dev/null para que advertencias de permisos de archivos puntuales
    # no interrumpan el respaldo de los datos.
    tar -czf "$archivo_backup" "${rutas_a_respaldar[@]}" 2>/dev/null

    if [ $? -ne 0 ] && [ ! -s "$archivo_backup" ]; then
        echo "[ - ] ERROR: No se pudo generar el paquete de respaldo. Verifique las rutas indicadas."
        return 1
    fi

    echo "[ + ] Paquete comprimido generado: $archivo_backup"

    # 4. CIFRADO OBLIGATORIO DEL RESPALDO:
    # Reutilizamos la función cifrar_archivo() ya definida más arriba en el script.
    # Ella misma se encarga de eliminar el .tar.gz sin cifrar una vez generado el .enc,
    # por lo que NUNCA queda información sensible en texto plano sobre el disco.
    cifrar_archivo "$archivo_backup"

    if [ $? -eq 0 ]; then
        echo "[ + ] ÉXITO: Respaldo cifrado disponible en '${archivo_backup}.enc'."
        echo "[ i ] RECORDATORIO: la automatización periódica de esta tarea mediante cron"
        echo "      queda propuesta como mejora continua para la Tercera Entrega."
    else
        echo "[ - ] ERROR: El paquete se generó pero falló el proceso de cifrado."
        return 1
    fi
}


### FUNCIÓN DE MONITOREO DE RECURSOS DEL SERVIDOR
### Permite al operador consultar el estado del servidor sin tener que recordar cada
### comando individualmente, algo clave frente a picos de accesos concurrentes de
### pacientes escaneando códigos QR.

monitorear_recursos() {
    echo ""
    echo "========================================================================="
    echo "               ESTADO DE RECURSOS DEL SERVIDOR S.I.G.S.M."
    echo "========================================================================="

    # 1. ESPACIO EN DISCO:
    # "df -h" muestra el uso de disco en formato "human readable" (GB/MB en vez de bloques).
    # Filtramos a la partición raíz para no saturar la pantalla con montajes virtuales.
    echo "--- Uso de disco (partición raíz) ---"
    df -h /

    echo ""
    # 2. MEMORIA RAM:
    # "free -m" reporta la memoria total, usada y disponible en megabytes.
    echo "--- Uso de memoria RAM (MB) ---"
    free -m

    echo ""
    # 3. PROCESOS QUE MÁS CONSUMEN CPU:
    # "ps -eo comm,%cpu --sort=-%cpu" lista todos los procesos (e), mostrando el nombre del
    # comando y su porcentaje de CPU, ordenados de mayor a menor consumo.
    # "head -6" recorta el resultado al encabezado más los 5 procesos que más consumen.
    echo "--- Top 5 procesos por consumo de CPU ---"
    ps -eo comm,%cpu --sort=-%cpu | head -6

    echo "========================================================================="
}


### FUNCIÓN DE AUDITORÍA DE ACCESOS (LOGS) 
### IMPORTANTE: esta función se limita a CONSULTAR los logs existentes (últimas N líneas),


auditoria_accesos() {
    local cantidad_lineas=${1:-50}   # Si no se pasa parámetro, mostramos las últimas 50 líneas por defecto

    echo ""
    echo "========================================================================="
    echo "        AUDITORÍA DE ACCESOS - ÚLTIMAS $cantidad_lineas LÍNEAS DE LOG"
    echo "========================================================================="

    # 1. LOG DE AUTENTICACIÓN DEL SISTEMA (/var/log/auth.log):
    # Aquí quedan registrados TODOS los intentos de inicio de sesión, exitosos y fallidos,
    # incluyendo los intentos sobre el puerto SSH personalizado (ver hardening_ssh()).
    # Usamos "tail -n" (en lugar de "tail -f") para que el menú NO se quede esperando
    # indefinidamente por nuevas líneas y el operador pueda volver al menú principal.
    if [ -f /var/log/auth.log ]; then
        echo "--- /var/log/auth.log (últimas $cantidad_lineas líneas) ---"
        tail -n "$cantidad_lineas" /var/log/auth.log
    else
        echo "[ i ] No se encontró /var/log/auth.log en esta distribución."
    fi

    echo ""
    # 2. LOG ESPECÍFICO DEL SERVICIO SSH (systemd/journalctl):
    # "journalctl -u ssh" filtra el diario de systemd exclusivamente por la unidad "ssh".
    # "-n" limita la cantidad de líneas y "--no-pager" evita que journalctl abra un
    # visor interactivo que trabaría la ejecución del script.
    echo "--- journalctl -u ssh (últimas $cantidad_lineas líneas) ---"
    journalctl -u ssh -n "$cantidad_lineas" --no-pager 2>/dev/null

    echo "========================================================================="
    echo "[ i ] Sugerencia: repita intentos de conexión desde el puerto SSH configurado"
    echo "      para verificar aquí que las restricciones de la Sección 10 funcionan."
}


### FUNCIÓN DE HARDENING DEL SERVICIO SSH
### Automatiza los apartados A (deshabilitar root), B (cambio de puerto) y D (restricción
### de usuarios permitidos). El apartado C (autenticación por clave pública) NO se
### automatiza porque todavía no fue dictado en clase; queda documentado como pendiente.

hardening_ssh() {
    local nuevo_puerto=$1
    shift
    local usuarios_permitidos=("$@")   # El resto de los parámetros son los usuarios autorizados

    # 1. VALIDACIÓN DE PARÁMETROS:
    if [ -z "$nuevo_puerto" ] || [ ${#usuarios_permitidos[@]} -eq 0 ]; then
        echo "[ - ] ERROR: Sintaxis incorrecta."
        echo "Uso correcto: hardening_ssh <puerto_nuevo> <usuario1> <usuario2> ..."
        echo "Ejemplo: hardening_ssh 22022 edsu jMarcos"
        return 1
    fi

    # Validamos que el puerto sea efectivamente un número dentro del rango permitido
    if ! [[ "$nuevo_puerto" =~ ^[0-9]+$ ]] || [ "$nuevo_puerto" -lt 1024 ] || [ "$nuevo_puerto" -gt 65535 ]; then
        echo "[ - ] ERROR: El puerto debe ser un número entre 1024 y 65535 (rango no privilegiado)."
        return 1
    fi

    # 2. RESPALDO DEL ARCHIVO ORIGINAL ANTES DE TOCARLO:
    # Buena práctica de administración: NUNCA se edita una configuración crítica del
    # servidor sin conservar una copia previa para poder revertir ante cualquier error.
    local respaldo_sshd="${SSHD_CONFIG}.bak.$(date +%Y%m%d_%H%M%S)"
    cp "$SSHD_CONFIG" "$respaldo_sshd"
    echo "[ + ] INFO: Copia de seguridad del archivo original guardada en '$respaldo_sshd'."

    # 3. FUNCIÓN AUXILIAR INTERNA PARA FIJAR UNA DIRECTIVA DE FORMA IDEMPOTENTE:
    # Si la directiva ya existe (comentada o no), la reemplaza; si no existe, la agrega
    # al final del archivo. Esto evita duplicar líneas si el script se corre más de una vez.
    _set_directiva_ssh() {
        local directiva=$1
        local valor=$2
        if grep -qE "^[#[:space:]]*${directiva}[[:space:]]" "$SSHD_CONFIG"; then
            # sed -i: edita el archivo "in place". Usamos "|" como delimitador para no
            # entrar en conflicto con las barras "/" que puedan aparecer en algún valor.
            sed -i -E "s|^[#[:space:]]*${directiva}[[:space:]].*|${directiva} ${valor}|" "$SSHD_CONFIG"
        else
            echo "${directiva} ${valor}" >> "$SSHD_CONFIG"
        fi
    }

    # 4. APARTADO A - DESHABILITAR EL LOGIN DIRECTO DE ROOT:
    # Obliga a ingresar con una cuenta común y escalar privilegios mediante sudo,
    # eliminando una de las principales vías de ataque por fuerza bruta.
    _set_directiva_ssh "PermitRootLogin" "no"

    # 5. APARTADO B - CAMBIO DE PUERTO POR DEFECTO:
    # El puerto 22 es el principal objetivo de escaneos automatizados de bots;
    # moverlo a un puerto alto reduce drásticamente el ruido en los logs.
    _set_directiva_ssh "Port" "$nuevo_puerto"

    # 6. APARTADO D - RESTRICCIÓN EXPLÍCITA DE USUARIOS:
    # AllowUsers limita el acceso remoto EXCLUSIVAMENTE a las cuentas indicadas.
    _set_directiva_ssh "AllowUsers" "${usuarios_permitidos[*]}"

    # Endurecimientos adicionales de bajo riesgo, alineados con el fragmento documentado
    # en el informe (límite de intentos y tiempo de gracia para autenticar):
    _set_directiva_ssh "MaxAuthTries" "3"
    _set_directiva_ssh "LoginGraceTime" "30"

    # NOTA: NO se modifica "PasswordAuthentication" ni "PubkeyAuthentication" en esta
    # entrega, ya que ese cambio depende de la autenticación por clave pública
    # (apartado C), que queda fuera de alcance por no haber sido dictada en clase.

    # 7. VALIDACIÓN DE SINTAXIS ANTES DE APLICAR CAMBIOS:
    # "sshd -t" chequea la sintaxis del archivo SIN reiniciar el servicio. Es un paso
    # obligatorio para no quedar bloqueados fuera del servidor por un error de tipeo.
    if sshd -t; then
        echo "[ + ] ÉXITO: Sintaxis de '$SSHD_CONFIG' validada correctamente."
        systemctl restart ssh
        echo "[ + ] ÉXITO: Servicio SSH reiniciado. Nuevo puerto: $nuevo_puerto | Usuarios permitidos: ${usuarios_permitidos[*]}"
        echo "[ i ] RECOMENDACIÓN: mantenga esta sesión abierta y valide la nueva conexión"
        echo "      desde una segunda terminal antes de cerrar esta consola."
    else
        # 8. ROLLBACK AUTOMÁTICO ANTE ERROR DE SINTAXIS:
        # Si la validación falla, restauramos el archivo original desde el respaldo
        # para no dejar el servicio SSH en un estado inconsistente o caído.
        echo "[ - ] ERROR: Sintaxis inválida detectada. Restaurando configuración original..."
        cp "$respaldo_sshd" "$SSHD_CONFIG"
        echo "[ + ] INFO: Se restauró '$SSHD_CONFIG' desde el respaldo. El servicio SSH no fue modificado."
        return 1
    fi
}


### FUNCIÓN DE FIREWALL PERIMETRAL (UFW) ---
### Aplica la política "deny by default" y habilita únicamente los puertos que el
### sistema efectivamente necesita: SSH (en su puerto personalizado) y los puertos
### del futuro servidor web (80/443), ya previstos para la Tercera Entrega.

configurar_firewall() {
    local puerto_ssh=$1

    if [ -z "$puerto_ssh" ]; then
        echo "[ - ] ERROR: Debe indicar el puerto SSH habilitado actualmente."
        echo "Uso correcto: configurar_firewall <puerto_ssh>"
        echo "Ejemplo: configurar_firewall 22022"
        return 1
    fi

    # Verificamos que el comando ufw esté disponible antes de operar con él
    if ! command -v ufw > /dev/null 2>&1; then
        echo "[ - ] ERROR: El paquete 'ufw' no está instalado. Instálelo con: apt install ufw -y"
        return 1
    fi

    echo "[ i ] Aplicando política de firewall por defecto (denegar todo lo entrante)..."

    # 1. POLÍTICA POR DEFECTO:
    # Denegamos TODO el tráfico entrante y permitimos TODO el tráfico saliente.
    # Esto asegura que únicamente pase lo que habilitemos explícitamente a continuación.
    ufw default deny incoming
    ufw default allow outgoing

    # 2. HABILITACIÓN DE PUERTOS ESTRICTAMENTE NECESARIOS:
    echo "[ i ] Habilitando puerto SSH personalizado ($puerto_ssh/tcp)..."
    ufw allow "$puerto_ssh"/tcp

    echo "[ i ] Habilitando puertos del futuro servidor web (80/tcp y 443/tcp)..."
    ufw allow 80/tcp
    ufw allow 443/tcp

    # 3. ACTIVACIÓN DEL FIREWALL:
    # "--force" evita el prompt interactivo de confirmación, útil para ejecución vía script.
    ufw --force enable

    if [ $? -eq 0 ]; then
        echo "[ + ] ÉXITO: Firewall UFW activo y configurado."
        echo "--- Estado actual del firewall ---"
        ufw status verbose
    else
        echo "[ - ] ERROR: No se pudo habilitar el firewall UFW."
        return 1
    fi
}


### FUNCIÓN DE PERMISOS DEL DIRECTORIO DE LA APLICACIÓN 
### Protege la confidencialidad de la información médica gestionada por el S.I.G.S.M.:
### el dueño es el usuario del servidor web (www-data) y el grupo propietario es el
### personal técnico/administrativo del hospital (SIGSM-administrativos).

permisos_directorio_app() {
    # 1. CREACIÓN DEL DIRECTORIO (SI NO EXISTE):
    # "-p" crea también los directorios padres intermedios si no existieran (/var/www/html).
    mkdir -p "$DIR_APP"
    echo "[ + ] INFO: Directorio de la aplicación disponible en '$DIR_APP'."

    # Validamos que el grupo institucional exista antes de intentar asignarlo como dueño
    if ! getent group "$GRUPO_ADMINISTRATIVOS" > /dev/null 2>&1; then
        echo "[ - ] ERROR: El grupo '$GRUPO_ADMINISTRATIVOS' no existe. Ejecute primero la inicialización de grupos."
        return 1
    fi

    # 2. ASIGNACIÓN DE PROPIETARIO Y GRUPO (chown):
    # -R: Aplica el cambio de forma recursiva a todo el contenido ya existente dentro del directorio.
    # Dueño: www-data (usuario con el que corre Apache).
    # Grupo: SIGSM-administrativos (personal médico/administrativo con acceso operativo).
    chown -R "${USUARIO_WEB}:${GRUPO_ADMINISTRATIVOS}" "$DIR_APP"

    # 3. ASIGNACIÓN DE PERMISOS (chmod 770):
    # 7 (dueño) = lectura + escritura + ejecución.
    # 7 (grupo) = lectura + escritura + ejecución.
    # 0 (otros) = sin ningún permiso.
    # El bit de ejecución sobre un directorio es lo que permite "atravesarlo" y listar
    # su contenido; sin él, ni siquiera el propio grupo podría acceder a los archivos.
    chmod -R 770 "$DIR_APP"

    if [ $? -eq 0 ]; then
        echo "[ + ] ÉXITO: Permisos aplicados. Dueño=${USUARIO_WEB}, Grupo=${GRUPO_ADMINISTRATIVOS}, Modo=770."
        echo "--- Verificación (ls -ld) ---"
        ls -ld "$DIR_APP"
    else
        echo "[ - ] ERROR: Falló la aplicación de permisos sobre '$DIR_APP'."
        return 1
    fi
}

### LOGO DE EMPRESA TRANSFORMADO A CODIGO ASCII
Logo() {
	#Logo de Empresa transformado a CODIGO ASCII
	clear
	# Color cian / Celeste brillante (\e[1;36m)

	echo -e "\e[1;36m"

	cat << 'EOF'
                           #############################################%
                           #############################################%
                           #############################################%
                           #############################################%
                           ###########%%%%%%%%%#%%%%%%%%%%%%%%%%%%%%%%%%%
                           ##########%
                           ##########%
                           ##########%
                           ##########%
                           ##########%
                           ##########%
                           ##########%
                           ##########%
                           ##########%
                           %#########%
                             %########%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                              %########################################
                                %######################################
                                 %#####################################
                               %#######################################
                             %########%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                           %#########%
                           ##########%
                           ##########%
                           ##########%
                           ##########%
                           ##########%
                           ##########%
                           ##########%
                           ##########%
                           ##########%
                           ##########%
                           ##########%
                           #############################################%
                           #############################################%
                           %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                           %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                           @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
EOF

# Restablecer el Color normal de la Terminal
echo -e "\e[0m"

	echo " Inicianilizando sistema ... "
sleep 3
clear
}

# INICIO A LA FUNCION LOGO
Logo



### --- MENU PRINCIPAL E INTERFAZ PARA OPERAR ---
#Menu para poder Operar
menu() {
    while true; do
        echo ""
        echo "============================================================="
        echo "   S.I.G.S.M. - CONSOLA DE ADMINISTRACIÓN DEL SERVIDOR"
        echo "         (Ducasse, Galli, Salas, Correa - EDSU)"
        echo "============================================================="
        echo " --- Usuarios y Grupos --"
        echo " 1) Crear un Nuevo Usuario (Asociación automática a Grupo)"
        echo " 2) Eliminar un Usuario (Limpieza total de HOME)"
        echo " 3) Listar Usuarios del Sistema S.I.G.S.M."
        echo " 4) Crear un Grupo Personalizado"
        echo " 5) Eliminar un Grupo Personalizado"
        echo " 6) Listar Grupos del Sistema S.I.G.S.M. (tiempo real)"
        echo " <---------------------------------------------------------->"
        echo " --- Seguridad de Datos (Ley 18.331) ---"
        echo " 7) Cifrar un Archivo Sensible"
        echo " 8) Descifrar un Archivo Protegido"
        echo " 9) Generar Respaldo Cifrado del Sistema"
        echo "<----------------------------------------------------------->"
        echo " --- Monitoreo y Auditoría ---"
        echo "10) Monitorear Recursos del Servidor (CPU/RAM/Disco)"
        echo "11) Ver Auditoría de Accesos (Logs de autenticación/SSH)"
        echo "<----------------------------------------------------------->"
        echo " --- Hardening del Servidor ---"
        echo "12) Aplicar Hardening al Servicio SSH (sin clave pública)"
        echo "13) Configurar Firewall Perimetral (UFW)"
        echo "14) Aplicar Permisos al Directorio de la Aplicación"
        echo "<----------------------------------------------------------->"
        echo "15) Salir de la Consola"
        echo "============================================================="
        read -p "Seleccione una opción (1-15): " opcion

        case $opcion in
            1)
                echo ""
                echo "--- REGISTRO DE NUEVO USUARIO ---"
                read -p "Ingrese nombre de usuario para login (ej: jperez): " usr
                read -p "Ingrese rol (admin/administrativo): " rl
                read -p "Ingrese primer nombre (ej: Juan): " nom
                read -p "Ingrese primer apellido (ej: Perez): " ape
                # Pasamos los 4 parámetros posicionales a nuestra función
                crear_usuario "$usr" "$rl" "$nom" "$ape"
                ;;
            2)
                echo ""
                read -p "Ingrese el ID/Login del usuario a eliminar: " usr
                eliminar_usuario "$usr"
                ;;
            3)
                listar_usuarios_sigsm
                ;;
            4)
                echo ""
                read -p "Ingrese nombre del nuevo grupo (ej: seguridad): " grp
                crear_grupo "$grp"
                ;;
            5)
                echo ""
                read -p "Ingrese nombre del grupo a eliminar (sin prefijo SIGSM-): " grp
                eliminar_grupo "$grp"
                ;;
            6)
                listar_grupos_sigsm
                ;;
            7)
                echo ""
                read -p "Ruta del archivo con datos a cifrar (ej: /home/edsu/datos.txt): " arch
                cifrar_archivo "$arch"
                ;;
            8)
                echo ""
                read -p "Ruta del archivo .enc a descifrar (ej: /home/edsu/datos.txt.enc): " arch
                descifrar_archivo "$arch"
                ;;
            9)
                echo ""
                echo "--- GENERACIÓN DE RESPALDO CIFRADO ---"
                echo "Presione ENTER para usar las rutas por defecto (${DIR_APP} y /etc/netplan/),"
                read -p "o ingrese rutas separadas por espacio para respaldar otras carpetas: " -a rutas_custom
                respaldo_sistema "${rutas_custom[@]}"
                ;;
            10)
                monitorear_recursos
                ;;
            11)
                echo ""
                read -p "¿Cuántas líneas de log desea ver? (ENTER para 50 por defecto): " n_lineas
                auditoria_accesos "${n_lineas:-50}"
                ;;
            12)
                echo ""
                echo "--- HARDENING DEL SERVICIO SSH (sin autenticación por clave pública) ---"
                read -p "Ingrese el nuevo puerto SSH (ej: 22022): " puerto
                read -p "Ingrese los usuarios autorizados a conectarse, separados por espacio (ej: edsu jMarcos): " -a usuarios_ssh
                hardening_ssh "$puerto" "${usuarios_ssh[@]}"
                ;;
            13)
                echo ""
                read -p "Ingrese el puerto SSH actualmente habilitado (ej: 22022): " puerto_actual
                configurar_firewall "$puerto_actual"
                ;;
            14)
                permisos_directorio_app
                ;;
            15)
                echo ""
                echo "Cerrando consola de administración del S.I.G.S.M. ¡Gracias por Probar nuestro Proyecto!. EDSU :)"
                exit 0
                ;;
            *)
                echo ""
                echo "[ - ] ERROR: Opción fuera de rango. Por favor, seleccione un número entre 1 y 15."
                ;;
        esac
    done
}

# Iniciamos el bucle del menú
menu
