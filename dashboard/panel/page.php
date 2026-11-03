<?php
    # carpeta principal
    $repositorio = '../../';

    # subcarpetas
    $styles = $repositorio . 'styles';
    $assets = $repositorio . 'assets';
    $home = $repositorio . 'home';

    # PDO
    require $repositorio . 'php/conexion.php';

    //  Consulta para obtener todas las categorías y sus documentos asociados
    $categoriasQuery = "SELECT * FROM categoria";
    $getCategories = $conexion->prepare($categoriasQuery);
    $getCategories->execute();
    $categorias = $getCategories->fetchAll();

    //  Consulta separada para evitar filas duplicadas
    $reseñasQuery = "SELECT * FROM reseña";
    $getReseñas = $conexion->prepare($reseñasQuery);
    $getReseñas->execute();

    //  Obtenemos todas las reseñas y las almacenamos en un array
    $reseñas = $getReseñas->fetchAll();

    //  Consulta para obtener los documentos asociados a cada categoría
    $documentosQuery = "SELECT * FROM documento";
    $getDocumentos = $conexion->prepare($documentosQuery);
    $getDocumentos->execute();
    $documentos = $getDocumentos->fetchAll();
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
    <link rel="stylesheet" href="table.css">

    <style>
        .panel-unique-button         {
            color:            rgb(75, 69, 80);
            text-align:         start;
            padding:            3px 20px;
            font-weight:        600;
            font-size:          14px;
            border-radius:      7px;
            border:             none;
            background-color:   white;
            transition:
                background-color .1s ease-out;
        }

        .panel-unique-button:hover  {
            background-color:   rgb(103, 127, 249);
            color:              rgb(255, 255, 255);
        }

        .panel-unique-button:active  {
            background-color:   rgb(54, 85, 235);
        }

        .panel-unique-grpbutton       {
            border-right: 3px solid rgb(197, 196, 196);
            transition:
                border-right .1s ease-out;
        }

        .panel-unique-grpbutton:hover {
            border-right: 3px solid rgb(224, 223, 223);
        }

        .panel-unique-separator       {
            width:      80%;
            align-self: center;
            border:     1px solid rgb(183, 183, 183)
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
        <main class="row d-flex justify-content-center main-mt h-100">
            <!-- Contenedor del panel -->
            <div class="col-12 d-flex flex-column flex-md-row h-100">
                <!-- Panel lateral (buttons) -->
                <div class="col-12 col-md-3 col-lg-2 d-flex flex-column panel-unique-grpbutton h-100">
                    <!-- Opciones -->
                    <div class="col-12 d-flex flex-column">
                        <button id="option-categories" class="panel-unique-button">Categorias</button>
                        <button id="option-accounts"   class="panel-unique-button">Cuentas</button>
                    </div>
                    <hr class="panel-unique-separator">
                    <!-- Panel de registros -->
                    <div class="col-12 d-flex flex-column">
                        <button id="option-events"     class="panel-unique-button">Registros</button>
                    </div>
                    <hr class="panel-unique-separator">
                    <!-- Cuenta -->
                    <div class="col-12 d-flex flex-column">
                        <button id="option-logout"     class="panel-unique-button">Cerrar sesión</button>
                    </div>
                </div>
                <!-- Contenido principal -->
                <div class="d-flex col-12 col-md-9 col-lg-10 flex-column">
                    <!-- Categorias -->
                    <div id="content-categories" class="col-12 d-flex flex-column justify-content-center d-block">
                        <!-- Creación de categorías -->
                        <form action="<?= $repositorio ?>php/modules/manage/category.php" method="POST" id="content-categories-create" class="col-12 d-flex flex-column align-items-center gap-2" style="padding: 20px;">
                            <h5>Crear categoría</h5>
                            <input name="category-name-input" type="text" class="col-10 sigsm-input-def" placeholder="Ingrese nombre de la nueva categoría">
                            <div id="content-categories-create-options" class="col-10 d-flex flex-row-reverse">
                                <button type="submit" name="accion" value="create" class="col-3 button button-panel button-theme-lightblue rounded">Crear</button>
                            </div>
                        </form>
                        <!-- Categorias y configuracion -->
                        <div class="col-12 d-flex flex-column flex-md-row justify-content-between">
                            <!-- Contenedor de botones de selección de categorías -->
                            <div id="content-categories-category-selector" class="col-12 col-md-3 col-lg-2 d-flex flex-column align-items-center panel-unique-grpbutton d-none">
                                
                            </div>

                            <!-- Mensaje de aviso si no se selecciona una categoría -->
                            <div id="content-categories-category-unselected" class="d-flex col-12 col-md-9 align-items-center justify-content-center">
                                <h5>Seleccione una categoría o cree una</h5>
                            </div>

                            <!-- Documentos y encuestas -->
                            <div id="content-categories-category-content" class="col-12 col-md-10 d-flex flex-column align-items-center d-none" style="color: rgba(83, 83, 83, 0.5); height: 300px; overflow-y: scroll;">
                                
                                <!-- Title categoria -->
                                <h5 id="content-category-selected-name" style="margin-left: 5px;">Seleccione categoría</h5>
                                
                                <!-- Opciones categoría seleccionada -->
                                <form action="<?= $repositorio ?>php/modules/manage/category.php" method="POST" class="col-12 d-flex flex-column align-items-center p-1 gap-3">
                                    <input type="hidden" id="category-rename-id-input" name="category-id-input" value="">
                                    <input id="category-name-input" name="category-name-input" type="text" class="col-12 col-md-10 sigsm-input-def" required placeholder="Ingrese nuevo nombre">
                                    <div id="content-category-selected-options-menu" class="col-12 d-flex flex-row justify-content-center gap-4">
                                        <button value="update"      type="submit" name="accion" class="col-3 button button-panel button-theme-lightblue rounded">Actualizar</button>
                                        <button value="deactivate"  type="submit" name="accion" class="col-3 button button-panel button-theme-lightblue rounded">Desactivar</button>
                                        <button value="delete"      type="submit" name="accion" class="col-3 button button-panel button-theme-lightred rounded">Eliminar</button>
                                    </div>
                                </form>

                                <!-- Token -->
                                <div class="col-11 d-flex flex-column flex-lg-row justify-content-center align-items-center align-text-center text-break p-2 gap-5 sigsm-indicator sigsm-shadow-y-1 my-4" style="height: auto;">
                                    <h5 class="rounded p-2" style="background-color: rgb(253, 180, 180); color: rgb(253, 23, 23);">Token</h5>
                                    <!-- Aparecerá el token por el script categories.js -->
                                    <p id="content-category-selected-token" style="color: rgb(168, 168, 168); font-weight: 300;"></p>
                                </div>

                                <!-- Botón de creación de documentos -->
                                <h5 style="margin-left: 5px;">Documentos</h5>
                                <div id="content-category-selected-btn-document-create" class="col-12 d-flex flex-row my-2 justify-content-center">
                                    <!-- Aparecerá un botón de creación de documentos por el script categories.js -->
                                </div>

                                <!-- Tabla de documentos -->
                                <table id="content-category-selected-documents" class="col-10 table" style="color: rgba(0, 0, 0, 0.5);">
                                    <thead>
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Nombre</th>
                                            <th scope="col">Fecha</th>
                                            <th scope="col">Estado</th>
                                            <th>Otro<th>
                                        </tr>
                                    </thead>
                                    <tbody id="content-category-selected-documents-tablebody">
                                    </tbody>
                                </table>

                                <!-- Contenedor de los promedios de satisfacción por servicio -->
                                <div id="content-category-selected-reviews" class="col-9 d-flex flex-column justify-content-between align-items-center p-1 d-none" style="justify-self: center;">

                                    <!-- Contenedor de cards que muestran valores numéricos como la cantidad de reseñas por categoría y demás -->
                                    <div class="d-flex col-10 flex-row justify-content-center align-items-center gap-4 p-3">

                                        <!-- Contador de encuestas hechas -->
                                        <div class="d-flex flex-column justify-content-center align-items-center sigsm-shadow-y-1 p-4 sigsm-indicator" style="height: 160px; width: 160px; ">
                                            <p id="content-category-selected-points-count" style="font-weight: 600; color: rgba(116, 116, 116, 0.75); font-size: 60px; margin: 0;">0</p>
                                            <h5 style="font-weight: 400; color: rgba(116, 116, 116, 0.5); font-size: 16px; margin: 0;">Cantidad</h5>
                                        </div>

                                        <!-- Promedio general del servicio -->
                                        <div class="d-flex flex-column justify-content-center align-items-center sigsm-shadow-y-1 p-4 sigsm-indicator" style="height: 160px; width: 160px; ">
                                            <p id="content-category-selected-points-average" style="font-weight: 600; color: rgba(116, 116, 116, 0.75); font-size: 60px; margin: 0;">0</p>
                                            <h5 style="font-weight: 400; color: rgba(116, 116, 116, 0.5); font-size: 16px; margin: 0;">Promedio</h5>
                                        </div>
                                    </div>
                                    <!-- Estadísticas con barras -->
                                    <div class="d-flex col-12 flex-column justify-content-start gap-1 p-2 mb-3" style="color: rgba(0, 0, 0, 1)">

                                        <!-- Estadísticas de Atención al Cliente -->
                                        <div class="col-12">
                                            <h5>Atención</h5>
                                            <div class="col-12 progress sigsm-shadow-y-1" style="border: 1px solid rgba(255, 255, 255, 1);">
                                                <div id="review-atention-progressbar"   class="progress-bar" role="progressbar" style="width: 40%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                        
                                        <!-- Estadísticas de Tiempo -->
                                        <div class="col-12">
                                            <h5>Tiempo</h5>
                                            <div class="col-12 progress sigsm-shadow-y-1" style="border: 1px solid rgba(255, 255, 255, 1);">
                                                <div id="review-time-progressbar"       class="progress-bar" role="progressbar" style="width: 80%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>

                                        <!-- Estadísticas de Condiciones -->
                                        <div class="col-12">
                                            <h5>Condiciones</h5>
                                            <div class="col-12 progress sigsm-shadow-y-1" style="border: 1px solid rgba(255, 255, 255, 1);">
                                                <div id="review-conditions-progressbar" class="progress-bar" role="progressbar" style="width: 80%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cuentas -->
                    <div id="content-accounts" class="col-12 d-flex flex-row h-100 d-none">

                        <!-- Opciones de cuentas -->
                        <div class="d-flex col-12 flex-column align-items-start col-md-3 col-lg-2 align-self-stretch p-1 panel-unique-grpbutton">
                            <!-- Botones en order arriba -> abajo -->
                            <button class="col-12 panel-unique-button">
                                Creación
                            </button>
                            <button class="col-12 panel-unique-button">
                                Actualización
                            </button>
                            <button class="col-12 panel-unique-button">
                                Desactivación
                            </button>
                            <button class="col-12 panel-unique-button">
                                Eliminación
                            </button>
                        </div>

                        <div id="content-accounts-options-container" class="col-12 col-md-9 col-lg-10">

                            <!-- Formulario de creación de cuentas  -->
                            <!-- autocomplete desactivado para no ver información sobre otras cuentas creadas anteriormente -->
                            <form id="content-accounts-create" action=" <?= $repositorio ?>php/modules/manage/account.php" method="POST" class="col-12 d-flex flex-column align-items-center p-2 gap-3" autocomplete="off">
                                <input  type="text" name="account-email-input"       class="col-lg-8 col-md-10 col-12 sigsm-input-def" required                           placeholder="Ingrese un email">
                                <input  type="text" name="account-password-input"    class="col-lg-8 col-md-10 col-12 sigsm-input-def" required                           placeholder="Ingrese una contraseña">
                                <input              name="account-role-input"        class="col-lg-8 col-md-10 col-12 sigsm-input-def" required list="account-role-input" placeholder="ROL">
                                <datalist id="account-role-input">
                                    <option value="Administrador">
                                    <option value="Administrativo">
                                </datalist>
                                <div class="col-12 d-flex flex-row justify-content-center">
                                    <button name="accion" value="create" class="col-3 button-panel button-theme-lightblue rounded">Crear</button>
                                </div>

                                <div class="col-10 d-flex flex-column align-items-center justify-content-center sigsm-indicator sigsm-shadow-y-1 h-50 p-2">
                                    <h5 class="rounded" style="display: block; background-color: rgb(252, 147, 147); color: rgb(255, 45, 45); font-size: 16px; padding: 10px 10px; font-weight: 500;">Contraseñas</h5>
                                    <p class="text-break" style="color: rgb(169, 169, 169); font-size: 13px; font-weight: 500;">Las contraseñas deben de tener: mayúsculas y minúsculas, un mínimo de 8 carácteres</p>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Eventos -->
                    <div id="content-events" class="col-12 d-flex flex-column justify-content-center d-none">

                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Instalamos variables en la ventana para que sean globales y otros scripts puedan acceder a ellas -->
    <script>
        window.categories = <?php echo json_encode($categorias)?>;
        window.reviews    = <?php echo json_encode($reseñas)   ?>;
        window.documents  = <?php echo json_encode($documentos)?>;
    </script>
    <script src="config.js"></script>

    <script src="<?= $styles ?>/main.js"></script>
    <script src="categories.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>