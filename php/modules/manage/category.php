<?php
    $repository = "../../../";
    $dashboard = $repository . "dashboard/";
    $phpSource = $repository . "php/";

    require $phpSource . "conexion.php";

    # Si el método es post
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        try {
            switch ($_POST["action"]) {

                # Si la acción del administrador es "crear categoría"
                case "create":
                    $categoryName = $_POST['category-create-input'];
                    $consulta = "INSERT INTO categoria (nombre_categoria) VALUE (:nombre)";
                    $stmt = $conexion->prepare($consulta);
                    $stmt->bindParam(":nombre", $categoryName, PDO::PARAM_STR);
                    $stmt->execute();
                    break;

                case "update":
                    var_dump($_POST);

                    $categoryId = $_POST['category-id-rename-input'];
                    $categoryNewName = $_POST['category-new-name-input'];
                    
                    $consulta = "UPDATE categoria SET nombre_categoria = :nombre_categoria WHERE id_categoria = :id_categoria";
                    
                    $stmt = $conexion->prepare($consulta);
                    $stmt->bindParam(":nombre_categoria", $categoryNewName, PDO::PARAM_STR);
                    $stmt->bindParam(":id_categoria", $categoryId, PDO::PARAM_INT);
                    $stmt->execute();
                    
                    break;
                default:
                    break;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    header("Location: " . $dashboard . "panel/page.php");
?>