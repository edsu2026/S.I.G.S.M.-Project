<?php
    $repository = "../../..";
    $phpSource = $repository . "/php";

    require $phpSource . "/conexion.php";

    try {
        // Aseguramos que solo se procese si la petición es POST y si vienen los campos obligatorios
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['category_id'], $_POST['document-name-input'])) {
            
            $categoryId = $_POST['category_id'];
            $fileName = $_POST["document-name-input"];
            
            // Verificamos que el archivo realmente se haya subido en el formulario
            if (!isset($_FILES["document-file-input"]) || $_FILES["document-file-input"]['error'] !== UPLOAD_ERR_OK) {
                die("Error: El archivo no se subió correctamente o no fue seleccionado.");
            }

            $file = $_FILES["document-file-input"];
            $rutaTemporal = $file['tmp_name'];

            // CORRECCIÓN: PATHINFO_EXTENSION con el guion bajo correcto
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION); 
            $allowed = array('jpg', 'pdf');

            if (!in_array(strtolower($ext), $allowed)) {
                die("Error: Formato de archivo no permitido (solo JPG y PDF).");
            }

            $consulta = "INSERT INTO documento (nombre_documento, id_categoria) VALUES (:nombre_documento, :id_categoria)";
            $stmt = $conexion->prepare($consulta);
            $stmt->bindParam(":nombre_documento", $fileName, PDO::PARAM_STR);
            $stmt->bindParam(":id_categoria", $categoryId, PDO::PARAM_INT);
            $stmt->execute();

            $idRecienCreado = $conexion->lastInsertId();

            $location = $repository . "/files/documents/";
            $final = $location . $idRecienCreado . "." . $ext;

            if (!move_uploaded_file($rutaTemporal, $final)) {
                echo "No se pudo subir el archivo físico al servidor.";
            } else {
                echo "Documento guardado con éxito. ID: ";
            }
        } else {
            // Si alguien entra directamente a la URL sin enviar el formulario
            echo "Acceso no autorizado. Por favor, envía el formulario desde la página principal.";
        }
    } catch (\Throwable $th) {
        throw $th;
    }

    header("Location: " . $repository . "/dashboard/panel/page.php");
?>