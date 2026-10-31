const categoryUnselected = document.getElementById("content-categories-category-unselected");
const categoryContent = document.getElementById("content-categories-category-content");
var categorySelected = false;

const categorySelectedTitle = document.getElementById("content-category-selected-name");

// Localizamos el contenedor del botón de creación de documentos
const btnDocumentCreateContainer = document.getElementById("content-category-selected-btn-document-create");
const id_category_input = document.getElementById("category-rename-id-input");
const documentsTableBody = document.getElementById("content-category-selected-documents-tablebody");

document.addEventListener('DOMContentLoaded', () => {

    // Tomamos las categorías inyectadas por PHP en la ventana global
    const categories = window.categories;
    const grpButtons = document.getElementById('content-categories-category-selector');

    if (!categories) return;

    categories.forEach(category => {
        const button = document.createElement('button');
        button.innerText = category['nombre_categoria'];
        button.className = "btn btn-outline-primary m-1 w-100"; 
        grpButtons.appendChild(button);

        // Al hacer click en una categoría lateral
        button.addEventListener('click', () => {
            console.log('clickeado');

            // 1. Almacenamos el título visual
            categorySelectedTitle.textContent = category['nombre_categoria'];
            
            // 2. Redirección parametrizada con el ID de la categoría por GET
            btnDocumentCreateContainer.innerHTML = `
                <a href="../manage/document/page.php?accion=create&id_categoria=${category['id_categoria']}" class="button-panel button-theme-lightblue text-decoration-none text-center px-4 py-2 rounded">
                    Crear documento
                </a>
            `;

            id_category_input.value = category['id_categoria'];

            // 3. Limpiamos y renderizamos la tabla de documentos de la categoría elegida
            documentsTableBody.innerHTML = '';

            if (category.documentos) {
                const arrayDocumentos = category.documentos.split('||');
                
                arrayDocumentos.forEach(doc => {
                    // Separamos usando el delimitador seguro ';;'
                    const datosDocumento = doc.split(';;');
                    
                    const idDoc     = datosDocumento[0];
                    const nomDoc    = datosDocumento[1];
                    const fechaDoc  = datosDocumento[2];
                    const activoDoc = datosDocumento[3] === '1' ? 'Activo' : 'Inactivo'; // Evaluamos el booleano numérico de MySQL

                    // Creamos la fila mapeando cada dato a las columnas (ID, Nombre, Fecha, Estado)
                    const fila = document.createElement('tr');
                    fila.innerHTML = `
                        <td>${idDoc}</td>
                        <td>${nomDoc}</td>
                        <td>${fechaDoc}</td>
                        <td><span class="badge ${datosDocumento[3] === '1' ? 'bg-success' : 'bg-danger'}">${activoDoc}</span></td>
                        <td><a href="../manage/document/page.php?accion=update&id_documento=${idDoc}" class="col-12 button-panel button-theme-lightblue rounded">Editar</button></td>
                    `;
                    documentsTableBody.appendChild(fila);
                });
            } else {
                documentsTableBody.innerHTML = `
                    <tr>
                        <td class="text-muted text-center col-12" colspan="4">Sin documentos en esta categoría</td>
                    </tr>
                `;
            }

            /**
             * Por defecto, el sistema no muestra el contenido de ninguna categoría hasta que el usuario seleccione
             * alguna opción. Si es la primera vez que el usuario selecciona una categoría, el mensaje que le indica al
             * usuario que seleccione una categoría, no será visible a menos que la página se recargue.
             */
            if (categorySelected == false) {
                categoryUnselected.classList.add('d-none');

                categoryContent.classList.remove('d-none');
                categoryContent.classList.add('d-block');
                categorySelected = true;
            }
        });
    });
});