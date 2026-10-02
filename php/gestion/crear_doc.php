<?php
require "../../php/conexion.php";
$uploads = "../../uploads/documentos/"; // Carpeta donde se guardarán los documentos subidos

try {
    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_FILES['inputFile'])) {
        if (empty(trim($_POST['inputCatId']))) {
            die("Error: El ID de la categoría no puede estar vacío.");
        }

        $extensionArchivo = pathinfo($_FILES['inputFile']['name'], PATHINFO_EXTENSION);
        if ($extensionArchivo !== 'pdf') {
            die("Error: Solo se permiten archivos PDF.");
        }
        
        //Inicialización de la consulta SQL
        $nombre_documento = (isset($_POST['inputNombre']) ? trim($_POST['inputNombre']) : 'Documento sin nombre');
        $id_categoria = $_POST['inputCatId'];

        $sql = "INSERT INTO documento (id_categoria, nombre_documento) VALUES (:id_categoria, :nombre_documento)";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id_categoria', $id_categoria, PDO::PARAM_INT);
        $stmt->bindParam(':nombre_documento', $nombre_documento, PDO::PARAM_STR);
        $stmt->execute();

        //Guardar el documento
        $idNuevoDocumento = $conexion->lastInsertId();
        $nombreArchivo = $idNuevoDocumento . '.pdf';
        $rutaArchivo = $uploads . $nombreArchivo;

        $archivoSubido = move_uploaded_file($_FILES['inputFile']['tmp_name'], $rutaArchivo);
        if (!$archivoSubido) {
            die("Error: No se pudo subir el archivo.");
        }

        header('Location: ../../dashboard/adminpanel.php');
    } else {
        echo "Error: No se envió el formulario de forma correcta o falta el archivo.";
    }
} catch (Exception $th) {
    echo "Ocurrió un error en el servidor: " . $th->getMessage();
}
?>