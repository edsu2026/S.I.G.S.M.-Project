<?php
    $repository = "../../..";
    $phpSources = $repository . "/php";
    
    require $phpSources . "/conexion.php";
    require $phpSources . "/captcha.php";

    # log in

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        try { 
            if (!isset($_POST['accion'])) die('No se declaró una acción');
            
            switch ($_POST['accion']) {
                case 'login':
                    session_start();

                    // Verificamos que la cookie de session esté generada para verificar el captcha
                    if (!isset($_SESSION['captcha_login'])) {

                        //  Terminamos el programa con mensaje de error
                        session_abort();
                        die('El sistema no pudo obtener el captcha. No está generado, así que no se puede validar que el usuario no sea un robot');
                    }

                    // Obtenemos el valor del captcha para verificar la respuesta primero, evitando que el programa no ejecute cosas inútiles si no se resolvió (el captcha) de manera correcta.
                    $captcha_answer = $_POST['input-captcha-answer'] ?? die('El sistema espera que el usuario responda el captcha');

                    // Si la respuesta no es correcta, el programa terminará
                    if (!validate_captcha($captcha_answer)) {

                        //  Terminamos el programa con mensaje de error
                        session_abort();
                        die('Captcha fallido');
                    }

                    // Obtenemos información de los inputs
                    $email          = $_POST['input-email']          ?? die('El sistema espera un correo electrónico');
                    $password       = $_POST['input-password']       ?? die('El sistema espera una contraseña');

                    // Buscamos al usuario por su email
                    $consulta = "SELECT * FROM usuario WHERE email = :email";
                    $stmt = $conexion->prepare($consulta);
                    $stmt->bindParam(":email", $email, PDO::PARAM_STR);
                    $stmt->execute();
                    
                    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

                    if (!$usuario) {
                        header("Location: " . $repository . "/dashboard/panel/page.php");
                    }

                    if ($usuario['activo'] == 0) {
                        die('Esta cuenta no se puede utilizar');
                    }

                    if ($usuario) {

                        if (password_verify($password, $usuario['contraseña'])) {

                            //  Le asignamos datos a la cookie $_SESSION
                            $_SESSION['id_user'] = $usuario['id_user'];
                            $_SESSION['rol']     = $usuario['rol'];
                            $_SESSION['email']   = $usuario['email'];
                            
                            header("Location: " . $repository . "/dashboard/panel/page.php");
                            exit();
                        } else {
                            echo "Contraseña incorrecta.";
                        }
                    } else {
                        echo "El correo electrónico no está registrado.";
                    }
                    break;
                case 'logout':
                    
                    $_SESSION = [];

                    if (ini_get("session.use_cookies")) {
                        $paramsList = session_get_cookie_params();
                        setcookie(session_name(), '', time() - 3600,
                            $paramsList["path"],   $params["domain"],
                            $paramsList["secure"], $params["httponly"]
                        );
                    }

                    session_destroy();
                    header('Location: ' . $repository . '/home/login/page.php');
                    break;
                default:
                    # code...
                    break;
            }
        } catch (PDOException $e) {
            // En producción es mejor guardar esto en un log en vez de mostrarlo al usuario
            echo "Error en la base de datos: " . $e->getMessage();
        }
    }

    # log out
?>