<?php
    function password_is_valid($password) {
        if (!preg_match('/[0-9]/', $password))        return false;
        if (!preg_match('/[^a-zA-Z0-9]/', $password)) return false;
        if (!preg_match('/[a-z]/', $password))        return false;
        if (strlen($password) < 8)                    return false;
        if (!preg_match('/[A-Z]/', $password))        return false;

        return true;
    }

    function role_is_valid($input_role) {
        //  Declaramos los roles que existen dentro de este sistema;
        $permitted_roles    =   ['administrador', 'administrativo'];
        $role_exists        =   false;

        //  Recorremos el array de roles y lo comparamos con el rol ingresado
        foreach ($permitted_roles as $role) {
            if ($role == $input_role) return true;
        }
        return false;
    }

    $repository = "../../..";

    require       $repository . "/php/conexion.php";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        try {
            if (!isset($_POST['accion'])) {
                die('Ninguna acción fue declarada');
            }

            switch ($_POST['accion']) {

                case 'create':
                    //  Obtenemos los datos ingresados de la cuenta
                    $account_email      =   $_POST['account-email-input']                  ?? die('No se ingresó un email');
                    $account_password   =   $_POST['account-password-input']               ?? die('No se ingresó una contraseña');
                    $account_role       =   strtolower($_POST['account-role-input'])        ?? die('No se ingresó ningún rol');

                    //  Validamos los datos
                    if (!password_is_valid($account_password))                     die('Ingrese una contraseña que cumpla con los parámetros');
                    if (!filter_var($account_email, FILTER_VALIDATE_EMAIL))        die('Ingrese un correo electrónico válido');

                    //  Si el rol no existe, el programa cierra
                    if (!role_is_valid($account_role))      die('Ingrese un rol válido');

                    //  Hasheamos la contraseña
                    $processed_password =   password_hash($account_password, PASSWORD_DEFAULT);
                    
                    //  Consulta
                    $sql                =   $conexion->prepare("INSERT INTO usuario (email, contraseña, rol) VALUES (:account_email, :account_passwd, :account_role)");
                    $sql->bindParam(":account_email"   , $account_email,    PDO::PARAM_STR);
                    $sql->bindParam(":account_passwd"  , $processed_password, PDO::PARAM_STR);
                    $sql->bindParam(":account_role"    , $account_role,     PDO::PARAM_STR);
                    $sql->execute();

                    if ($sql->rowCount() < 1) {
                        die('No se pudo crear el usuario');
                    }
                    # code...
                    break;
                case 'update':

                    //  Controlamos el flujo dependiendo de qué cosa de quiere actualizar
                    switch ($_POST['accion-update']) {
                        
                        //  En el caso de que la acción sea "actualizar email"
                        case 'update-email':

                            //  Obtenemos los datos del email ingresado
                            $account_email      =   $_POST['account-email-input'] ?? die('Necesario ingresar un email');

                            //  Verificamos que el email ingresado sea válido
                            if (!filter_var($account_email, FILTER_VALIDATE_EMAIL))        die('Ingrese un correo electrónico válido');

                            //  Obtenemos información de la cuenta a actualizar
                            $account_id         =   $_POST['user_id'];

                            //  Consulta para actualizar
                            $stmt               =   $conexion->prepare("UPDATE usuario SET email = :email WHERE id_user = :id");
                            $stmt->bindParam(':email', $account_email, PDO::PARAM_STR);
                            $stmt->bindParam(':id'   , $account_id   , PDO::PARAM_INT);
                            $stmt->execute();

                            //  Validamos que se haya actualizado
                            if ($stmt->rowCount() < 1) {
                                die('El sistema no logró actualizar el usuario');
                            }
                        
                            break;
                        case 'update-password':
                            
                            //  Obtenemos los datos de la contraseña ingresada
                            $account_password      =   $_POST['account-password-input'] ?? die('Necesario ingresar un email');

                            //  Verificamos que el email ingresado sea válido
                            if (!password_is_valid($account_password))        die('Ingrese una contraseña válida');

                            //  Hasheamos la contraseña
                            $account_password      =   password_hash($account_password, PASSWORD_DEFAULT);

                            //  Obtenemos información de la cuenta a actualizar
                            $account_id         =   $_POST['user_id'];

                            //  Consulta para actualizar
                            $stmt               =   $conexion->prepare("UPDATE usuario SET contraseña = :passwd WHERE id_user = :id");
                            $stmt->bindParam(':passwd', $account_password, PDO::PARAM_STR);
                            $stmt->bindParam(':id'    , $account_id      , PDO::PARAM_INT);
                            $stmt->execute();

                            //  Validamos que se haya actualizado
                            if ($stmt->rowCount() < 1) {
                                die('El sistema no logró actualizar el usuario');
                            }
                        
                            break;
                        case 'update-role':

                            //  Obtenemos los datos del rol ingresado
                            $account_role      =   $_POST['account-role-input'] ?? die('Necesario ingresar un email');

                            //  Si el rol no existe, el programa cierra
                            if (!role_is_valid($account_role)) die('El rol no es válido');

                            //  Obtenemos información de la cuenta a actualizar
                            $account_id         =   $_POST['user_id'];

                            //  Consulta para actualizar
                            $stmt               =   $conexion->prepare("UPDATE usuario SET rol = :newrole WHERE id_user = :id");
                            $stmt->bindParam(':newrole', $account_role , PDO::PARAM_STR);
                            $stmt->bindParam(':id'     , $account_id   , PDO::PARAM_INT);
                            $stmt->execute();

                            //  Validamos que se haya actualizado
                            if ($stmt->rowCount() < 1) {
                                die('El sistema no logró actualizar el usuario');
                            }
                        
                            break;
                        default:
                            # code...
                            break;
                    }

                    break;
                
                case 'deactivate':
                    //  Obtenemos información de la cuenta a desactivar
                    $account_id         =   $_POST['user_id'];

                    //  Consulta para desactivar
                    $stmt               =   $conexion->prepare("UPDATE usuario SET activo = 0 WHERE id_user = :id");
                    $stmt->bindParam(':id'   , $account_id   , PDO::PARAM_INT);
                    $stmt->execute();
                    break;
                
                case 'activate':
                    //  Obtenemos información de la cuenta a actualizar
                    $account_id         =   $_POST['user_id'];

                    //  Consulta para actualizar
                    $stmt               =   $conexion->prepare("UPDATE usuario SET activo = 1 WHERE id_user = :id");
                    $stmt->bindParam(':id'   , $account_id   , PDO::PARAM_INT);
                    $stmt->execute();
                    break;
                default:
                    # code...
                    break;
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    header("Location: " . $repository . "/dashboard/panel/page.php");
?>