function validarInicioSolicitud() {
    const patronEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; 
    const patronPassword = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;

    let inputEmail = document.getElementById("inputEmail");
    let inputPassword = document.getElementById("inputPassword");
    let alertDiv = document.getElementById("alertDiv");

    // Corrección: Usar .value para leer los inputs
    let email = inputEmail.value;
    let password = inputPassword.value;

    if (!patronEmail.test(email)) {
        alertDiv.textContent = "Email inadecuado. Su email debe de tener el siguiente formato: ejemplo@dominio.com";
        alertDiv.classList.remove("d-none");
        return; // Detiene la función si el email no es válido
    }

    if (!patronPassword.test(password)) {
        alertDiv.textContent = "Contraseña inadecuada. Debe contener al menos 8 caracteres, una mayúscula, una minúscula y un número";
        alertDiv.classList.remove("d-none");
        return; // Detiene la función si la contraseña no es válida
    }

    alert("Datos validos");
}

function crearCategoria() {
    let inputNombreCat = document.getElementById("inputNombreCat").value.trim();
    const patronNombre = /^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑüÜ\s\-_.,?!()]{1,64}$/;

    if (patronNombre.test(inputNombreCat) === false) {
        alert("Por favor, ingresa un nombre para la categoría");
        return;
    }

    alert("¡Datos válidos! Procesando categoría...");
}

function eliminarCategoria() {
    let inputIdCatDel = document.getElementById("inputIdCatDel").value.trim();
    const patronID = /^[1-9]\d*$/;

    if (!patronID.test(inputIdCatDel)) {
        alert("Por favor, ingresa un ID de categoría válido");
        return;
    }

    alert("¡ID de categoría válido! Procesando eliminación...");
}

function crearDocumento() {
    let inputCatId = document.getElementById("inputCatId").value.trim();
    let inputNombreDoc = document.getElementById("inputNombreDoc").value.trim();
    //let inputFile = document.getElementById("inputFile").value.trim();

    const patronNombre = /^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑüÜ\s\-_.,?!()]{1,64}$/;
    const patronID = /^[1-9]\d*$/;

    if (!patronID.test(inputCatId)) {
        alert("Por favor, ingresa una categoría válida");
        return;
    }

    if (patronNombre.test(inputNombreDoc) === false) {
        alert("Por favor, ingresa un nombre para el documento");
        return;
    }

    alert("¡Datos válidos! Procesando documento...");
}

function actualizarDocumento() {
    let inputIdDoc = document.getElementById("inputIdDocAct").value.trim();
    //let inputFile = document.getElementById("inputFileAct").value.trim();

    const patronNombre = /^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑüÜ\s\-_.,?!()]{1,64}$/;
    const patronID = /^[1-9]\d*$/;

    if (!patronID.test(inputIdDoc)) {
        alert("Por favor, ingresa un ID de documento válido");
        return;
    }

    alert("¡Datos válidos! Procesando actualización de documento...");
}

function eliminarDocumento() {
    let inputIdDoc = document.getElementById("inputIdDocDel").value.trim();

    const patronID = /^[1-9]\d*$/;

    if (!patronID.test(inputIdDoc)) {
        alert("Por favor, ingresa un ID de documento válido");
        return;
    }

    alert("¡ID de documento válido! Procesando eliminación...");
}

function actualizarEncuesta() {
    let inputIdEnc = document.getElementById("inputIdEncAct").value.trim();
    let inputURL = document.getElementById("inputLinkAct").value.trim();

    const patronID = /^[1-9]\d*$/;

    if (!patronID.test(inputIdEnc)) {
        alert("Por favor, ingresa un ID de encuesta válido");
        return;
    }

    try {
        const url = new URL(inputURL);
        
        if (url.protocol !== "http:" && url.protocol !== "https:") {
            alert("La URL debe comenzar con http:// o https://");
            return;
        }
    } catch (error) {
        alert("Formato de URL inválido");
        return;
    }

    alert("¡URL válida! Procesando actualización de encuesta...");
}

function eliminarEncuesta() {
    let inputIdEncDel = document.getElementById("inputIdEncDel").value.trim();

    const patronID = /^[1-9]\d*$/;

    if (!patronID.test(inputIdEncDel)) {
        alert("Por favor, ingresa un ID de encuesta válido");
        return;
    }

    alert("¡ID de encuesta válido! Procesando eliminación...");
}

function crearEncuesta() {
    let texto = document.getElementById("inputURL").value.trim();
    let inputNombreEnc = document.getElementById("inputNombreEnc").value.trim();
    let inputIdCat = document.getElementById("inputIdCat").value.trim();

    const patronNombre = /^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑüÜ\s\-_.,?!()]{1,64}$/;
    const patronID = /^[1-9]\d*$/;

    if (!patronID.test(inputIdCat)) {
        alert("Por favor, ingresa una categoría válida");
        return;
    }

    if (patronNombre.test(inputNombreEnc) === false) {
        alert("Por favor, ingresa un nombre para la encuesta");
        return;
    }

    try {
        const url = new URL(texto);
        
        if (url.protocol !== "http:" && url.protocol !== "https:") {
            alert("La URL debe comenzar con http:// o https://");
            return;
        }
    } catch (error) {
        alert("Formato de URL inválido");
        return;
    }

    alert("¡URL válida! Procesando encuesta...");
}