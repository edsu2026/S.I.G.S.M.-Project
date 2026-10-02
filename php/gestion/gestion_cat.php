<?php

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        try {
            if (!isset($_POST['inputCatId'])) {
                die('Los campos no pueden ser vacios');
            }

            require "../conexion.php";
            $idCat = $_POST['inputCatId'];
            $action = $_POST['action'];

            if ($action == "actCat") {
                $nuevoNombre = ($_POST['inputNombre']) ?? die("El nombre no puede ser nulo");

                $sqlActCat = "UPDATE categoria SET nombre_categoria = :nuevo_nombre WHERE id_categoria = :id_categoria";
                $stmt = $conexion->prepare($sqlActCat);
                $stmt->bindParam(':nuevo_nombre', $nuevoNombre, PDO::PARAM_STR);
                $stmt->bindParam(':id_categoria', $idCat, PDO::PARAM_INT);
                $stmt->execute();
            }
            
            if ($action == "bajaCat") {
                $sqlBajaCat = "UPDATE categoria SET activo = 0 WHERE id_categoria = :id_categoria";
                $stmt = $conexion->prepare($sqlBajaCat);
                $stmt->bindParam(':id_categoria', $idCat, PDO::PARAM_INT);
                $stmt->execute();
            }

            echo "Categoria actualizada";
            header('Location: ../../dashboard/adminpanel.php');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

?>