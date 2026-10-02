<?php
    try {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            require "../conexion.php";
            $action = $_POST['action'];
            if ($action == "actDoc") {
                if (!empty($_POST['inputNombre'])) {

                    if (empty($_FILES['inputFile'])) {
                        die("Archivo no puede ser nulo");
                    }

                    $infoExtension = pathinfo($_FILES['inputFile']['name'], PATHINFO_EXTENSION);
                    if ($infoExtension !== 'pdf') {
                        die("El archivo debe ser un PDF");
                    }
                
                    $newDocName = $_POST['inputNombre'];
                    $sql = "UPDATE documento SET nombre_documento = :nombre_doc WHERE id_documento = :id_doc";
                    $stmtActDoc = $conexion->prepare($sql);
                    $stmtActDoc->bindParam(':nombre_doc', $newDocName, PDO::PARAM_STR);
                    $stmtActDoc->bindParam(':id_doc', $_POST['inputDocId'], PDO::PARAM_INT);
                    $stmtActDoc->execute();
                    
                    $id_archivo = $_POST['inputDocId'];
                    $rutaDocumentos = '../../uploads/documentos/';
                    $nuevoArchivoNombre = $rutaDocumentos . $id_archivo . '.' . $infoExtension;
                    $archivoFueSubido = move_uploaded_file($_FILES['inputFile']['tmp_name'], $nuevoArchivoNombre);
                    if (!$archivoFueSubido) {
                        die("Error al subir el archivo");
                    }

                    header('Location: ../../dashboard/adminpanel.php');
                }
            }
            if ($action == "bajaDoc") {
                $activo = 0;
                $sql = "UPDATE documento SET activo = :sel WHERE id_documento = :id_doc";
                $stmtActDoc = $conexion->prepare($sql);
                $stmtActDoc->bindParam(':sel', $activo, PDO::PARAM_INT);
                $stmtActDoc->bindParam(':id_doc', $_POST['inputDocId'], PDO::PARAM_INT);
                $stmtActDoc->execute();
            }
        }
        header("Location: ../../dashboard/adminpanel.php");
    } catch (\Throwable $th) {
        throw $th;
    }
?>