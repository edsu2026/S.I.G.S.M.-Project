<?php
    $repository = "../../..";
    $phpSources = $repository . "/php/";
    
    require $phpSources . "conexion.php";

    # log in

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        try { 
            $email = $_POST['input-email'] ?? "";
            $password = $_POST['input-password'] ?? "";

            // 1. Buscamos al usuario por su email
            $consulta = "SELECT * FROM usuario WHERE email = :email LIMIT 1";
            $stmt = $conexion->prepare($consulta);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->execute();
            
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario) {
                $password_preparada = base64_encode(hash('sha256', $password, true));

                if (password_verify($password_preparada, $usuario["contraseña"])) {
                    
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }
                    
                    $_SESSION['id_user'] = $usuario['id_user'];
                    $_SESSION['rol'] = $usuario['rol'];
                    $_SESSION['nombre'] = $usuario['nombre'];
                    
                    switch ($_SESSION['rol']) {
                        case 'paciente':
                            header("Location: " . $repository . "/home/main");

                            break;
                        case 'administrador':
                            header("Location: " . $repository . "/dashboard/panel/page.php");

                            break;
                        case 'administrativo':
                            header("Location: " . $repository . "/dashboard/panel/page.php");

                            break;
                        default:
                            break;
                    }
                    exit();
                    
                } else {
                    echo "Contraseña incorrecta.";
                }
            } else {
                echo "El correo electrónico no está registrado.";
            }

        } catch (PDOException $e) {
            // En producción es mejor guardar esto en un log en vez de mostrarlo al usuario
            echo "Error en la base de datos: " . $e->getMessage();
        }
    }

    # log out
?>