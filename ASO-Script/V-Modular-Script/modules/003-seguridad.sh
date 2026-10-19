#!/bin/bash
### --- SEGURIDAD, CIFRADO, SSH, FIREWALL Y PERMISOS ---

### --- FUNCIONES DE SEGURIDAD Y PROTECCIÓN DE DATOS (LEY 18.331) ---
# Función para cifrar de forma simétrica archivos confidenciales de administración.
cifrar_archivo() {
    local archivo=$1

    # Validamos que se haya indicado la ruta del archivo (sin espacios en blanco)
    if [ -z "$archivo" ]; then
        echo "[ - ] ERROR: Debe ingresar la ruta del archivo a proteger."
        return 1
    fi

    # Comprobamos la existencia del archivo antes de intentar cifrarlo
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

    # 3. FUNCIÓN AUXILIAR INTERNA PARA FIJAR UNA DIRECTIVA:
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
    # en el informe (límite de intentos y tiempo para autenticar):
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
### del servidor web (80/443), ya previstos para la Tercera Entrega.

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
### el dueño es el usuario del servidor web (www-data) y el grupo propietario son los
### administradores del servidor (SIGSM-administradores). El personal médico y de enfermería
### NO accede a este directorio: solo usa la aplicación a través del navegador (frontend).

permisos_directorio_app() {
    # 1. CREACIÓN DEL DIRECTORIO (SI NO EXISTE):
    # "-p" crea también los directorios padres intermedios si no existieran (/var/www/html).
    mkdir -p "$DIR_APP"
    echo "[ + ] INFO: Directorio de la aplicación disponible en '$DIR_APP'."

    # Validamos que el grupo institucional exista antes de intentar asignarlo como dueño
    if ! getent group "$GRUPO_ADMINISTRADORES" > /dev/null 2>&1; then
        echo "[ - ] ERROR: El grupo '$GRUPO_ADMINISTRADORES' no existe. Ejecute primero la inicialización de grupos."
        return 1
    fi

    # 2. ASIGNACIÓN DE PROPIETARIO Y GRUPO (chown):
    # -R: Aplica el cambio de forma recursiva a todo el contenido ya existente dentro del directorio.
    # Dueño: www-data (usuario con el que corre Apache).
    # Grupo: SIGSM-administradores (administradores del servidor con acceso operativo).
    chown -R "${USUARIO_WEB}:${GRUPO_ADMINISTRADORES}" "$DIR_APP"

    # 3. ASIGNACIÓN DE PERMISOS (chmod 770):
    # 7 (dueño) = lectura + escritura + ejecución.
    # 7 (grupo) = lectura + escritura + ejecución.
    # 0 (otros) = sin ningún permiso.
    # El bit de ejecución sobre un directorio es lo que permite "atravesarlo" y listar
    # su contenido; sin él, ni siquiera el propio grupo podría acceder a los archivos.
    chmod -R 770 "$DIR_APP"

    if [ $? -eq 0 ]; then
        echo "[ + ] ÉXITO: Permisos aplicados. Dueño=${USUARIO_WEB}, Grupo=${GRUPO_ADMINISTRADORES}, Modo=770."
        echo "--- Verificación (ls -ld) ---"
        ls -ld "$DIR_APP"
    else
        echo "[ - ] ERROR: Falló la aplicación de permisos sobre '$DIR_APP'."
        return 1
    fi
}
