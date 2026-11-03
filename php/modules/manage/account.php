<?php
    function password_is_valid($password) {
        if (!preg_match('/[0-9]/', $password))        return false;
        if (!preg_match('/[^a-zA-Z0-9]/', $password)) return false;
        if (!preg_match('/[a-z]/', $password))        return false;
        if (strlen($password) < 8)                    return false;
        if (!preg_match('/[A-Z]/', $password))        return false;

        return true;
    }

    $repository = "../../..";

    require       $repository . "/php/conexion.php";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

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
                if (!filter_var($account_email, FILTER_VALIDATE_EMAIL))        die('Ingrese un correo electrónico válido. Sentido común crack');

                //  Declaramos los roles que existen dentro de este sistema;
                $permitted_roles    =   ['administrador', 'administrativo'];
                $role_exists        =   false;

                //  Recorremos el array de roles y lo comparamos con el rol ingresado
                foreach ($permitted_roles as $role) {
                    if ($role == $account_role) $role_exists = true;
                }

                //  Si el rol no existe, el programa cierra
                if (!$role_exists)      die('Ingrese un rol válido');

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
            
            default:
                # code...
                break;
        }
    }

    header("Location: " . $repository . "/dashboard/panel/page.php");
?>