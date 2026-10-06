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
- [</> Uso de la Consola de Operador (script)](#-uso-de-la-consola-de-operador)

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
- Propietario: `www-data` | Grupo: `SIGSM-administrativos`.
- Impide el acceso no autorizado desde la consola local a datos médicos sensibles.
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

**Gestion Administrativa** Esto ya depende mas del script y del Portal de administradores/administrativos que es parte para cargar documentos, actualizar, dar permisos, quitar permisos, agregar usuarios/grupos a roles.etc

---  

## </> Uso de la Consola de Operador (script)

La gestion se realiza mediante el script interactivo que desarrollamos:
`scriptEDSU_V(Beta).sh`.

```bash 
# Otorgar permisos de ejecución
chmod +x scriptEDSU_V(Beta).sh
```
<!-- El "bash" se podria cambiar tmb por cualquier lenguaje que agarre markdown, que puede ser css, python, javascript, java, html.etc (No se si agarra todos, pero agarra bastantes) -->

```bash 
# Abrirlo (SIEMPRE CON ROOT, ya que controlas cosas criticas con el script)
sudo ./scriptEDSU_V(Beta).sh
```

### Banner de la Empresa en Consola
El script incluye tambien un banner/logo en arte ASCII de **EDSU** solamente utilizando nuestro logo que es una **E** con colores cian.

```text
=============================================================
   S.I.G.S.M. - CONSOLA DE ADMINISTRACIÓN DEL SERVIDOR
         (Ducasse, Galli, Salas, Correa - EDSU)
=============================================================
 --- Usuarios y Grupos --
 1) Crear un Nuevo Usuario (Asociación automática a Grupo)
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

