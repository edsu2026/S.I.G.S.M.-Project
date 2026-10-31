<?php
    $repository = "../../..";
    $phpSource = $repository . "/php";

    $docsLocation = $repository . "/files/documents/";
    $extsAllowed = array('jpg', 'pdf');

    function toPanel($msg) {
        header("Location: " . $repository . "/dashboard/panel/page.php");
    }

    require $phpSource . "/conexion.php";

    try {
        // Aseguramos que solo se procese si la petición es POST y si vienen los campos obligatorios
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $errores = [];    

            /** Se validará qué acción está haciendo el usuario al sistema querer requerir del módulo
             *  El parámetro "accion" en $_POST es pasado por botones al querer gestionar documentos
             *  (crear, actualizar, activar/desactivar, eliminar).
            */
            switch ($_POST['accion']) {
                case 'create':
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

                    if (!in_array(strtolower($ext), $extsAllowed)) {
                        die("Error: Formato de archivo no permitido (solo JPG y PDF).");
                    }

                    $consulta = "INSERT INTO documento (id_categoria, nombre_documento) VALUES (:id_categoria, :nombre_documento)";
                    $stmt = $conexion->prepare($consulta);
                    $stmt->bindParam(":nombre_documento", $fileName, PDO::PARAM_STR);
                    $stmt->bindParam(":id_categoria", $categoryId, PDO::PARAM_INT);
                    $stmt->execute();

                    $idRecienCreado = $conexion->lastInsertId();

                    $final = $docsLocation . $idRecienCreado . "." . $ext;

                    if (!move_uploaded_file($rutaTemporal, $final)) {
                        echo "No se pudo subir el archivo físico al servidor.";
                    } else {
                        echo "Documento guardado con éxito. ID: ";
                    }

                    break;
                
                case 'update':
                    //  VALIDAMOS
                    if (!isset($_FILES['document-file-input']) || !isset($_POST['document-name-input'])) {
                        exit('Campos vacíos o incompletos');
                        toPanel();
                    }

                    /// Obtención de los datos pasados por el form
                    //  Datos de los inputs (escenciales para la Base de Datos)
                    $document_id    =   $_POST['document-id-input'];
                    $document_name  =   $_POST['document-name-input'];

                    //  Obtención del archivo subido por el usuario (el nuevo que reemplazará al antiguo)
                    $document_file  =   $_FILES['document-file-input'] ?? '';
                    $document_tmp   =   $document_file['tmp_name'] ?? '';
                    $document_ext   =   pathinfo($document_file['name'], PATHINFO_EXTENSION);
                    $document_des   =   $docsLocation . $document_id . $document_ext;

                    //  Validación de la extensión del archivo
                    if (!in_array(strtolower($document_ext), $extsAllowed)) {
                        toPanel('El formato del archivo no está habilitado');
                    }

                    //  Consulta
                    $sql            =   $conexion->prepare('UPDATE documento SET nombre_documento = :docname WHERE id_documento = :id');
                    $sql->bindParam(':id', $document_id, PDO::PARAM_INT);
                    $sql->bindParam(':docname', $document_name, PDO::PARAM_STR);
                    $sql->execute();

                    //  Validamos cambios
                    $returned       =   $sql->rowCount();
                    if ($returned < 1) {
                        toPanel('No se editó ninguna tabla. El nombre ya está editado');
                    }

                    // Subida del documento
                    if (!move_uploaded_file($document_tmp, $document_des)) {
                        toPanel('No se logró almacenar el archivo en el servidor');
                    }
                    break;
                case 'deactivate':
                    //  Obtenemos información del documento
                    $document_id    =  $_POST['document-id-input'];
                    
                    //  Consulta
                    $sql            =  $conexion->prepare("UPDATE documento SET activo = 0 WHERE id_documento = :id");
                    $sql->bindParam(":id", $document_id, PDO::PARAM_INT);
                    $sql->execute();

                    //  Validamos cambios
                    if ($sql->rowCount() < 1) {
                        toPanel('No hubieron cambios');
                    }
                    break;
                case 'activate':
                    //  Obtenemos información del documento
                    $document_id = $_POST['document-id-input'];

                    //  Consulta
                    $sql            =   $conexion->prepare("UPDATE documento SET activo = 1 WHERE id_documento = :id");
                    $sql->bindParam(":id", $document_id, PDO::PARAM_INT);
                    $sql->execute();

                    //  Validamos cambios
                    if ($sql->rowCount() < 1) {
                        toPanel('No hubieron cambios');
                    }
                    break;
                case 'delete':
                    //  Obtenemos información del documento
                    $document_id    =   $_POST['document-id-input'];

                    //  Consulta
                    $sql            =   $conexion->prepare("DELETE FROM documento WHERE id_documento = :id");
                    $sql->bindParam(":id", $document_id, PDO::PARAM_INT);
                    $sql->execute();

                    //  glob otiene cualquier archivo que cumpla con determinado patrón que se pasa como parámetro.
                    //  El parametro que se le pasa es:
                    /**
                     *  1)  Que exista dentro de la carpeta "files/documents"
                     *  2)  Que tenga de nombre el id del documento
                     *  3)  Que tenga cualquier extensión (la extensión del archivo no se guarda en la Base de Datos, pero el nombre del documento no se puede repetir);
                     */
                    $document_file  =   glob($docsLocation . $document_id . ".*");

                    //  Eliminamos el archivo de la carpeta
                    if (!unlink($document_file[0])) {
                        toPanel('El archivo perteneciente al documento ' . $document_id . ' no se pudo eliminar');
                    }
                    break;
                default:
                    # code...
                    break;
            }
        } else {
            // Si alguien entra directamente a la URL sin enviar el formulario
            toPanel("Acceso no autorizado. Por favor, envía el formulario desde la página principal.");
        }
    } catch (\Throwable $th) {
        throw $th;
    }

    toPanel('Reenviando al panel');
?>