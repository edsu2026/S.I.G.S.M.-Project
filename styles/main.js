/**
 * @file main.js
 * @brief FRONTEND: cuando el usuario se mueve en la página en el eje vertical, se agregará una clase al contenedor "header-main"
 * @author E.D.S.U. Ian Franco Galli Santos
*/
const header_main = document.getElementById('header-main');

window.addEventListener('scroll', () => {
    /**
     * @brief Si la posición del eje Y del dispositivo dentro de la página es 30, se agrega la clase, sino se remueve.
     */
    if (window.scrollY > 30) {
        /**
         * Agregamos la clase
         */
        header_main.classList.add('on-scrolled-y');
    } else {
        /**
         * Removemos la clase
         */
        header_main.classList.remove('on-scrolled-y');
    }
});