<?php
    # carpeta principal
    $repositorio = '../../';

    # subcarpetas
    $styles = $repositorio . 'styles';
    $assets = $repositorio . 'assets';
    $home = $repositorio . 'home';
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
    <link rel="stylesheet" href="<?= $styles ?>/input.css">
    <link rel="stylesheet" href="<?= $styles ?>/login_sistema.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body>
    <div class="container-fluid d-flex flex-column min-vh-100">
        <header class="row">
            <div id="header-main" class="col-12 d-flex flex-column">
                <div id="header-main-options" class="col-12 d-flex flex-row justify-content-center align-items-center">
                    <div class="col-12 d-flex flex-row align-items-start">
                        <span>
                            <a href="#" class="button button-link">Hospital de Clínicas</a>
                        </span>
                    </div>
                </div>
            </div>
        </header>
        <!--
            Contenedor principal
        -->
        <main class="row d-flex flex-column flex-md-row justify-content-center h-100">
            <!-- Contenedor del form -->
            <div class="col-12 col-md-6 d-flex flex-column justify-content-center align-items-center gap-3 p-5 rounded vh-50" style="border: 1px solid rgba(0, 0, 0, 0.4);">
                <form action="<?= $repositorio ?>php/modules/user/account.php" method="POST" class="col-12 d-flex flex-column align-items-center gap-3">
                    <input name="input-email" class="col-12 input-def" type="text" placeholder="Ingrese email">
                    <input name="input-password" class="col-12 input-def" type="password" placeholder="Ingrese contraseña">
                    <button name="action" value="login" class="col-6 button-panel button-theme-lightblue rounded">Iniciar sesión</button>
                </form>
            </div>
            <div class="login-right col-12 col-md-6 d-flex gap-3 p-5 rounded shadow vh-50" style="border: 1px solid rgba(0, 0, 0, 0.4);"></div>
        </main>
    </div>
    <script src="<?= $styles ?>/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>