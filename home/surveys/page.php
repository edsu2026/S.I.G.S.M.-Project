<?php
    # carpeta principal
    $repositorio = '../../';

    # subcarpetas
    $styles = $repositorio . 'styles';
    $assets = $repositorio . 'assets';
    $home = $repositorio . 'home';

    # PDO
    require $repositorio . 'php/conexion.php';

    $sql = "SELECT c.id_categoria, c.nombre_categoria, 
        IF(d.id_documento IS NOT NULL, 
            GROUP_CONCAT(CONCAT(d.id_documento, ';;', d.nombre_documento, ';;', d.fecha_creacion, ';;', d.activo) SEPARATOR '||'), 
            NULL
        ) AS documentos
    FROM categoria c
    LEFT JOIN documento d ON c.id_categoria = d.id_categoria
    GROUP BY c.id_categoria";

    $getCategories = $conexion->prepare($sql);
    $getCategories->execute();
    $categorias = $getCategories->fetchAll();
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
    <link rel="stylesheet" href="<?= $styles ?>/input.css">
    <link rel="stylesheet" href="<?= $styles ?>/shadow.css">
    <link rel="stylesheet" href="<?= $styles ?>/sigsm-indicator.css">
    <style>
        .category-link {
            text-decoration:        none;
            overflow:               hidden;
            height:                 130px;
            transition:
                transform           .1s ease-out,
                background-color    .1s ease-out,
                color               .1s ease-out,
                border-bottom       .1s ease-out;
        }

        .category-link:hover {
            transform:              scale(1.045);
            background-color:       var(--color-blue);
            color:                  var(--color-white);
            border-bottom:          7px solid rgb(14, 35, 118);
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body>
    <div class="container-fluid d-flex flex-column">
        <header class="row" style="z-index: 1">
            <div id="header-main" class="col-12 d-flex flex-column sigsm-shadow-y-1">
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
        <main class="row d-flex justify-content-center main-mt">
            <!-- Contenedor -->
            <div class="col-12 d-flex flex-column flex-md-row justify-content-center align-items-sm-center">

                <!-- Encuestas pendientes -->
                <div class="col-11 col-md-10 d-flex flex-column justify-content-center align-items-center gap-3" style="margin-bottom: 40px;">

                    <!-- Enlace -->
                    <a href="#" class="col-11 col-md-6 d-flex flex-row category-link sigsm-shadow-y-1 sigsm-indicator">

                        <!-- Imagen -->
                        <div class="col-6 d-flex flex-column align-items-center justify-content-center" style="overflow: hidden;">
                            <img style="col-12" src="<?= $repositorio ?>assets/img/example1.jpg" style="background-size: cover; background-position: center; max-height: 130px; object-fit: cover;">
                        </div>

                        <div class="col-6 d-flex flex-column justify-content-center align-items-center">
                            <h5>Servicio medico<h5>
                        </div>
                    </a>

                    <!-- Enlace -->
                    <a href="#" class="col-11 col-md-6 d-flex flex-row category-link sigsm-shadow-y-1 sigsm-indicator">

                        <!-- Imagen -->
                        <div class="col-6 d-flex flex-column align-items-center justify-content-center" style="overflow: hidden;">
                            <img style="col-12" src="<?= $repositorio ?>assets/img/example1.jpg" style="background-size: cover; background-position: center; max-height: 130px; object-fit: cover;">
                        </div>

                        <div class="col-6 d-flex flex-column justify-content-center align-items-center">
                            <h5>Servicio medico<h5>
                        </div>
                    </a>

                    <!-- Enlace -->
                    <a href="#" class="col-11 col-md-6 d-flex flex-row category-link sigsm-shadow-y-1 sigsm-indicator">

                        <!-- Imagen -->
                        <div class="col-6 d-flex flex-column align-items-center justify-content-center" style="overflow: hidden;">
                            <img style="col-12" src="<?= $repositorio ?>assets/img/example1.jpg" style="background-size: cover; background-position: center; max-height: 130px; object-fit: cover;">
                        </div>

                        <div class="col-6 d-flex flex-column justify-content-center align-items-center">
                            <h5>Servicio medico<h5>
                        </div>
                    </a>
                </div>

                <!-- Informacion -->
                <div class="col-12 col-md-4 justify-content-center sigsm-shadow-y">
                    <div class="col-11 sigsm-indicator"></div>
                </div>
            </div>
        </main>
    </div>
    <script src="<?= $styles ?>/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>