<?php
    # Función para establecer la cookie de categoria seleccionada

    # carpeta principal
    $repositorio = '../../';

    # subcarpetas
    $styles      = $repositorio . 'styles';
    $assets      = $repositorio . 'assets';
    $home        = $repositorio . 'home';

    # PDO
    require $repositorio . 'php/conexion.php';

    # Obtenemos la cookie de encuestas disponibles
    $surveys_available      = isset($_COOKIE['AVAILABLE_SURVEYS']) ? json_decode($_COOKIE['AVAILABLE_SURVEYS']) : false;
    $surveys_url            = $home . '/surveys/survey/page.php';

    # Array de encuestas disponibles
    $surveys            = [];

    if ($surveys_available) {

        #  Generamos una cookie para almacenar la encuesta seleccionada (así evitamos ingresarla en la URL)
        setcookie('SELECTED_SURVEY', '', time() + (86400 * 30), '/'); // 30 días

        #  Recorremos los tokens de encuestas disponibles y obtenemos la información de cada encuesta
        foreach ($surveys_available as $survey_token) {

            #  Consulta
            $sql = $conexion->prepare("SELECT * FROM categoria WHERE token_categoria = :token");
            $sql->bindParam(':token', $survey_token);
            $sql->execute();

            #  Obtenemos la información de la encuesta
            $survey = $sql->fetchAll();
            
            #   Validamos que la categoria exista, si no, mostramos un mensaje de error
            if ($survey) {
                #  Agregamos la encuesta al array de encuestas disponibles
                $surveys[] = $survey[0];
            }
        }
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
            <div class="col-12 d-flex flex-column justify-content-center align-items-center flex-md-row justify-content-md-between align-items-md-start">

                <!-- Encuestas pendientes -->
                <div class="col-11 col-md-7 d-flex flex-column justify-content-center align-items-center gap-3" style="margin-bottom: 40px;">

                    <?php 
                        
                        #  Verificamos si hay encuestas disponibles
                        if (empty($surveys)) {
                            echo '<div class="col-12 d-flex flex-column justify-content-center align-items-center sigsm-shadow-y-1 p-3 gap-2" style="height: auto;">
                                    <h5 class="rounded text-center d-block text-center" style="background-color: rgb(157, 177, 253); color: rgb(64, 70, 249); ">No hay encuestas disponibles</h5>
                                    <p style="color: rgb(168, 168, 168); font-weight: 300;">No se han encontrado encuestas disponibles para tu usuario. Escanea un código QR para acceder a las encuestas disponibles.</p>
                                </div>';
                        } else {
                            #  Mostramos las encuestas disponibles
                            foreach ($surveys as $survey):
                            ?>
                            <a href="./redirect.php?token=<?= $survey['token_categoria'] ?>" class="col-10 d-flex flex-row category-link sigsm-shadow-y-1 sigsm-indicator">

                                <!-- Imagen -->
                                <div class="col-6 d-flex flex-column align-items-center justify-content-center" style="overflow: hidden;">
                                    <img style="col-12" src=" <?= $assets ?>/img/example1.jpg" style="background-size: cover; background-position: center; max-height: 130px; object-fit: cover;">
                                </div>

                                <!-- Titulo -->
                                <div class="col-6 d-flex flex-column justify-content-center align-items-center">
                                    <h5> <?= $survey['nombre_categoria'] ?> </h5>
                                </div>
                            </a>
                            <?php
                            endforeach;
                        }
                    ?>
                </div>

                <!-- Informacion -->
                <div class="col-11 col-md-5 d-flex justify-content-sm-center align-items-md-start sigsm-shadow-y">
                    <div class="col-11 sigsm-indicator sigsm-shadow-y-1 p-3 gap-2" style="height: auto;">
                        <h5 class="rounded text-center d-block text-center" style="background-color: rgb(157, 177, 253); color: rgb(64, 70, 249); ">Acerca</h5>
                        <p style="color: rgb(168, 168, 168); font-weight: 300;">En esta página encontrarás todas las encuestas que tienes disponibles. Cuando escaneas un código QR, la aplicación te redirigirá a esta página y te enseñará todos los servicios disponibles para reseñar según los QR que hayas escaneado.</p>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script src="<?= $styles ?>/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>