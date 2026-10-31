<?php
    # carpeta principal
    $repositorio = '../../../';

    # subcarpetas
    $styles = $repositorio . 'styles';
    $assets = $repositorio . 'assets';
    $home = $repositorio . 'home';

    if (!isset($_COOKIE['category_id'])) {
        die('Se necesita una cookie para poder enviar una reseña');
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
    <link rel="stylesheet" href="<?= $styles ?>/shadow.css">
    <link rel="stylesheet" href="<?= $styles ?>/sigsm-radio.css">
    <link rel="stylesheet" href="<?= $styles ?>/sigsm-indicator.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body>
    <div class="container-fluid d-flex flex-column min-vh-100">
        <header class="row" style="z-index: 1">
            <div id="header-main" class="col-12 d-flex flex-column sigsm-shadow-y-1">
                <div id="header-main-options" class="col-12 d-flex flex-row justify-content-center align-items-center">
                    <div class="col-6 d-flex flex-row justify-content-center">
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
        <main class="row d-flex flex-column justify-content-center align-items-center main-mt">
            <div id="content-info" class="d-flex col-12 flex-column flex-md-row justify-content-center align-items-center gap-4 gap-md-5 col-lg-row">
                <!-- Card de explicación de reseñas -->
                <div class="col-12 col-md-5 d-flex flex-column gap-5 gap-md-3" style="border: 2px solid rgba(116, 116, 116, 0.2); border-bottom: 10px solid rgb(32, 89, 246); border-radius: 20px; overflow: hidden; height: 310px";>
                    <!-- Imagen -->
                    <div class="col-12" style="max-height: 120px; border-radius: 20px;">
                        <img class="col-12" src="<?= $repositorio ?>/assets/img/example1.jpg" style="background-size: cover; background-position: center; max-height: 130px; object-fit: cover;">
                    </div>
                    <!-- Contenedor de la información de la card -->
                    <div class="col-12 p-4">
                        <h5 class="rounded" style="margin-left: 5px; padding: 5px 5px; font-weight: 600; background-color: rgba(32, 157, 246, 0.4); color: rgb(32, 89, 246);">Reseñas</h5>
                        <p style="margin-left: 5px; color: rgb(168, 168, 168); font-weight: 300;">Las reseñas ayudan al Hospital de Clínicas a mejorar sus servicios</p>
                    </div>
                </div>
                <!-- Card de explicación de encuestas -->
                <div class="col-12 col-md-5 d-flex flex-column gap-5 gap-md-3" style="border: 2px solid rgba(116, 116, 116, 0.2); border-bottom: 10px solid rgb(32, 89, 246); border-radius: 20px; overflow: hidden; height: 310px";>
                    <!-- Imagen de la card -->
                    <div class="col-12" style="max-height: 120px; border-radius: 20px;">
                        <img class="col-12" src="<?= $repositorio ?>/assets/img/example1.jpg" style="background-size: cover; background-position: center; max-height: 130px; object-fit: cover;">
                    </div>
                    <!-- Contenedor de la información de la card -->
                    <div class="col-12 p-4">
                        <h5 class="rounded" style="margin-left: 5px; padding: 5px 5px; font-weight: 600; background-color: rgba(103, 32, 246, 0.4); color: rgb(73, 1, 110);">Encuesta</h5>
                        <p style="margin-left: 5px; color: rgb(168, 168, 168); font-weight: 300;">Son completamente anónimas, por lo tanto, no se le solicitará información personal.</p>
                    </div>
                </div>
            </div>
            <!-- Contenedor de preguntas -->
            <form action="<?= $repositorio ?>php/modules/manage/survey.php" method="POST" id="content-questions" class="col-12 d-flex flex-column justify-content-center align-items-center p-5 gap-5 mt-1">
                <!-- Atencion -->
                <div id="content-questions-question-1" class="col-12 col-md-11 d-flex flex-column justify-content-center align-items-center" style="color: rgba(177, 177, 177, 1)">
                    <h3 class="position-absoulte top-0 start-0" style="color: rgba(0, 0, 0, 0.8);">Atención al cliente</h3>
                    <div id="content-questions-question-1-buttons" class="col-12 col-md-11 d-flex flex-column flex-md-row justify-content-between align-items-center position-relative p-2 gap-4 rounded">
                        <p class="d-none d-md-block position-absoulte start-0">Muy mala</p>
                        <div id="question-1" class="col-12 col-md-10 d-flex flex-row justify-content-center align-items-center gap-5 sigsm-radio">
                            <span><input type="radio" id="question-1-input" name="question-1-input" value="1"></span>
                            <span><input type="radio" id="question-1-input" name="question-1-input" value="2"></span>
                            <span><input type="radio" id="question-1-input" name="question-1-input" value="3"></span>
                            <span><input type="radio" id="question-1-input" name="question-1-input" value="4"></span>
                            <span><input type="radio" id="question-1-input" name="question-1-input" value="5"></span>
                        </div>
                        <div class="col-12 d-flex justify-content-between align-items-between d-md-none">
                            <p>Muy mala</p>
                            <p>Muy buena</p>
                        </div>
                        <p class="d-none d-md-block">Muy buena</p>
                    </div>
                </div>
                <!-- Eficiencia de respuesta -->
                <div id="content-questions-question-2" class="col-12 col-md-11 d-flex flex-column justify-content-center align-items-center" style="color: rgba(177, 177, 177, 1)">
                    <h3 class="position-absoulte top-0 start-0" style="color: rgba(0, 0, 0, 0.8);">Eficiencia en respuesta</h3>
                    <div id="content-questions-question-1-buttons" class="col-12 col-md-11 d-flex flex-column flex-md-row justify-content-between align-items-center position-relative p-2 gap-4 rounded">
                        <p class="d-none d-md-block position-absoulte start-0">Muy mala</p>
                        <div class="col-12 col-md-10 d-flex flex-row justify-content-center align-items-center gap-5 sigsm-radio">
                            <span><input type="radio" id="question-2-input" name="question-2-input" value="1"></span>
                            <span><input type="radio" id="question-2-input" name="question-2-input" value="2"></span>
                            <span><input type="radio" id="question-2-input" name="question-2-input" value="3"></span>
                            <span><input type="radio" id="question-2-input" name="question-2-input" value="4"></span>
                            <span><input type="radio" id="question-2-input" name="question-2-input" value="5"></span>
                        </div>
                        <div class="col-12 d-flex justify-content-between align-items-between d-md-none">
                            <p>Muy mala</p>
                            <p>Muy buena</p>
                        </div>
                        <p class="d-none d-md-block">Muy buena</p>
                    </div>
                </div>
                <!-- Condiciones -->
                <div id="content-questions-question-3" class="col-12 col-md-11 d-flex flex-column justify-content-center align-items-center" style="color: rgba(177, 177, 177, 1)">
                    <h3 class="position-absoulte top-0 start-0" style="color: rgba(0, 0, 0, 0.8);">Condiciones y calidad</h3>
                    <div id="content-questions-question-1-buttons" class="col-12 col-md-11 d-flex flex-column flex-md-row justify-content-between align-items-center position-relative p-2 gap-4 rounded">
                        <p class="d-none d-md-block position-absoulte start-0">Muy mala</p>
                        <div class="col-12 col-md-10 d-flex flex-row justify-content-center align-items-center gap-5 sigsm-radio">
                            <span><input type="radio" id="question-3-input" name="question-3-input" value="1"></span>
                            <span><input type="radio" id="question-3-input" name="question-3-input" value="2"></span>
                            <span><input type="radio" id="question-3-input" name="question-3-input" value="3"></span>
                            <span><input type="radio" id="question-3-input" name="question-3-input" value="4"></span>
                            <span><input type="radio" id="question-3-input" name="question-3-input" value="5"></span>
                        </div>
                        <div class="col-12 d-flex justify-content-between align-items-between d-md-none">
                            <p>Muy mala</p>
                            <p>Muy buena</p>
                        </div>
                        <p class="d-none d-md-block">Muy buena</p>
                    </div>
                </div>
                <!-- Votado -->
                <div class="col-12 d-flex flex-column flex-md-row justify-content-center align-items-center gap-3 gap-md-5">
                    <!-- Atención -->
                    <div class="d-flex flex-column justify-content-center align-items-center sigsm-shadow-y-1 p-4 sigsm-indicator">
                        <p id="answer-atencion" style="font-weight: 600; color: rgba(116, 116, 116, 0.75); font-size: 60px; margin: 0;">0</p>
                        <h5 style="font-weight: 400; color: rgba(116, 116, 116, 0.5); font-size: 16px; margin: 0;">Atención</h5>
                    </div>
                    <!-- Respuesta -->
                     <div class="d-flex flex-column justify-content-center align-items-center sigsm-shadow-y-1 p-4 sigsm-indicator">
                        <p id="answer-velocidad" style="font-weight: 600; color: rgba(116, 116, 116, 0.75); font-size: 60px; margin: 0;">0</p>
                        <h5 style="font-weight: 400; color: rgba(116, 116, 116, 0.5); font-size: 16px; margin: 0;">Respuesta</h5>
                    </div>
                    <!-- Condiciones y calidad -->
                     <div class="d-flex flex-column justify-content-center align-items-center sigsm-shadow-y-1 p-4 sigsm-indicator">
                        <p id="answer-condiciones" style="font-weight: 600; color: rgba(116, 116, 116, 0.75); font-size: 60px; margin: 0;">0</p>
                        <h5 style="font-weight: 400; color: rgba(116, 116, 116, 0.5); font-size: 16px; margin: 0;">Condiciones</h5>
                    </div>
                </div>
                <div class="col-12 d-flex flex-row justify-content-center align-items-center gap-2">
                    <button type="submit" name="accion" value="reset"  class="col-4 button-panel button-theme-lightred  rounded">  Reiniciar   </button>
                    <button type="submit" name="accion" value="submit" class="col-4 button-panel button-theme-lightblue rounded">  Subir       </button>
                </div>
            </form>
        </main>
    </div>
    <script src="<?= $styles ?>/main.js"></script>
    <script src="inputs.js"></script>
    <script src="validar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>