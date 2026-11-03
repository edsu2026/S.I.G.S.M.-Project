<?php
    //  Localizamos el repositorio del proyecto
    $repository         = "..";
    
    //  Localizamos la página de encuestas
    $surveys_page       = $repository . "/home/surveys/page.php";

    //  Verificamos si se ha recibido un token de encuesta
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {

        //  Obtenemos el token de encuesta de la URL
        $SURVEY_TOKEN      = $_GET['token'];
        $SURVEYS_AVAILABLE = null;

        //  Verificamos si el token de encuesta está presente
        if (empty($SURVEY_TOKEN)) {
            echo 'Token is missing.';
            exit();
        }

        //  Verificamos si la cookie de encuestas disponibles existe, si no, la creamos
        if (!isset($_COOKIE['AVAILABLE_SURVEYS'])) {
            $SURVEYS_AVAILABLE = [];
        } else {
            $SURVEYS_AVAILABLE = json_decode($_COOKIE['AVAILABLE_SURVEYS'], true);

            //  Verificamos si el token de encuesta ya está presente en la cookie
            foreach ($SURVEYS_AVAILABLE as $token) {
                if ($token === $SURVEY_TOKEN) {
                    //  Si el token ya está presente, redirigimos al usuario a la página de encuestas
                    header('Location: ' . $surveys_page);
                    exit();
                }
            }
        }

        $SURVEYS_AVAILABLE[] = $SURVEY_TOKEN;

        //  Almacenamos los tokens en la cookie
        setcookie('AVAILABLE_SURVEYS', json_encode($SURVEYS_AVAILABLE), time() + (86400 * 30), '/'); // 30 días

        //  Redirigimos al usuario a la página de encuestas
        header('Location: ' . $surveys_page);
        exit();
    } else {
        echo 'Invalid request method.';
    }
?>