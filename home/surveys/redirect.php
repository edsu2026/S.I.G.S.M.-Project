<?php
    # Localizamos el repositorio del proyecto
    $repository = '../../';

    # Validamos el tipo de metodo (post);
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {

        # Obtenemos el token de encuesta de la URL
        $survey_token = isset($_GET['token']) ? $_GET['token'] : null;

        # Validamos que se haya proporcionado un token de encuesta
        if ($survey_token) {

            # Establecemos la cookie de categoria seleccionada
            setcookie('SELECTED_SURVEY', $survey_token, time() + (86400 * 30), '/'); // 30 días
        } else {
            echo 'No se ha proporcionado un token de encuesta.';
            exit();
        }

        # Redirigimos al usuario a la página de encuestas
        header('Location: ' . $repository . 'home/surveys/survey/page.php');
        exit();
    } else {
        echo 'Método de solicitud no válido.';
        exit();
    }
?>