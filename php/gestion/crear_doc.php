<?php
require "../conexion.php";

try {
    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_FILES['inputFile'])) {
        
        if (!isset($_POST['inputCatId']) || empty(trim($_POST['inputCatId']))) {
            die("Error: El ID de la categoría no puede estar vacío.");
        }
        
        $nombre_documento = (!isset($_POST['inputNombre']) || empty(trim($_POST['inputNombre']))) 
            ? "Documento sin título" 
            : trim($_POST['inputNombre']);

        $id_categoria = $_POST['inputCatId'];

        $sql = "INSERT INTO documento (id_categoria, nombre_documento) VALUES (:id_categoria, :nombre_documento)";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id_categoria', $id_categoria, PDO::PARAM_INT);
        $stmt->bindParam(':nombre_documento', $nombre_documento, PDO::PARAM_STR);
        $stmt->execute();
        
        $id_documento_creado = $conexion->lastInsertId();

        $nombre_original = basename($_FILES['inputFile']['name']);
        $extension = pathinfo($nombre_original, PATHINFO_EXTENSION);
        
        $nuevo_nombre = $id_documento_creado . "." . $extension;

        $folder = "../../uploads/documentos/";
        $destino = $folder . $nuevo_nombre;
        $archivo_temporal = $_FILES['inputFile']['tmp_name'];
    
        if (move_uploaded_file($archivo_temporal, $destino)) {
            echo "El archivo se ha guardado correctamente en el servidor como: " . $nuevo_nombre . " y se registró en la base de datos con el ID: " . $id_documento_creado;
        } else {
            echo "Error: No se pudo mover el archivo físico al directorio de destino. Verifica los permisos de la carpeta.";
        }
        header('Location: ../../panel/adminpanel.php');
    } else {
        echo "Error: No se envió el formulario de forma correcta o falta el archivo.";
    }
} catch (Exception $th) {
    echo "Ocurrió un error en el servidor: " . $th->getMessage();
}
?>