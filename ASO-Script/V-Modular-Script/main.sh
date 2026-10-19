#!/bin/bash
### =========================================================================
### S.I.G.S.M. - Sistema Informático de Gestión de Servicios Médicos
### Script de Administración de Sistemas Operativos - Tercera Entrega Final
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
###   [CAMBIO] Rol unico "administradores" -> Se elimino por completo el rol "administrativos" (medicos y enfermeros),
###            ya que ese personal solo usa el frontend y NO tiene acceso al servidor (backend)
###   [NUEVO] Modularizacion de Scripts -> Se modularizo (se dividio los modulos del script y se creo un "main.sh" para que sea mas escalable)
### =========================================================================

### --- RUTAS DEL PROYECTO ---
# Calcula la carpeta donde está este main.sh, aunque se ejecute desde otro directorio.
DIR_PROYECTO="$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")" && pwd)"

### --- CONTROL DE PRIVILEGIOS DE ROOT ---
# La variable especial del sistema $EUID almacena el ID del usuario actual.
# En todos los sistemas Linux, el usuario Administrador (root) siempre tiene el ID 0.
# Si el ID no es igual a 0, detenemos el script de inmediato por seguridad.
if [ "$EUID" -ne 0 ]; then
    echo "[ - ] ERROR: Este script requiere privilegios de ADMINISTRADOR (ejecutar con sudo o sudo su)."
    exit 1
fi

### --- CARGA DE CONFIGURACIÓN Y MÓDULOS ---
# source carga las funciones/variables en este mismo proceso de Bash.
source "$DIR_PROYECTO/config/config.sh" || exit 1
source "$DIR_PROYECTO/modules/002-grupos.sh" || exit 1
source "$DIR_PROYECTO/modules/001-usuarios.sh" || exit 1
source "$DIR_PROYECTO/modules/003-seguridad.sh" || exit 1
source "$DIR_PROYECTO/modules/004-respaldos.sh" || exit 1
source "$DIR_PROYECTO/modules/005-monitoreo.sh" || exit 1

# Garantizamos que los grupos base existan antes de mostrar el menú.
inicializar_grupos_sistema

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
        echo " 1) Crear un Nuevo Usuario Administrador (Asociación automática a Grupo)"
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
                clear
                echo ""
                echo "--- REGISTRO DE NUEVO USUARIO ---"
                read -p "Ingrese nombre de usuario para login (ej: jperez): " usr
                read -p "Ingrese primer nombre (ej: Juan): " nom
                read -p "Ingrese primer apellido (ej: Perez): " ape
                # Pasamos los 3 parámetros posicionales a nuestra función
                crear_usuario "$usr" "$nom" "$ape"
                ;;
            2)
                clear
                echo ""
                read -p "Ingrese el ID/Login del usuario a eliminar: " usr
                eliminar_usuario "$usr"
                ;;
            3)
                clear
                listar_usuarios_sigsm
                ;;
            4)
                clear
                echo ""
                read -p "Ingrese nombre del nuevo grupo (ej: seguridad): " grp
                crear_grupo "$grp"
                ;;
            5)
                clear
                echo ""
                read -p "Ingrese nombre del grupo a eliminar (sin prefijo SIGSM-): " grp
                eliminar_grupo "$grp"
                ;;
            6)
                clear
                listar_grupos_sigsm
                ;;
            7)
                clear
                echo ""
                read -p "Ruta del archivo con datos a cifrar (ej: /home/edsu/datos.txt): " arch
                cifrar_archivo "$arch"
                ;;
            8)
                clear
                echo ""
                read -p "Ruta del archivo .enc a descifrar (ej: /home/edsu/datos.txt.enc): " arch
                descifrar_archivo "$arch"
                ;;
            9)
                clear
                echo ""
                echo "--- GENERACIÓN DE RESPALDO CIFRADO ---"
                echo "Presione ENTER para usar las rutas por defecto (${DIR_APP} y /etc/netplan/),"
                read -p "o ingrese rutas separadas por espacio para respaldar otras carpetas: " -a rutas_custom
                respaldo_sistema "${rutas_custom[@]}"
                ;;
            10)
                clear
                monitorear_recursos
                ;;
            11)
                clear
                echo ""
                read -p "¿Cuántas líneas de log desea ver? (ENTER para 50 por defecto): " n_lineas
                auditoria_accesos "${n_lineas:-50}"
                ;;
            12)
                clear
                echo ""
                echo "--- HARDENING DEL SERVICIO SSH (sin autenticación por clave pública) ---"
                read -p "Ingrese el nuevo puerto SSH (ej: 22022): " puerto
                read -p "Ingrese los usuarios autorizados a conectarse, separados por espacio (ej: edsu jMarcos): " -a usuarios_ssh
                hardening_ssh "$puerto" "${usuarios_ssh[@]}"
                ;;
            13)
                clear
                echo ""
                read -p "Ingrese el puerto SSH actualmente habilitado (ej: 22022): " puerto_actual
                configurar_firewall "$puerto_actual"
                ;;
            14)
                clear
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
