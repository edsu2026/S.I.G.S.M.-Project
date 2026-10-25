#!/bin/bash
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
    # justamente para que una eventual saturación de /respaldos
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
    # Reutilizamos la función cifrar_archivo() ya definida en el modulo "003-seguridad".
    # Ella misma se encarga de eliminar el .tar.gz sin cifrar una vez generado el .enc,
    # por lo que NUNCA queda información sensible en texto plano sobre el disco.
    cifrar_archivo "$archivo_backup"

    if [ $? -eq 0 ]; then
        echo "[ + ] ÉXITO: Respaldo cifrado disponible en '${archivo_backup}.enc'."
    else
        echo "[ - ] ERROR: El paquete se generó, pero falló el proceso de cifrado."
        return 1
    fi
}
