<?php
    function create_captcha(): array {

        //  Validamos que la sesion haya estado inicializada
        if (!session_status() == PHP_SESSION_NONE) {

            //  Finalizamos el programa con mensaje de error 
            die('Llama a la cookie $_SESSION primero con session_start');
        }

        //  Al azar obtenemos valores numéricos que formularán la pregunta
        $a              = random_int(1, 9);
        $b              = random_int(1, 9);

        //  Almacenamos la respuesta del captcha en $_SESSION (luego la procesará el programa de inicio de sesión)
        $_SESSION['captcha_login'] = [
            'answer' => $a + $b
        ];
        return  ['pregunta' =>  $a . ' +  ' . $b];
    }

    function validate_captcha(int $answer_input_value): bool       {
        $captcha           = $_SESSION['captcha_login'] ?? null;
        unset($_SESSION['captcha_login']);

        if (!is_array($captcha)) {
            echo "Error: el captcha no se logró obtener";
            return false;
        };

        if (!is_int($answer_input_value)) {
            echo "Error: la respuesta no es un número o no es un número entero";
            return false;
        }

        return $answer_input_value === $captcha['answer'];
    }
?>