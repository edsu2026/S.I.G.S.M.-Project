<!-- Si vas a tocar codigo de aca, recorda que esto es LENGUAJE MARKDOWN, antes de TOCAR ALGO busca como es la sintaxis de Markdown
    Es facil igual, pero de todas forma TENE CUIDADO, no vayas a romper algo (Esto es un comentario, no se ve en el codigo) -->

<!-- Aca empieza el Codigo (Despues de este comentario y el de abajo) -->

<!-- Centro los textos tipo titulo -->
<div align="center">
    
# S.I.G.S.M.
### Sistema de Información de Gestión de Servicios Médicos

</div>

<!-- Este es para centrar los cosos estos animados -->
<div align="center">
    
[![Estado](https://img.shields.io/badge/Estado-Version%20Final-brightgreen?style=for-the-badge&logo=github)](https://github.com/)
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
![Bash](https://img.shields.io/badge/GNU_Bash-4EAA25?style=for-the-badge&logo=gnu-bash&logoColor=white)
![XAMPP](https://img.shields.io/badge/XAMPP-FB7A24?style=for-the-badge&logo=xampp&logoColor=white)
![OpenSSL](https://img.shields.io/badge/OpenSSL_AES--256-721412?style=for-the-badge&logo=openssl&logoColor=white)
![Git](https://img.shields.io/badge/Git-F05032?style=for-the-badge&logo=git&logoColor=white)

---

## 📋 Tabla de Contenidos:
- [💡 Acerca del Proyecto](#-acerca-del-proyecto)
- [👥 Equipo de Desarrollo (EDSU)](#-equipo-de-desarrollo-edsu)
- [🚀 Instalacion y Despliegue](#-instalacion-y-despliegue)

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
