<?php
    $repository = '../../../';
    require $repository . '/php/conexion.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        switch ($_POST['accion']) {
            case 'submit':
                // Obtener el token de la categoría seleccionada desde la cookie
                $category_token = $_COOKIE['SELECTED_SURVEY'];

                if (empty($category_token)) {
                    var_dump($_COOKIE['SELECTED_SURVEY']);
                    die("Error: No se ha seleccionado ninguna categoría.");
                }

                // Aquí puedes procesar los datos del formulario
                $answer1 = $_POST['question-1-input'] ?? null;
                $answer2 = $_POST['question-2-input'] ?? null;
                $answer3 = $_POST['question-3-input'] ?? null;

                // Validar que todas las preguntas hayan sido respondidas
                if ($answer1 && $answer2 && $answer3) {
                    // Buscamos la categoria con cierto token
                    $search = $conexion->prepare("SELECT id_categoria FROM categoria WHERE token_categoria = :token");
                    $search->bindParam(':token', $category_token, PDO::PARAM_STR);
                    $search->execute();
                    $category = $search->fetchAll();

                    if (!$category) {
                        die("Error: La categoría con token '$category_token' no existe.");
                    }

                    $category = $category[0]; // Obtenemos el primer resultado

                    // Consulta
                    $sql = $conexion->prepare("INSERT INTO reseña (id_categoria, atención, tiempo, condiciones) VALUES (:catid, :answer1, :answer2, :answer3)");
                    $sql->bindParam(':catid'  , $category['id_categoria'], PDO::PARAM_INT);
                    $sql->bindParam(':answer1', $answer1,                  PDO::PARAM_INT);
                    $sql->bindParam(':answer2', $answer2,                  PDO::PARAM_INT);
                    $sql->bindParam(':answer3', $answer3,                  PDO::PARAM_INT);
                    $sql->execute();

                    // Validamos cambios
                    if ($sql->rowCount() > 0) {
                        echo "Respuestas guardadas correctamente.";
                    } else {
                        die("Error al guardar las respuestas.");
                    }

                    setcookie('SELECTED_SURVEY', '', time() - 3600, '/'); // Borrar la cookie
                } else {
                    echo "Por favor, responde todas las preguntas.";
                }
                break;
            case 'delete':
                // Aquí puedes procesar los datos del formulario para eliminar una encuesta existente
                break;
            default:
                break;
        }
    } else {
        echo "Método de solicitud no permitido.";
    }
    header("Location: " . $repository . "home/main");
?>