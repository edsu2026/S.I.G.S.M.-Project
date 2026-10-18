<!-- Si vas a tocar codigo de aca, recorda que esto es LENGUAJE MARKDOWN, antes de TOCAR ALGO busca como es la sintaxis de Markdown
    Es facil igual, pero de todas forma TENE CUIDADO, no vayas a romper algo (Esto es un comentario, no se ve en el codigo) -->

<!-- Aca empieza el Codigo (Despues de este comentario y el de abajo) -->

<!-- Centro los textos tipo titulo -->
<div align="center">
    
# S.I.G.S.M.
### Sistema informatico de Gestión de Servicios Médicos

</div>

<!-- Este es para centrar los cosos estos animados -->
<div align="center">
    
[![Estado](https://img.shields.io/badge/Estado-En%20desarrollo-yellow?style=for-the-badge&logo=github)](https://github.com/)
[![Cliente](https://img.shields.io/badge/Cliente-Hospital%20de%20Cl%C3%ADnicas-red?style=for-the-badge&logo=hospital)](https://github.com/)
[![Institución](https://img.shields.io/badge/Instituci%C3%B3n-E.T.A.S.%20%2F%20UTU%202026-blue?style=for-the-badge)](https://github.com/)
[![Empresa](https://img.shields.io/badge/Empresa-EDSU-purple?style=for-the-badge&logo=github)](https://github.com/)

</div>

---

## 🚀 Tecnologias:
![PHP](https://img.shields.io/badge/PHP_8.x-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL_%2F_MariaDB-00000F?style=for-the-badge&logo=mysql&logoColor=white)
![Apache](https://img.shields.io/badge/Apache_HTTP_Server-D22128?style=for-the-badge&logo=apache&logoColor=white)
![Ubuntu](https://img.shields.io/badge/Ubuntu_Server_24.04-E95420?style=for-the-badge&logo=ubuntu&logoColor=white)
![Linux](https://img.shields.io/badge/Linux-FCC624?style=for-the-badge&logo=linux&logoColor=black)
![Bash](https://img.shields.io/badge/GNU_Bash-4EAA25?style=for-the-badge&logo=gnu-bash&logoColor=white)
![XAMPP](https://img.shields.io/badge/XAMPP-FB7A24?style=for-the-badge&logo=xampp&logoColor=white)
![OpenSSL](https://img.shields.io/badge/OpenSSL_AES--256-721412?style=for-the-badge&logo=openssl&logoColor=white)
![Git](https://img.shields.io/badge/Git-F05032?style=for-the-badge&logo=git&logoColor=white)

---

## 📋 Tabla de Contenidos:
- [💡 Acerca del Proyecto](#-acerca-del-proyecto)
- [👥 Equipo de Desarrollo (EDSU)](#-equipo-de-desarrollo-edsu)
- [🚀 Instalacion y Despliegue](#-instalacion-y-despliegue)
- [🐧 Sistemas Operativos y Seguridad (ASO)](#-sistemas-operativos-y-seguridad-aso)
- [👥 Desarrollo Web & Experiencia de Usuario](#-desarrollo-web--experiencia-de-usuario)
- [</> Uso de la Consola de Operador (script)](#-uso-de-la-consola-de-operador-script)

---
## 💡 Acerca del Proyecto 
**S.I.G.S.M.** es una plataforma web e infraestructura de servidor diseñada para modernizar, digitalizar y resguardar información médica de el **Hospital de Clínicas**.
---
## 👥 Equipo de Desarrollo (EDSU)
| Integrante | Rol Principal | Especialidad Técnica |
| :--- | :--- | :--- |
| **Mateo Ducasse** | Admin. de Sistemas / Dev / Tester | Scripts Bash, Hardening SSH (Puertos), Cifrado OpenSSL, Netplan |
| **Ian Galli** | Desarrollador Full Stack | Frontend Responsivo, PHP/MySQL, Mobile-First, QR |
| **Valentin Correa** | Admin. de Base de Datos / Dev | RF/RNF, Modelado Entidad-Relación, Diagramas de Casos de Uso UML, Consultas MySQL |
| **Marcos Salas** | Project Manager / Documentación | Documentacion, Diagrama de Gantt, Auditoría de Proyecto |
---
## 🐧 Sistemas Operativos y Seguridad (ASO)
La infraestructura del servidor cuenta con seguridad bastante pulida.

### 1. Configuración de Red
- **Adaptador Puente (Bridged):** Esto solo lo usamos nosotros para trabajar con SSH del PC Host a la VM.
- **IP Estática permanente:** Configurada en `/etc/netplan/00-installer-config.yaml` (`192.168.1.50/24`).

### 2. Cortafuegos (UFW)
- Política por defecto: `default deny incoming` (Bloquear todo el tráfico entrante no autorizado).
- Puertos habilitados:
  - `22022/tcp` — Acceso remoto seguro SSH (Se utiliza un puerto personalizado configurable mediante un script, deshabilitando el puerto 22 (predeterminado) para                    reducir la exposición ante escaneos y accesos automatizados.)
  - `80/tcp` — Protocolo Web HTTP
  - `443/tcp` — Protocolo Web Seguro HTTPS

### 3. Hardening SSH
- Puerto de escucha modificado de 22 al **`22022`** (Se puede modificar cuando quieran).
- Acceso directo de `root` deshabilitado (`PermitRootLogin no`).
- Lista blanca de usuarios autorizados (`AllowUsers edsu`).
- Política de reintentos restringida (`MaxAuthTries 3`, `LoginGraceTime 30`).
  <!-- MaxAuthTries 3:
                      - Máximo de 3 intentos de autenticación.
       LoginGraceTime 30:
                      - Da 30 segundos para completar el inicio de sesión.
   -->
- **Validación de la configuración:** antes de reiniciar el servicio SSH, se comprueba la sintaxis de `sshd_config` mediante `sshd -t`.
- **Respaldo de la configuración:** antes de realizar cambios, se genera una copia de seguridad de `/etc/ssh/sshd_config`.
- **Rollback automático:** si la validación de `sshd_config` falla, el script restaura automáticamente la configuración original.
- **Reinicio automático del servicio:** si la configuración es válida, el servicio SSH se reinicia aplicando los nuevos parámetros.
- **Puerto configurable:** el puerto SSH puede modificarse desde el script.
- **Usuarios autorizados configurables:** la lista de usuarios permitidos mediante `AllowUsers` puede definirse desde el script.

### 4. Permisos de Archivos y Directorios
- Directorio de la aplicación web `/var/www/html/sigsm` configurado con permiso  **`770`** (`drwxrwx---`).
- Propietario: `www-data` | Grupo: `SIGSM-administradores`.
- Impide el acceso no autorizado desde la consola local a datos médicos sensibles.

### 5. Roles y Acceso al Servidor
Al servidor (backend) **solo acceden los Administradores**. El personal administrativo del hospital y los pacientes usan unicamente el frontend (la aplicacion web), por lo que **no tienen cuenta en el sistema operativo**.

| Perfil | Cuenta en el servidor | Como accede |
| :--- | :---: | :--- |
| **Administradores** (`SIGSM-administradores`) | ✅ Si | SSH (puerto `22022`) y Consola de Operador |
| **Personal administrativo** | ❌ No | Portal web con sus credenciales habituales del hospital |
| **Pacientes** | ❌ No | Escaneando el codigo QR desde el celular (sin usuario) |
| `www-data` | ⚙️ Cuenta de servicio | Usuario con el que corre Apache, dueño de la aplicacion |

- Existe un unico grupo de sistema: `SIGSM-administradores` (se crea solo al iniciar la consola).
- Los permisos del personal administrativo (cargar documentos, encuestas, etc.) se controlan **dentro de la aplicacion** (PHP/MySQL), no con usuarios o grupos de Linux.
- Se aplica el principio de **minimo privilegio**: quien no administra el servidor no tiene acceso a la consola, a SSH ni a los datos del servidor.
---

## 🚀 Instalacion y Despliegue
Para desarrollar, testear o simplemente deplegar la aplicacion de forma local (En su propia PC/Servidor)
<details> <!-- Crea el contenedor desplegable -->
    <summary><b>Ver paso a paso la Configuracion de XAMPP(hace click👆)</b></summary> <!-- Titulo Visible del desplegable con Negrita (el <b></b>) -->

<br> <!-- Este es para colocar texto abajo -->

#### **PASO 1: Ubicacion de Archivos**
Descarga o clona el repositorio manteniendo el orden original de las carpetas tal y como se presetan en este, deberan de instalarse
en la siguiente ruta: C:/xampp/htdocs (Si te aparece reemplazar algo dale todo que si)

#### **PASO 2: Inicio de Servicios**
Abre el **XAMPP CONTROL PANEL** e inicia los servicios:
- [x] **Apache**
- [x] **MySQL**  

#### **PASO 3: Carga de Base de datos en phpMyAdmin**
1. Accedé a **phpMyAdmin** desde tu navegador:
   [http://localhost/phpmyadmin/](http://localhost/phpmyadmin/)

2. En la barra superior, seleccioná la pestaña **"SQL"**.

3. Dentro de la carpeta raíz del proyecto descargado, dirigite a la siguiente ruta:

   ```text
   assets/sql/basededatos.sql

4. Abrí el archivo ```basededatos.sql``` con cualquier editor de código, como Visual Studio Code

5. Seleccioná todo el contenido del archivo:
  
    ```text
    Ctrl + A
    
6. Copia todo el contenido del archivo:
  
    ```text
    Ctrl + C

7. Volvé a la pestaña "SQL" de phpMyAdmin y pegá todo el contenido del script:
  
    ```text
    Ctrl + V

8. Finalmente, ejecutá el script mediante el botón ```"Continuar"``` que aparece en phpMyAdmin(Ya quedaria la BD Importada Correctamente)
    
</details>

## 👥 Desarrollo Web & Experiencia de Usuario
**Interfaces Mobile-First** Desarrollada para dar Prioridad a cualquier tipo de dispositivo Movil facilitando al Usuario y siendo amigable.  
<!-- Primero las Interfaces Mobiles -->

**Modulo QR para Pacientes** Escaneo rapido para ingresar al portal de Pacientes (Donde se podra ver cosas como los Documentos, Encuestas.etc)

**Gestion Administrativa** Esto ya depende mas del script y del Portal web (frontend) usado por el personal administrativo, que es la parte para cargar documentos, actualizar, dar permisos, quitar permisos, agregar usuarios/grupos a roles.etc

---  

## </> Uso de la Consola de Operador (script)

La gestion se realiza mediante el script interactivo que desarrollamos: `main.sh`.
Es modular: carga la configuracion (`config/config.sh`) y cada modulo de la carpeta `modules/` con `source`.

```text
sigsm/
├── main.sh                  # Punto de entrada: control de root, logo y menu
├── config/
│   └── config.sh            # Variables compartidas (rutas, grupo, usuario web)
└── modules/
    ├── 001-usuarios.sh      # Crear / eliminar / listar usuarios administradores
    ├── 002-grupos.sh        # Inicializacion y gestion de grupos
    ├── 003-seguridad.sh     # Cifrado, hardening SSH, firewall y permisos
    ├── 004-respaldos.sh     # Respaldos cifrados
    └── 005-monitoreo.sh     # Monitoreo de recursos y auditoria de accesos
```

```bash 
# Otorgar permisos de ejecución
chmod +x main.sh
```
<!-- El "bash" se podria cambiar tmb por cualquier lenguaje que agarre markdown, que puede ser css, python, javascript, java, html.etc (No se si agarra todos, pero agarra bastantes) -->

```bash 
# Abrirlo (SIEMPRE CON ROOT, ya que controlas cosas criticas con el script)
sudo ./main.sh
```

### Banner de la Empresa en Consola
El script incluye tambien un banner/logo en arte ASCII de **EDSU** solamente utilizando nuestro logo que es una **E** con colores cian.

```text
=============================================================
   S.I.G.S.M. - CONSOLA DE ADMINISTRACIÓN DEL SERVIDOR
         (Ducasse, Galli, Salas, Correa - EDSU)
=============================================================
 --- Usuarios y Grupos --
 1) Crear un Nuevo Usuario Administrador (Asociación automática a Grupo)
 2) Eliminar un Usuario (Limpieza total de HOME)
 3) Listar Usuarios del Sistema S.I.G.S.M.
 4) Crear un Grupo Personalizado
 5) Eliminar un Grupo Personalizado
 6) Listar Grupos del Sistema S.I.G.S.M. (tiempo real)
 <---------------------------------------------------------->
 --- Seguridad de Datos (Ley 18.331) ---
 7) Cifrar un Archivo Sensible
 8) Descifrar un Archivo Protegido
 9) Generar Respaldo Cifrado del Sistema
<----------------------------------------------------------->
 --- Monitoreo y Auditoría ---
10) Monitorear Recursos del Servidor (CPU/RAM/Disco)
11) Ver Auditoría de Accesos (Logs de autenticación/SSH)
<----------------------------------------------------------->
 --- Hardening del Servidor ---
12) Aplicar Hardening al Servicio SSH (sin clave pública)
13) Configurar Firewall Perimetral (UFW)
14) Aplicar Permisos al Directorio de la Aplicación
<----------------------------------------------------------->
15) Salir de la Consola
=============================================================
```

---
<!-- Si estas leyendo esto, te recuerdo que faltan cosas aun y que por eso no se explico cada opcion, ni se detallo a fondo, aun faltan varias cositas que voy a ir agregando con el tiempo porq me toma tiempo documentar correctamente. -->

<details>
    <summary>
        <b>¿Como funciona el Menu de la Shell?(hace click👆)</b>
    </summary>
<br> <!-- Este es un Salto de linea  -->

Antes de mostrar el menu, `main.sh` hace tres cosas de forma automatica:

- Verifica que se este ejecutando como **root** (con `sudo`). Si no es asi, muestra un error y termina el script.
- Carga la configuracion (`config/config.sh`) y todos los modulos (`modules/001` a `modules/005`).
- Se CREA Automaticamente el grupo del unico rol que existe en el servidor: `SIGSM-administradores` ("admin"), si todavia no existe. El personal administrativo del area de la salud solo usa la aplicacion web y NO tiene cuenta en el servidor.

Despues muestra el logo de la empresa y el menu, que se repite en bucle hasta que elijas la opcion 15.

Opciones del Menu:

## -- Usuarios y Grupos -- 
```text
1) Crear un Nuevo Usuario Administrador (Asociación automática a Grupo)
```
Esta opcion primero te va a pedir ingresar unos datos NECESARIOS y OBLIGATORIOS:
- username: Nombre de Usuario (Identificador) que usaras para logearte al sistema
- nombre: Primer nombre de la persona
- apellido: Primer Apellido de la persona

Valida campos vacios, tambien va a buscar que el username ingresado que NO ESTE REGISTRADO en el sistema, si esta registrado en el sistema actual entonces te va a devolver un error la terminal.

Como en el servidor solo existen Administradores, ya NO se pide un rol: toda cuenta creada con esta opcion se asigna automaticamente como grupo primario a ```SIGSM-administradores```.

Tambien le genera un Home a ese usuario(serviria para que cada Administrador tenga su espacio/cuenta propia). 
ej: ```"/home/mateo"```

Por ultimo, guarda en el campo de metadatos de la cuenta el texto ```SIGSM-admin - Nombre Apellido``` (es lo que despues usa la opcion 3 para listar), y le asigna una **contrasena temporal igual al nombre de usuario**, que queda **expirada**: en el primer login por SSH el sistema obliga a cambiarla.

```text
2) Eliminar un Usuario (Limpieza total de HOME)
```
Esta opcion permite eliminar un usuario del sistema junto con TODOS sus archivos y configuraciones almacenadas en su home (/home/user)

- Debes indicar obligatoriamente el nombre de usuario que quieres eliminar. Si no se proporciona ningun usuario, la operacion devolvera error.

- El sistema verificara que el usuario indicado exista.

- Si el usuario no existe, se mostrara un mensaje de error y te volvera al Menu con todas las opciones nuevamente.

- Si el usuario existe, sera eliminado tanto el usuario como su directorio personal (/home/user), incluyendo archivos, configuraciones y carpetas que contenga este.

> **⚠️ Advertencia:** Esta opcion es DESTRUCTIVA. CUIDADO CON SU USO, ya que justamente se pierde toda la home (carpeta personal) de ese usuario, por eso es importante tener en cuenta el USO CUIDADOSO de esta opcion.

```text
3) Listar Usuarios del Sistema S.I.G.S.M.
```
Esta opcion muestra en pantalla todas las cuentas existentes en el servidor. Posteriormente, utiliza el prefijo ```SIGSM-``` para filtrar y aislar unicamente las cuentas pertenecientes al sistema.

Para cada usuario encontrado, se obtiene y muestra la siguiente informacion:
- Nombre de usuario: Gracias a que busca en el servidor con el prefijo ```SIGSM-``` para aislar bien los nombres del sistema obtenemos los nombres de usuarios.

- rol: Gracias a el campo de metadatos donde se guarda "SIGSM-admin - Nombre Apellido" en esto caso mostraria en pantalla "rol - nombre". ej: "admin - mateo".

```text
4) Crear un Grupo Personalizado
```
Esta opcion permite crear un nuevo grupo en el servidor. Para hacerlo, se debe ingresar el nombre del grupo que desea crear.

El sistema agrega automaticamente el prefijo ```SIGSM-``` al nombre proporcionado y verifica que el grupo no exista previamente. Si ya se encuentra registrado, se mostrar un mensaje de error y no se realizara ninguna modificacion.

Si el grupo no existe, sera creado en el sistema.

```text
5) Eliminar un Grupo Personalizado
```
Esta opcion permite eliminar un grupo personalizado del servidor. Para realizar la operacion, se debe ingresar el nombre del grupo que se desea eliminar (sin el prefijo `SIGSM-`).

El sistema agrega automaticamente el prefijo ```SIGSM-``` al nombre proporcionado y verifica que el grupo exista previamente. Si el grupo no existe, se mostrara un mensaje de error y no se realizara ninguna modificacion.

Si el grupo existe, se procedera a eliminarlo del sistema. En caso de que el grupo sea utilizado como grupo primario de algun usuario la eliminacion no podra realizarse y mostrara error.

```text
6) Listar Grupos del Sistema S.I.G.S.M. (tiempo real)
```
Obtiene los grupos pertenecientes al sistema S.I.G.S.M, filtra los grupos internos del OS y los imprime de forma "limpia" osea por asi decirlo sacandole texto inecesario, mostrando el grupo y sus miembros asociados.

- Para cada grupo `SIGSM-` junta a los miembros **primarios** (usuarios cuyo grupo principal es ese) y a los **secundarios** (los que figuran en la lista de miembros del grupo).
- Si el grupo no tiene a nadie, muestra `(Sin miembros asignados)`.
- El nombre se muestra sin el prefijo. ej: `Grupo: administradores --> Miembros: mateo,ian`

Como lee los datos directamente del sistema cada vez que se ejecuta, siempre muestra el estado actual (por eso "tiempo real").

## -- Seguridad de Datos (Ley 18.331) -- 
```text
7) Cifrar un Archivo Sensible
```
Esta opcion protege un archivo confidencial para que nadie pueda leerlo sin la contraseña. Te pide la **ruta** del archivo. ej: ```/home/edsu/datos.txt```

- Valida que se haya ingresado una ruta y que el archivo exista. Si no, devuelve error y vuelve al Menu.

- Cifra el archivo con **OpenSSL**.

- La contraseña te la pide OpenSSL por pantalla en ese momento (no queda guardada en ningun lado).

- El resultado se guarda al lado del original con la extension `.enc`. ej: `datos.txt.enc`

- Si el cifrado salio bien, **elimina el archivo original en texto plano** para que no queden datos sensibles sin proteger en el disco.

> **⚠️ Advertencia:** Si te olvidas la contraseña NO hay forma de recuperar el archivo, y el original ya fue eliminado. Guardala en un lugar seguro.

```text
8) Descifrar un Archivo Protegido
```
Esta opcion hace el camino inverso a la opcion 7: permite volver a leer un archivo cifrado. Te pide la ruta del archivo `.enc`. ej: ```/home/edsu/datos.txt.enc```

- Valida que se haya ingresado la ruta y que el archivo cifrado exista.

- Te pide la contraseña con la que fue cifrado y lo descifra con los mismos parametros de la opcion 7.

- El archivo recuperado se guarda con el nombre original (sin el `.enc`). ej: `datos.txt`

- Si salio bien, **elimina el archivo `.enc`** para no dejar copias redundantes.

- Si la contraseña es incorrecta o el archivo esta corrupto, muestra un error y el `.enc` NO se elimina.

> **⚠️ Advertencia:** Una vez descifrado el archivo queda en **texto plano**. Si contiene datos sensibles recorda volver a cifrarlo con la opcion 7 cuando termines de usarlo.

```text
9) Generar Respaldo Cifrado del Sistema
```
Esta opcion genera una copia de seguridad (backup) de los datos criticos del servidor y la deja **cifrada**, cumpliendo con la Ley 18.331: NUNCA queda un respaldo en texto plano en el disco.

Primero te pregunta que queres respaldar:
- Si apretas **ENTER** usa las rutas por defecto: el directorio de la aplicacion (`DIR_APP`) y la configuracion de red (`/etc/netplan/`).
- Si queres otras carpetas, ingresalas separadas por espacio. ej: `/var/www/html/sigsm /etc/ssh`

Despues el sistema hace lo siguiente de forma automatica:
- Crea el directorio de respaldos (`DIR_RESPALDOS`) si todavia no existe. Esta separado del directorio de la aplicacion a proposito: si se llena, no afecta a la aplicacion web.
- Empaqueta y comprime las rutas en un `.tar.gz` con fecha y hora en el nombre. ej: `backup_sigsm_20260518_143015.tar.gz`
- Lo cifra reutilizando la funcion de la opcion 7 (te pide la contraseña) y elimina el `.tar.gz` sin cifrar.
- El resultado final es el archivo `.tar.gz.enc` dentro de `DIR_RESPALDOS`.

Si no se pudo generar el paquete (por ejemplo, rutas inexistentes) muestra un error y no sigue.

> **💡 Para restaurar:** primero descifra el respaldo con la opcion 8 y despues extrae el `.tar.gz` resultante con `tar -xzf`.

## -- Monitoreo y Auditoría -- 
```text
10) Monitorear Recursos del Servidor (CPU/RAM/Disco)
```
Esta opcion NO pide ningun dato: muestra en pantalla el estado actual del servidor, para no tener que recordar cada comando. Es clave frente a picos de accesos concurrentes de pacientes escaneando codigos QR.

Muestra tres cosas:
- **Disco:** uso de la particion raiz (`/`) en formato legible (GB/MB).
- **Memoria RAM:** total, usada y disponible, en megabytes.
- **CPU:** los 5 procesos que mas CPU consumen en ese momento, con su porcentaje.

Es solo de lectura, no modifica nada del sistema.

```text
11) Ver Auditoría de Accesos (Logs de autenticación/SSH)
```
Esta opcion permite revisar quien intento entrar al servidor. Te pregunta cuantas lineas de log queres ver (ENTER para usar **50** por defecto).

Consulta dos fuentes:
- `/var/log/auth.log`: registro de TODOS los intentos de inicio de sesion, exitosos y fallidos, incluyendo los del puerto SSH personalizado (si el archivo no existe en la distribucion, avisa y sigue).
- `journalctl -u ssh`: el registro del servicio SSH segun systemd.

Solo **consulta** los logs ya existentes (las ultimas N lineas), no los modifica. Usa `tail -n` y `--no-pager` para que la pantalla no se quede esperando y puedas volver al Menu.

## -- Hardening del Servidor -- 
```text
12) Aplicar Hardening al Servicio SSH (sin clave pública)
```
Esta opcion endurece la configuracion del servicio SSH (`/etc/ssh/sshd_config`). Te pide dos datos:
- Nuevo puerto SSH: numero entre **1024 y 65535** (rango no privilegiado). ej: `22022`
- Usuarios autorizados: los que van a poder conectarse por SSH, separados por espacio. ej: `edsu jMarcos`

Valida que el puerto sea un numero dentro del rango y que haya al menos un usuario. Despues:
- Hace una **copia de seguridad** del archivo original con fecha y hora. ej: `sshd_config.bak.20260518_143015`
- Aplica estas directivas (si ya existian las reemplaza, si no las agrega, asi que se puede ejecutar mas de una vez sin duplicar lineas):
  - `PermitRootLogin no` → deshabilita el login directo de root (apartado A).
  - `Port <puerto>` → cambia el puerto por defecto (22), que es el blanco de los escaneos automaticos (apartado B).
  - `AllowUsers <usuarios>` → solo esos usuarios pueden conectarse (apartado D).
  - `MaxAuthTries 3` y `LoginGraceTime 30` → limita los intentos y el tiempo para autenticarse.
- Valida la sintaxis con `sshd -t` **antes** de aplicar: si esta bien, reinicia el servicio SSH; si hay un error, **restaura automaticamente** la copia de seguridad y no toca el servicio.

La autenticacion por clave publica (apartado C) todavia NO se automatiza (queda pendiente), por eso la opcion dice "sin clave publica" y no se modifica `PasswordAuthentication` ni `PubkeyAuthentication`.

Despues de aplicarlo la conexion pasa a ser: ```ssh usuario@IP -p 22022```

> **⚠️ Advertencia:** Incluí en la lista de usuarios autorizados a la cuenta con la que estas conectado, si no, te podes dejar afuera del servidor. Mantene la sesion actual abierta y proba la nueva conexion desde una **segunda terminal** antes de cerrarla. Si el firewall ya esta activo, habilita antes el nuevo puerto (opcion 13).

```text
13) Configurar Firewall Perimetral (UFW)
```
Esta opcion configura el firewall **UFW** con la politica "denegar por defecto". Te pide el **puerto SSH actualmente habilitado** (el mismo que pusiste en la opcion 12). ej: `22022`

- Verifica que `ufw` este instalado. Si no lo esta, muestra el error con el comando para instalarlo (`apt install ufw -y`).

- Politica por defecto: **deniega todo el trafico entrante** y permite todo el saliente.

- Habilita unicamente los puertos que el sistema necesita:
  - El puerto SSH personalizado (`<puerto>/tcp`).
  - `80/tcp` y `443/tcp`, del servidor web (HTTP/HTTPS).

- Activa el firewall sin pedir confirmacion (`--force`) y muestra el estado final con `ufw status verbose`.

> **⚠️ Advertencia:** Si ingresas un puerto distinto al que realmente esta usando SSH, el firewall te va a cortar el acceso remoto. El puerto de esta opcion tiene que coincidir con el de la opcion 12.

```text
14) Aplicar Permisos al Directorio de la Aplicación
```
Esta opcion NO pide datos. Protege la confidencialidad de la informacion medica del S.I.G.S.M. configurando quien puede acceder al directorio de la aplicacion web (`DIR_APP`, definido en `config.sh`. ej: `/var/www/html/sigsm`).

- Crea el directorio si no existe (junto con los directorios padres que falten).

- Valida que exista el grupo `SIGSM-administradores`. Si no existe, muestra un error: hay que correr antes la inicializacion de grupos (se hace sola al arrancar el script).

- Asigna el propietario y el grupo de forma recursiva (a todo lo que ya hay adentro):
  - **Dueño:** `www-data` (el usuario con el que corre el servidor web).
  - **Grupo:** `SIGSM-administradores` (administradores del servidor con acceso operativo).

- Aplica los permisos `770` de forma recursiva:
  - Dueño: lectura + escritura + ejecucion.
  - Grupo: lectura + escritura + ejecucion.
  - Otros: **sin ningun permiso**.

- Al final muestra el resultado con `ls -ld` para que puedas verificarlo.

> **⚠️ Advertencia:** Cualquier usuario que NO sea `www-data` ni miembro de `SIGSM-administradores` no va a poder entrar a ese directorio.

## -- Salir -- 
```text
15) Salir de la Consola
```
Muestra un mensaje de despedida y cierra la consola de administracion (`exit 0`).

- No deshace ni revierte nada: todos los cambios hechos (usuarios, grupos, SSH, firewall, permisos, respaldos) quedan aplicados en el servidor.

- Si ingresas un numero fuera del rango 1-15 el menu muestra un error y vuelve a pedirte una opcion.

</details>

