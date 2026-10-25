#!/bin/bash
### --- INICIALIZACIÓN SILENCIOSA DE GRUPOS ---
# Función para asegurar que el grupo del sistema exista antes de que el script opere.
# Al arrancar, creamos silenciosamente el grupo "SIGSM-administradores" (único rol con acceso al servidor).
inicializar_grupos_sistema() {
    # Lista de grupos definidos por el diseño de roles del sistema
    local grupos=("administradores")

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

    # Recorremos todos los grupos cuyo nombre comience con "SIGSM-" sin comillas claramente
    for grupo in $(getent group | grep "^SIGSM-" | cut -d: -f1); do

    # Obtenemos el GID correspondiente al grupo 
    gid=$(getent group "$grupo" | cut -d: -f3)

    # Buscamos usuarios cuyo grupo primario corresponda a este GID
    miembros_primarios=$(getent passwd |
	awk -F: -v g="$gid" '$4 == g {print $1}' |
	tr '\n' ',' |
	sed 's/,$//')

	# Obtenemos los usuarios que pertenecen al grupo como secundarios
	miembros_secundarios=$(getent group "$grupo" | cut -d: -f4)

# Unimos miembros primarios y secundarios sin comas sobrantes.
        if [ -n "$miembros_primarios" ] && [ -n "$miembros_secundarios" ]; then
            todos_miembros="$miembros_primarios,$miembros_secundarios"
        elif [ -n "$miembros_primarios" ]; then
            todos_miembros="$miembros_primarios"
        elif [ -n "$miembros_secundarios" ]; then
            todos_miembros="$miembros_secundarios"
        else
            todos_miembros="(Sin miembros asignados)"
        fi

		#Mostramos el nombre sin el prefijo SIGSM-
		nombre_limpio=$(echo "$grupo" | sed 's/^SIGSM-//')

		echo "Grupo: $nombre_limpio --> Miembros: $todos_miembros"
	done
    echo "========================================================================="
}
