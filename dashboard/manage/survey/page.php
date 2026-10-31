<?php
    # carpeta principal
    $repositorio = '../../../';

    # subcarpetas
    $styles = $repositorio . 'styles';
    $assets = $repositorio . 'assets';
    $home = $repositorio . 'home';

    # PDO
    require $repositorio . 'php/conexion.php';

    $id_documento = 0;

    if ($_SERVER["REQUEST_METHOD"] == "GET"):
        $id_categoria = $_GET['id_categoria'] ?? NULL;
        if (!isset($_GET['accion'])) {
            exit('No se declaró la acción');
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
    <link rel="stylesheet" href="<?= $styles ?>/input.css">
    <link rel="stylesheet" href="<?= $styles ?>/buttongroup.css">
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
                <div id="header-sub" class="col-12 d-flex flex-row justify-content-start align-items-center">
                    <span>
                        <a href="#" class="button button-link">
                            Redirecciones
                        </a>
                    </span>
                    <span>
                        <a href="#" class="button button-link">
                            Preguntas
                        </a>
                    </span>
                    <span>
                        <a href="#" class="button button-link">
                            Campañas
                        </a>
                    </span>
                    <span>
                        <a href="#" class="button button-link">
                            Acerca de
                        </a>
                    </span>
                </div>
            </div>
            
        </header>
        <!--
            Contenedor principal
        -->
        <main class="row d-flex flex-row justify-content-center" style="margin-top: 110px; height: 490px;">
            <!-- Contenedor del panel -->
        </main>
    </div>
    <script src="<?= $styles ?>/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <?php endif ?>
</body>
</html>