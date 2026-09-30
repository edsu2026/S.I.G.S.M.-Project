<?php
    try {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            require "../conexion.php";
            $action = $_POST['action'];
            if ($action == "actDoc") {
                if (!empty($_POST['inputNombre'])) {
                    $newDocName = $_POST['inputNombre'];
                    $sql = "UPDATE documento SET nombre_documento = :nombre_doc WHERE id_documento = :id_doc";
                    $stmtActDoc = $conexion->prepare($sql);
                    $stmtActDoc->bindParam(':nombre_doc', $newDocName, PDO::PARAM_STR);
                    $stmtActDoc->bindParam(':id_doc', $_POST['inputDocId'], PDO::PARAM_INT);
                    $stmtActDoc->execute();
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
        header("Location: ../../panel/adminpanel.php");
    } catch (\Throwable $th) {
        throw $th;
    }
?>