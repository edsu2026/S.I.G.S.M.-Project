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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body>
    <div class="container-fluid d-flex flex-column min-vh-100">
        <header class="row" style="z-index: 1">
            <div id="header-main" class="col-12 d-flex flex-column">
                <div id="header-main-options" class="col-12 d-flex flex-row justify-content-center align-items-center">
                    <div class="col-6 d-flex flex-row">
                        <span>
                            <a href="#" class="button button-link">Hospital de Clínicas</a>
                        </span>
                    </div>
                    <div class="col-6 d-flex flex-row justify-content-end align-items-center me-1">
                        <span>
                            <a href="./login/page.php" class="button button-link">Iniciar sesión</a>
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
        <main class="row main-mt d-flex flex-column justify-content-center align-items-center">
            <!-- Botones y campañas -->
            <div class="col-12 d-flex flex-column flex-md-row justify-content-center align-items-center gap-3">
                <!-- Botones -->
                <div class="col-12 col-md-6 d-flex flex-column gap-2">
                    <button class="col-12 d-flex flex-row button-card">
                        <!-- Imagen del botón -->
                        <div class="col-4" style="overflow: hidden">
                            <img src="<?= $assets ?>/img/example1.jpg" class="w-100 h-100 object-fit-cover" alt="Sección">
                        </div>
                        <!-- Contenedor del texto del botón -->
                        <div class="col-8 d-flex flex-column">
                            <h5>Documentos</h5>
                            <p>Aca se encuentran los Documentos</p>
                        </div>
                    </button>

                    <!-- Button encuestas -->
                    <button class="col-12 d-flex flex-row button-card">
                        <!-- Imagen del botón -->
                        <div class="col-4" style="overflow: hidden">
                            <img src="<?= $assets ?>/img/example1.jpg" class="w-100 h-100 object-fit-cover" alt="Sección">
                        </div>

                        <!-- Contenedor del texto del botón -->
                        <div class="col-8 d-flex flex-column">
                            <h5>Encuestas</h5>
                            <p>Aca se encuentran las Encuestas</p>
                        </div>
                    </button>
                </div>

                <!-- Campañas -->
                <div class="col-12 col-md-5 d-flex flex-row rounded p-2" style="background: radial-gradient(circle at 50%, #3F5EFB, #7A46FC)">
                    <button class="col-12" style="border: none; text-decoration: none;">
                        <div class="col-12 card card-campaign mx-auto shadow">
                            <img class="card-img-top object-fit-cover" src="<?= $assets ?>/img/campaña1.jpg" style="height: 200px; object-position: center;">
                            <div class="card-img-overlay card-overlapped" style="background-color: black; color: white;">
                                <h3 class="card-title">Campaña contra la crisis climática</h3>
                            </div>
                        </div>
                    </button>
                </div>
            </div>
            
        </main>
        <footer class="row theme-darkblue justify-content-center mt-auto">
            <!-- Añadido align-items-center y corregido el ancho de columnas si usas d-flex -->
            <div class="col-12 d-flex justify-content-center gap-4 align-items-center">
                <!-- Eliminados los col-12/col-md-3 para que Flexbox maneje el espacio de forma fluida -->
                <section>
                    <p class="mb-0">E.D.S.U.</p> <!-- mb-0 quita el margen del párrafo -->
                </section>
                <section>
                    <p class="mb-0">Hospital de Clínicas</p> <!-- mb-0 quita el margen del párrafo -->
                </section>
            </div>
            <div class="col-12 d-flex justify-content-center">
                <p class="mb-0" style="color: rgb(212, 188, 255)">© 2026 Todos los derechos reservados</p> <!-- mb-0 quita el margen del párrafo -->
            </div>
        </footer>
    </div>
    <script src="<?= $styles ?>/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>