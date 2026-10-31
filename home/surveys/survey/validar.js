/**
 * @file    survey/validar.js
 * @author  E.D.S.U. Ian Franco Galli Santos
 * @brief   Acá se valida que el usuario haya ingresado valores, evitando que una pregunta no se responda
 */

/**
 * Obtenemos el formulario de la página
 */
const formulary          = document.getElementById('content-questions');

/**
 * @function    cancel
 * @brief       La función cancela el envío del formulario, y opcionalmente lanza una alerta de error si se pasa como parametro
 * @param {*}   event   Objeto que contiene información sobre lo sucedido
 * @param {*}   message Mensaje opcional 
 */
function cancel(event, message) {
   /**
   * Prevenimos el envío
   */
    if (message) alert(message);
    event.preventDefault();
}

formulary.addEventListener('submit', (event) => {
    /* El siguiente algoritmo se ejecutará al querer subir la reseña */

    /* Obtenemos el valor que el usuario ingresó (dentro del rango 1-5) */
    const radioQuestion1 = document.querySelector("input[name='question-1-input']:checked");

    /* Si el usuario no respondió la pregunta 1, se cancelará el envío */
    if (!radioQuestion1) {
        cancel(event, "Responda la pregunta 1");

        /* Salimos para que el programa no siga */
        return;
    }

    /* Obtenemos el valor que el usuario ingresó (dentro del rango 1-5) */
    const radioQuestion2 = document.querySelector("input[name='question-1-input']:checked");

    /* Si el usuario no respondió la pregunta 1, se cancelará el envío */
    if (!radioQuestion2) {
        /**
         * Prevenimos el envío
         */
        cancel(event, "Responda la pregunta 2");

        /* Salimos para que el programa no siga */
        return;
    }

    /* Si el usuario no respondió la pregunta 1, se cancelará el envío */
    const radioQuestion3 = document.querySelector("input[name='question-1-input']:checked");

    /* Si el usuario no respondió la pregunta 1, se cancelará el envío */
    if (!radioQuestion3) {
        /**
         * Prevenimos el envío
         */
        cancel(event, "Responda la pregunta 3");

        /* Salimos para que el programa no siga */
        return;
    }

    // Testing: probamos la funcion cancel() y únicamente le pasamos el parámetro "event" para validar que se cancele el formulario sin lanzar error
    // cancel(event);
})