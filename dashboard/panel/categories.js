const categoryUnselected = document.getElementById("content-categories-category-unselected");
const categoryContent = document.getElementById("content-categories-category-content");
var categorySelected = false;

const categorySelectedTitle = document.getElementById("content-category-selected-name");

// Localizamos el contenedor del botón de creación de documentos
const btnDocumentCreateContainer = document.getElementById("content-category-selected-btn-document-create");

// Localizamos el contenedor del token
const tokenContainer = document.getElementById("content-category-selected-token");

const id_category_input = document.getElementById("category-rename-id-input");

/** Tabla en donde se pondrán los documentos */ 
const documentsTableBody = document.getElementById("content-category-selected-documents-tablebody");

/** Objetos para enseñar las reseñas */ 
const categorySelectedReviews       = document.getElementById("content-category-selected-reviews");
const categorySelectedPointsCount   = document.getElementById("content-category-selected-points-count");
const categorySelectedPointsAverage = document.getElementById("content-category-selected-points-average");

//  Barras de las reseñas
const reviewAtentionProgressBar     = document.getElementById("review-atention-progressbar");
const reviewTimeProgressBar         = document.getElementById("review-time-progressbar");
const reviewConditionsProgressBar   = document.getElementById("review-conditions-progressbar");

document.addEventListener('DOMContentLoaded', () => {

    // Tomamos las categorías inyectadas por PHP en la ventana global
    const categories = window.categories;
    // alert(categories);
    const reviews    = window.reviews;
    const documents  = window.documents;

    //  Contenedor donde se encuentran las categorías a seleccionar en el panel
    const grpButtons = document.getElementById('content-categories-category-selector');

    // Si hay categorias, se insertan los botones
    if (categories != "") {
 
        // Le quitamos la clase d-none al contenedor de botones de categorías para que se muestre
        grpButtons.classList.remove('d-none');

        // Iteramos sobre cada categoría y creamos un botón para cada una
        categories.forEach(category => {

            const button = document.createElement('button');
            button.innerText = category['nombre_categoria'];
            button.className = "col-10 panel-unique-button"; 
            grpButtons.appendChild(button);

            // Al hacer click en una categoría lateral
            button.addEventListener('click', () => {
                console.log('clickeado');

                // Almacenamos el título visual
                categorySelectedTitle.textContent = category['nombre_categoria'];
                
                // Creamos el boton con el ID de la categoría por GET
                btnDocumentCreateContainer.innerHTML = `
                    <a href="../manage/document/page.php?accion=create&id_categoria=${category['id_categoria']}" class="button-panel button-theme-lightblue text-decoration-none text-center px-4 py-2 rounded">
                        Crear documento
                    </a>
                `;

                // Almacenamos el ID de la categoría en el input oculto para renombrar la categoría
                id_category_input.value = category['id_categoria'];

                // Limpiamos la tabla de documentos de la categoría elegida
                documentsTableBody.innerHTML = '';

                // Renderizamos el token de la categoría elegida
                tokenContainer.textContent = category['token_categoria'];

                // Renderizamos los documentos de la categoría elegida
                documents.forEach(doc => {

                    // Comprobamos si el documento pertenece a la categoría seleccionada
                    if (doc['id_categoria'] == category['id_categoria']) {

                        //  Creamos una fila que se agregará a la tabla documentsTableBody
                        const fila = document.createElement('tr');

                        //  Construímos la fila
                        fila.innerHTML = `
                            <td>${doc['id_documento']}</td>
                            <td>${doc['nombre_documento']}</td>
                            <td>${doc['fecha_creacion']}</td>
                            <td><span class="badge ${doc['activo'] === 1 ? 'bg-success' : 'bg-danger'}">${doc['activo'] === 1 ? 'Activo' : 'Inactivo'}</span></td>
                            <td><a href="../manage/document/page.php?accion=update&id_documento=${doc['id_documento']}" class="col-12 button-panel button-theme-lightblue rounded">Editar</button></td>
                        `;

                        //  Agregamos la fila
                        documentsTableBody.appendChild(fila);
                    } else {

                        // Si no hay documentos en la categoría, mostramos un mensaje indicando que no hay documentos
                        documentsTableBody.innerHTML = `
                        <tr>
                            <td class="text-muted text-center col-12" colspan="4">Sin documentos en esta categoría</td>
                        </tr>
                        `;
                    }
                });

                //  Renderizamos las reseñas de la categoría elegida
                //  Calculamos el total de puntos y el número de reseñas para la categoría seleccionada
                let averagePoints      = 0;
                let averageAtencion    = 0;
                let averageTiempo      = 0;
                let averageCondiciones = 0;

                //  Total de reseñas
                let totalReviews = 0;
                reviews.forEach(review => {

                    //  Si la columna id_categoria de la reseña es igual al id de la categoría actual, significa que tal reseña pertenece a la categoría
                    if (review['id_categoria'] == category['id_categoria']) {

                        //  Sumamos
                        totalReviews++;

                        //  Sumamos los valores de la reseña a las variables pertenecientes
                        averageAtencion    += parseInt(review['atención']);
                        averageTiempo      += parseInt(review['tiempo']);
                        averageCondiciones += parseInt(review['condiciones']);
                    }
                });

                //  Calculamos el promedio de puntos para la categoría seleccionada
                if (totalReviews > 0) {

                    //  Calculamos los promedios individuales de cada criterio
                    //  Reutilizamos variables
                    averageAtencion    = averageAtencion    / totalReviews;
                    averageTiempo      = averageTiempo      / totalReviews;
                    averageCondiciones = averageCondiciones / totalReviews;

                    //  Mostramos los promedios individuales en la consola para depuración
                    console.log({ averageAtencion, averageTiempo, averageCondiciones });

                    //  Calculamos el promedio total de puntos para la categoría seleccionada
                    averagePoints = parseFloat(averageAtencion + averageTiempo + averageCondiciones) / 3;

                    //  Mostramos los resultados en el HTML
                    categorySelectedPointsCount.textContent = totalReviews;
                    categorySelectedPointsAverage.textContent = averagePoints.toFixed(2);
                    
                    //  El sistema validará que el contenedor tenga d-none para removerlo
                    categorySelectedReviews.classList.forEach(HTML_CLASS => {

                        //  Si la comparación devuelve "true", significa que existe la clase d-none
                        if (HTML_CLASS == 'd-none') categorySelectedReviews.classList.remove('d-none');
                    });  

                    //  Al width de las barras de progreso les asignamos el resultado del pasaje a porcentaje de los promedios del 0% a 100%
                    reviewAtentionProgressBar.style.width   = `${(averageAtencion / 5) * 100}%`;
                    reviewTimeProgressBar.style.width       = `${(averageTiempo / 5) * 100}%`;
                    reviewConditionsProgressBar.style.width = `${(averageCondiciones / 5) * 100}%`;
                } else {

                    /** 
                     *  Si la categoría seleccionada no cuenta con reseñas y se puede
                     *  ver el contenedor de las reseñas, entonces el sistema no debería
                     *  de enseñar el contenedor
                    */

                    //  Booleano que confirma que el contenedor tenga o no d-none
                    let HAS_DNONE = false;
                    categorySelectedReviews.classList.forEach(HTML_CLASS => {
                        if (HTML_CLASS == 'd-none') HAS_DNONE == true;
                    });  

                    //  Si no tiene d-none (cuando lo deberia de tener)
                    //  Si ya tenía d-none, significa que la categoría anterior tampoco tenía reseñas 
                    if (!HAS_DNONE) {

                        //  Le agregamos d-none
                        categorySelectedReviews.classList.add('d-none');
                    }
                }

                /**
                 * Por defecto, el sistema no muestra el contenido de ninguna categoría hasta que el usuario seleccione
                 * alguna opción. Si es la primera vez que el usuario selecciona una categoría, el mensaje que le indica al
                 * usuario que seleccione una categoría, no será visible a menos que la página se recargue.
                 */

                if (categorySelected == false) {

                    //  Ocultamos el mensaje "seleccione una categoría o cree una"
                    categoryUnselected.classList.add('d-none');

                    //  Hacemos aparecer el contenido de la categoría
                    categoryContent.classList.remove('d-none');
                    categoryContent.classList.add('d-block');

                    categorySelected = true;
                }
            });
        });
    } else {
        //  Removemos el col-md-9 y le agregamos col-md-12 para que ocupe todo el espacio el mensaje
        categoryUnselected.classList.remove("col-md-9");
        categoryUnselected.classList.add("col-md-12");
    }
});