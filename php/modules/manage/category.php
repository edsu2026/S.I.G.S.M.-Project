<?php
    function category_name_isvalid($category_name) {
        $category_name = trim($category_name);

        if (empty($category_name)) return false;
        if (mb_strlen($category_name, 'UTF-8') > 32 || mb_strlen($category_name, 'UTF-8') < 6) return false;
        if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ ]+$/u", $category_name))  return false;

        return true; 
    }

    $repository = "../../../";
    $dashboard = $repository . "dashboard/";
    $phpSource = $repository . "php/";

    require $phpSource . "conexion.php";

    # Si el método es post
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        try {
            switch ($_POST["accion"]) {

                # Si la acción del administrador es "crear categoría"
                case "create":
                    //  Obtenemos información de la categoría
                    $category_name      =   $_POST['category-name-input'];
                    //  Validamos nombre
                    if (!category_name_isvalid($category_name)) {
                        die('El nombre que se intenta ingresar no es válido');
                    }

                    $token_categoria    =   bin2hex(random_bytes(32)); // Genera un token de 64 caracteres

                    //  Consulta
                    $sql                =   $conexion->prepare("INSERT INTO categoria (nombre_categoria, token_categoria) VALUE (:catname, :cattoken)");
                    $sql->bindParam(":catname", $category_name, PDO::PARAM_STR);
                    $sql->bindParam(":cattoken", $token_categoria, PDO::PARAM_STR);
                    $sql->execute();

                    //  Validamos que se haya insertado alguna tabla
                    if ($sql->rowCount() < 1) {
                        die('No se insertó ninguna tabla');
                    }
                    break;

                case "update":
                    //  Obtenemos información de la categoría
                    $category_id        =   $_POST['category-id-input'];
                    $category_newname   =   $_POST['category-name-input'];

                    //  Validamos nombre
                    if (!category_name_isvalid($category_newname)) {
                        exit('El nombre que se intenta ingresar no es válido');
                    }
                    
                    //  Consulta
                    $sql                =   $conexion->prepare("UPDATE categoria SET nombre_categoria = :nombre_categoria WHERE id_categoria = :id_categoria");
                    $sql->bindParam(":nombre_categoria", $category_newname, PDO::PARAM_STR);
                    $sql->bindParam(":id_categoria", $category_id, PDO::PARAM_INT);
                    $sql->execute();
                    break;
                default:
                    break;
            }
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    header("Location: " . $dashboard . "panel/page.php");
?>