#!/bin/bash

### --- FUNCIONES DE GESTIÓN DE USUARIOS Y ASIGNACIÓN AUTOMÁTICA ---
# Función avanzada para crear un usuario ADMINISTRADOR, registrar sus datos personales y asignarle el grupo de administradores de forma automática.
# En el servidor (backend) SOLO existen administradores: el personal médico y de enfermería (administrativos)
# utiliza únicamente el frontend de la aplicación web, por lo que NO tiene cuenta en el sistema operativo.
crear_usuario() {
    # CAPTURA EXPLICATIVA DE PARÁMETROS (BASH):
    # $1 = Nombre de usuario(Identificador) que usará para loguearse en el sistema (ej: mducasse)
    # $2 = Primer nombre de la persona (ej: Mateo)
    # $3 = Primer apellido de la persona (ej: Ducasse)
    local username=$1
    local nombre=$2
    local apellido=$3

    # Único rol existente en el servidor. Se usa para armar el campo de metadatos (SIGSM-admin - Nombre Apellido).
    local rol="admin"

    # 1. VALIDACIÓN DE CAMPOS VACÍOS:
    # El operador "-z" evalúa si las variables de caracteres están vacías.
    # Si falta alguno de los tres parámetros, mostramos el uso correcto en pantalla y frenamos la función.
    if [ -z "$username" ] || [ -z "$nombre" ] || [ -z "$apellido" ]; then
        echo "[ - ] ERROR: Sintaxis de creación incorrecta."
        echo "Uso correcto: crear_usuario <usuario> <Nombre> <Apellido>"
        echo "Ejemplo: crear_usuario lmateo Mateo Ducasse"
        return 1
    fi

    # 2. VALIDACIÓN DE USUARIOS DUPLICADOS:
    # "id" busca el login en las cuentas activas del sistema operativo.
    # Si ya existe, emitimos un aviso y terminamos para no generar inconsistencias.
    if id "$username" > /dev/null 2>&1; then
        echo "[ - ] ERROR: El usuario '$username' ya está registrado en el servidor."
        return 1
    fi

    # 3. ASIGNACIÓN AUTOMÁTICA DEL GRUPO:
    # Como solo existen administradores, el grupo de destino es siempre el mismo.
    # Este grupo se crea al arrancar el script mediante inicializar_grupos_sistema(), definida en modules/002-grupos.sh.
    local grupo_destino="SIGSM-administradores"

    # Confirmamos que el grupo elegido exista antes de crear la cuenta.
    if ! getent group "$grupo_destino" > /dev/null 2>&1; then
        echo "[ - ] ERROR: El grupo '$grupo_destino' no existe."
        return 1
    fi

    # 4. CREACIÓN SEGURA DE LA CUENTA EN LINUX:
    # -m: Genera automáticamente el directorio del usuario en /home/usuario.
    # -s /bin/bash: Configura la consola interactiva de comandos por defecto.
    # -g: Define el grupo PRIMARIO al que pertenece (siempre SIGSM-administradores).
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
