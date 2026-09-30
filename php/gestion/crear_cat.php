<?php
    try {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            require "../conexion.php";
            if (!empty($_POST['newCatName'])) {
                $sqlCreateCat = "INSERT INTO categoria (nombre_categoria) VALUES (:nombre_categoria)";
                $stmt = $conexion->prepare($sqlCreateCat);
                $stmt->bindParam(':nombre_categoria', $_POST['newCatName'], PDO::PARAM_STR);
                $stmt->execute();
            }
        }
        header('Location: ../../panel/adminpanel.php');
    } catch (\Throwable $th) {
        throw $th;
    }
?>