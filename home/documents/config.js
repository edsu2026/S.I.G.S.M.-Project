// Obtenemos el input de búsqueda de documentos
const documentToSearchInput = document.getElementById("document-to-search-input");
const documentsResults = document.getElementById("content-documents-results");

// Verificamos que el input exista en la vista actual

if (window.documents != "") {
    window.documents.forEach(doc => {
        const categoriaObj = window.categories.find(cat => cat.id_categoria == doc['id_categoria']);
        const div     = document.createElement('a');
        div.href      = `../../files/documents/${doc['id_documento']}.${doc['ext']}`
        div.className = "d-flex flex-column sigsm-shadow-y-1 justify-content-center document-container document-card";
        div.innerHTML = `
            <div style="width: 370px; height: 135px; overflow: hidden;">
                <img src="../../assets/img/example1.jpg" alt="documento" style="object-fit: cover; background-size: cover; background-position: center">
            </div>
            <div class="col-12 d-flex flex-column mb-3 mx-2">
                <span><h5 class="document-category rounded p-1" style="margin: 0;">${categoriaObj['nombre_categoria']}</h5></span>
                <span><p class="document-title text-break" style="margin: 0;">${doc['nombre_documento']}</p></span>
            </div>`;
        documentsResults.appendChild(div);
    });
} else {
    const div     = document.createElement('div');
    div.className = "d-flex flex-column justify-content-center";
    div.innerHTML = `
        <div class="p-1 rounded">
            <h5>Sin documentos</h5>
        </div>`;
    documentsResults.appendChild(div);
}

if (documentToSearchInput && documentsResults) {
    documentToSearchInput.addEventListener('input', (event) => {
        documentsResults.innerHTML = '';

        let result = [];
        const searchText = documentToSearchInput.value;

        // Validamos si se ingresó texto
        if (searchText) {
            // Creamos un patrón flexible e insensible a mayúsculas/minúsculas ('i')
            const textPattern = new RegExp(searchText, 'i');

            // Recorremos la lista global de documentos
            window.documents.forEach(doc => {
                // Opcional: Buscar también el nombre de la categoría relacionada si lo necesitas
                const categoriaObj = window.categories.find(cat => cat.id_categoria == doc['id_categoria']);
                const nombreCategoria = categoriaObj ? categoriaObj['nombre_categoria'] : '';

                // Comprobamos si coincide con el nombre del documento o con su categoría
                if (textPattern.test(doc['nombre_documento']) || textPattern.test(nombreCategoria)) {
                    result.push(doc);
                }
            });
        }

        // Si hay documentos que cumplan con los parámetros
        if (result.length > 0) {
            result.forEach(doc => {
                const categoriaObj = window.categories.find(cat => cat.id_categoria == doc['id_categoria']);
                const div     = document.createElement('a');
                div.href      = `../../files/documents/${doc['id_documento']}.${doc['ext']}`;
                div.className = "d-flex flex-column sigsm-shadow-y-1 justify-content-center document-container document-card";
                div.innerHTML = `
                    <div style="width: 370px; height: 135px; overflow: hidden;">
                        <img src="../../assets/img/example1.jpg" alt="documento" style="object-fit: cover; background-size: cover; background-position: center">
                    </div>
                    <div class="col-12 d-flex flex-column mb-3 mx-2">
                        <span><h5 class="document-category rounded p-1" style="margin: 0;">${categoriaObj['nombre_categoria']}</h5></span>
                        <span><p class="document-title text-break" style="margin: 0;">${doc['nombre_documento']}</p></span>
                    </div>`;
                documentsResults.appendChild(div);
            });
        } else if (searchText == "") {
            window.documents.forEach(doc => {
                const categoriaObj = window.categories.find(cat => cat.id_categoria == doc['id_categoria']);
                const div     = document.createElement('a');
                div.href      = `../../files/documents/${doc['id_documento']}.${doc['ext']}`;
                div.className = "d-flex flex-column sigsm-shadow-y-1 justify-content-center document-container document-card";
                div.innerHTML = `
                    <div style="width: 370px; height: 135px; overflow: hidden;">
                        <img src="../../assets/img/example1.jpg" alt="documento" style="object-fit: cover; background-size: cover; background-position: center">
                    </div>
                    <div class="col-12 d-flex flex-column mb-3 mx-2">
                        <span><h5 class="document-category rounded p-1" style="margin: 0;">${categoriaObj['nombre_categoria']}</h5></span>
                        <span><p class="document-title text-break" style="margin: 0;">${doc['nombre_documento']}</p></span>
                    </div>`;
                documentsResults.appendChild(div);
            });
        } else {
            const div     = document.createElement('div');
            div.className = "d-flex flex-column sigsm-shadow-y-1 justify-content-center document-container document-card";
            div.innerHTML = `
                <div class="p-1">
                    <h5>Sin documentos</h5>
                </div>`;
            documentsResults.appendChild(div);
        }
    });
}