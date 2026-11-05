<?php
    # carpeta principal
    $repository = '../../..';

    # subcarpetas
    $styles     = $repository . '/styles';
    $assets     = $repository . '/assets';
    $home       = $repository . '/home';

    /** SESIÓN
     *  Reanudamos la sesión ya realizada anteriormente.
     *  Si id_user dentro de la cookie "$_SESSION" no está declarado, significa que no se inició sesión.
     *  */  
    session_start();
    if (!isset($_SESSION['id_user'])) {
        /** Terminamos la ejecución del programa
         *  Se planea que el programa redirija al usuario a una página con un mensaje de error.
         */
        header('Location: ' . $home . '/login/page.php');
    }

    # PDO
    require $repository . '/php/conexion.php';

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        try {
            $userId = $_GET['user'] ?? 0;

            if ($userId == 0 || $userId == 1) {
                header('Location: ' . $repository . '/dashboard/panel/page.php');
            }
            
            $userQuery   = "SELECT * FROM usuario WHERE id_user = :id";
            $getUser     = $conexion->prepare($userQuery);
            $getUser->bindParam(':id', $userId, PDO::PARAM_INT);
            $getUser->execute();

            $user        = $getUser->fetch();
            
            if (!$user)     header('Location: ' . $repository . '/dashboard/panel/page.php');
        } catch (\Throwable $th) {
            throw $th;
        }
    } else {
        header('Location: ' . $repository . '/dashboard/panel/page.php');
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="<?= $styles ?>/colorthemes.css">
    <link rel="stylesheet" href="<?= $styles ?>/button.css">
    <link rel="stylesheet" href="<?= $styles ?>/card-custom.css">
    <link rel="stylesheet" href="<?= $styles ?>/main.css">
    <link rel="stylesheet" href="<?= $styles ?>/buttongroup.css">
    <link rel="stylesheet" href="<?= $styles ?>/sigsm-input.css">
    <link rel="stylesheet" href="<?= $styles ?>/shadow.css">
    <link rel="stylesheet" href="<?= $styles ?>/sigsm-indicator.css">
    <link rel="stylesheet" href="<?= $styles ?>/sigsm-buttonsgrp.css">

    <style>
        html, body {
            height: 100%;
            margin: 0;
        }

        /* Permitir que el contenedor principal ocupe al menos toda la altura de la ventana */
        body > .container-fluid {
            min-height: 100%;
            display: flex;
            flex-direction: column;
        }

        main.row {
            flex: 1; /* Hace que el main ocupe todo el espacio restante debajo del header */
        }

        .separator {
            border:        2px solid rgb(204, 202, 202);
            width:         80%;
            align-self:    center;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body>
    <div class="container-fluid d-flex flex-column">
        <header class="row" style="z-index: 1">
            <div id="header-main" class="col-12 d-flex flex-column">
                <div id="header-main-options" class="col-12 d-flex flex-row justify-content-center align-items-center">
                    <div class="col-6 d-flex flex-row">
                        <span>
                            <a href="#" class="button button-link">Hospital de Clínicas</a>
                        </span>
                    </div>
                    <div class="col-6 d-flex flex-row justify-content-end align-items-center mr-1">
                        <span>
                            <a href="#" class="button button-link">Iniciar sesión</a>
                        </span>
                    </div>
                </div>
            </div>
        </header>
        <!--
            Contenedor principal
        -->
        <main class="row d-flex justify-content-center main-mt align-self-stretch" style="color: rgb(75, 69, 80); font-weight: 600; font-size: 14px;">
            <!-- Contenedor del panel -->
            <form action="<?= $repository ?>/php/modules/manage/account.php" method="POST" class="col-12 d-flex flex-column flex-md-row align-self-stretch justify-content-between" autocomplete="off">
                <!-- Input oculto que indica que la acción es "update" -->
                <input name="accion"  value="update"                  hidden required>
                <input name="user_id" value="<?= $user['id_user'] ?>" hidden required>

                <!-- Opciones -->
                <div class="col-2 sigsm-buttonsgrp sigsm-buttonsgrp-br">
                    <?php
                    switch ($user['activo']) {
                        case false: ?> <button name="accion" value="activate">Activar</button> <?php break;
                        case true : ?> <button name="accion" value="deactivate">Desactivar</button> <?php break;
                        default: die('Falta determinar "activo"'); break;
                    }
                    ?>
                    <button type="submit" name="accion" value="delete">    Eliminar</button>
                </div>

                <div class="col-12 col-md-6">
                    <div class="col-12 d-flex flex-column flex-md-row flex-wrap align-items-md-center justify-content-center gap-4">
                        <div class="d-flex flex-column align-items-center justify-content-center sigsm-indicator sigsm-shadow-y-1 gap-2" style="height: 300px; width: 300px">
                            <h5>Correo electronico</h5>
                            <input  name="account-email-input" class="col-10 sigsm-input-def" type="text" placeholder="Nuevo correo">
                            <button type="submit" name="accion-update" value="update-email"     class="col-10 button-panel button-theme-lightblue rounded">Actualizar</button>
                        </div>
                        <div class="d-flex flex-column align-items-center justify-content-center sigsm-indicator sigsm-shadow-y-1 gap-2" style="height: 300px; width: 300px">
                            <h5>Contraseña</h5>
                            <input  name="account-password-input" class="col-10 sigsm-input-def" type="text" placeholder="Nueva contraseña">
                            <button type="submit" name="accion-update" value="update-password"     class="col-10 button-panel button-theme-lightblue rounded">Actualizar</button>
                        </div>
                        <div class="d-flex flex-column align-items-center justify-content-center sigsm-indicator sigsm-shadow-y-1 gap-2" style="height: 300px; width: 300px">
                            <h5>Rol</h5>
                            <input  name="account-role-input" class="col-10 sigsm-input-def" type="text" placeholder="Ingrese rol">
                            <button type="submit" name="accion-update" value="update-role"     class="col-10 button-panel button-theme-lightblue rounded">Actualizar</button>
                        </div>
                    </div>
                </div>
                
                <!-- Propiedades -->
                <div class="d-flex flex-column sigsm-buttonsgrp-bl col-2">
                    <div class="col-12 d-flex flex-column align-items-center p-2">
                        <h5 class="col-6 rounded" style="background-color: rgb(160, 253, 255); color: rgb(14, 187, 230); text-align: center;">Nombre</h5>
                        <p class="text-break"><?= $user['email'] ?></p>
                    </div>
                    <hr class="separator">
                    <div class="col-12 d-flex flex-column align-items-start p-2">
                        <h5 class="col-6">Estado</h5>
                        <?php
                        switch ($user['activo']) {
                            case false: ?><p class="col-12 badge bg-danger">Inactivo</p><?php break;
                            case true : ?><p class="col-12 badge bg-success">Activo</p> <?php break;
                            default: die('Falta determinar "activo"'); break;
                        }
                        ?>
                    </div>
                    <hr class="separator">
                    <div class="col-12 d-flex flex-column align-items-start p-2">
                        <h5 class="col-6">Rol</h5>
                        <p class="col-12 badge bg-success"><?= $user['rol']?></p>
                    </div>
                </div>
            </form>
        </main>
    </div>
    <script src="<?= $styles ?>/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>