#!/bin/bash
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
    # "free -m" muestra la memoria total, usada y disponible en megabytes.
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
}
