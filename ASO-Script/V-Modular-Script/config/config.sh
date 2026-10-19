#!/bin/bash
### Variables compartidas por los módulos del S.I.G.S.M.
# Editá estas rutas/valores acá para mantenerlos centralizados.

DIR_APP="/var/www/html/sigsm"                  # Directorio del código de la aplicación web
DIR_RESPALDOS="/respaldos"                     # Directorio aislado para respaldos cifrados
GRUPO_ADMINISTRADORES="SIGSM-administradores"  # Grupo propietario de la aplicación
USUARIO_WEB="www-data"                         # Usuario del servidor Apache
SSHD_CONFIG="/etc/ssh/sshd_config"             # Archivo de configuración SSH
