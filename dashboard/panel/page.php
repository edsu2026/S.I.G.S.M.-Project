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
    <link rel="stylesheet" href="table.css">
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
        <main class="row d-flex justify-content-center main-mt">
            <!-- Contenedor del panel -->
            <div class="col-12 d-flex flex-column flex-md-row">
                <!-- Panel lateral (buttons) -->
                <div class="col-12 col-md-2 d-flex flex-column" style="border: 1px solid rgba(0, 0, 0, 0.3);">
                    <!-- Opciones -->
                    <div class="col-12 grp-buttons">
                        <button id="option-categories">Categorias</button>
                        <button id="option-accounts">Cuentas</button>
                    </div>
                    <hr>
                    <!-- Panel de registros -->
                    <div class="col-12 grp-buttons">
                        <button id="option-events">Eventos</button>
                    </div>
                    <hr>
                    <!-- Cuenta -->
                    <div class="col-12 grp-buttons">
                        <button id="option-logout">Cerrar sesión</button>
                    </div>
                </div>
                <!-- Contenido principal -->
                <div class="d-flex col-12 col-md-10 flex-column" style="border: 1px solid rgba(89, 89, 89, 0.3);">
                    <!-- Categorias -->
                    <div id="content-categories" class="col-12 d-flex flex-column justify-content-center d-block">
                        <!-- Creación de categorías -->
                        <form action="<?= $repositorio ?>php/modules/manage/category.php" method="POST" id="content-categories-create" class="col-12 d-flex flex-column align-items-center gap-2" style="padding: 20px; border: 1px solid rgba(0, 0, 0, 0.3);">
                            <h5>Crear categoría</h5>
                            <input name="category-name-input" type="text" class="col-10 input-def" placeholder="Ingrese nombre de la nueva categoría" style="height: 30px">
                            <div id="content-categories-create-options" class="col-10 d-flex flex-row-reverse">
                                <button type="submit" name="accion" value="create" class="col-3 button button-panel button-theme-lightblue rounded">Crear</button>
                            </div>
                        </form>
                        <!-- Categorias y configuracion -->
                        <div class="col-12 d-flex flex-column flex-md-row justify-content-md-between">
                            <!-- Contenedor de botones de selección de categorías -->
                            <div id="content-categories-category-selector" class="col-12 col-md-3 grp-buttons" style="border: 1px solid rgba(0, 0, 0, 0.3);">
                                
                            </div>
                            <div id="content-categories-category-unselected" class="d-flex col-12 col-md-9 align-items-center justify-content-center">
                                <h5>Seleccione una categoría o cree una</h5>
                            </div>
                            <!-- Documentos y encuestas -->
                            <div id="content-categories-category-content" class="col-12 col-md-9 d-none" style="border: 1px solid rgba(0, 0, 0, 0.3); color: rgba(0, 0, 0, 0.5);">
                                <!-- Title categoria -->
                                <h5 id="content-category-selected-name" style="margin-left: 5px;">Seleccione categoría</h5>
                                <!-- Opciones cat seleccionada -->
                                <form action="<?= $repositorio ?>php/modules/manage/category.php" method="POST" class="col-12 d-flex flex-column justify-content-start p-1 gap-1">
                                    <input type="hidden" id="category-rename-id-input" name="category-id-input" value="">
                                    <input id="category-name-input" name="category-name-input" type="text" placeholder="Ingrese nuevo nombre" class="col-12 input-def" required>
                                    <div id="content-category-selected-options-menu" class="col-12 d-flex flex-row justify-content-center gap-2">
                                        <button value="update"      type="submit" name="accion" class="col-3 button button-panel button-theme-lightblue rounded">Actualizar</button>
                                        <button value="deactivate"  type="submit" name="accion" class="col-3 button button-panel button-theme-lightblue rounded">Desactivar</button>
                                        <button value="delete"      type="submit" name="accion" class="col-3 button button-panel button-theme-lightred rounded">Eliminar</button>
                                    </div>
                                </form>

                                <h5 style="margin-left: 5px;">Documentos</h5>
                                <div id="content-category-selected-btn-document-create" class="col-12 d-flex flex-row my-2 justify-content-center">
                                    
                                </div>
                                <table id="content-category-selected-documents" class="table" style="border: 1px solid rgba(0, 0, 0, 0.3); color: rgba(0, 0, 0, 0.5); width: 100%;">
                                    <thead>
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Nombre</th>
                                            <th scope="col">Fecha</th>
                                            <th scope="col">Estado</th>
                                            <th scope="col">Otro<th>
                                        </tr>
                                    </thead>
                                    <tbody id="content-category-selected-documents-tablebody">
                                    </tbody>
                                </table>

                                <!-- Contenedor de los promedios de satisfacción por servicio -->
                                <div class="col-9 d-flex flex-column flex-lg-row justify-content-between p-1" style="justify-self: center;">

                                    <!-- Contenedor de cards que muestran valores numéricos como la cantidad de reseñas por categoría y demás -->
                                    <div class="d-flex col-12 flex-row flex-lg-column justify-content-between align-items-center col-lg-4 flex-lg-row gap-4 p-3">

                                        <!-- Contador de encuestas hechas -->
                                        <div class="d-flex flex-column align-items-center justify-content-center sigsm-shadow-y-1" style="height: 150px; width: 150px; border: 1px solid rgba(0, 0, 0, 0.3); color: black; border-radius: 50px;">
                                            <h5 style="font-size: 70px; color: rgba(0, 0, 0, 0.4);">371</h5>
                                            <p class="fw-medium" style="font-size: 10px;">TOTAL</p>
                                        </div>

                                        <!-- Promedio general del servicio -->
                                        <div class="d-flex flex-column align-items-center justify-content-center sigsm-shadow-y-1" style="height: 150px; width: 150px; border: 1px solid rgba(0, 0, 0, 0.3); color: black; border-radius: 50px;">
                                            <h5 style="font-size: 70px; color: rgba(0, 0, 0, 0.4);">9.2</h5>
                                            <p class="fw-medium" style="font-size: 10px;">PROMEDIO</p>
                                        </div>
                                    </div>
                                    <div class="d-flex col-12 flex-column col-lg-8 justify-content-between p-4" style="color: rgba(0, 0, 0, 1)">
                                        <h5>Atención</h5>
                                        <div class="col-12 progress sigsm-shadow-y-1" style="border: 1px solid rgba(255, 255, 255, 1);">
                                            <div class="progress-bar" role="progressbar" style="width: 80%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <h5>Tiempo</h5>
                                        <div class="col-12 progress sigsm-shadow-y-1" style="border: 1px solid rgba(255, 255, 255, 1);">
                                            <div class="progress-bar" role="progressbar" style="width: 80%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <h5>Profesionalismo</h5>
                                        <div class="col-12 progress sigsm-shadow-y-1" style="border: 1px solid rgba(255, 255, 255, 1);">
                                            <div class="progress-bar" role="progressbar" style="width: 80%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <h5>Condiciones</h5>
                                        <div class="col-12 progress sigsm-shadow-y-1" style="border: 1px solid rgba(255, 255, 255, 1);">
                                            <div class="progress-bar" role="progressbar" style="width: 80%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cuentas -->
                    <div id="content-accounts" class="col-12 d-flex flex-column justify-content-center d-none">

                    </div>

                    <!-- Eventos -->
                    <div id="content-events" class="col-12 d-flex flex-column justify-content-center d-none">

                    </div>
                </div>
            </div>
        </main>
    </div>
    <script>
        window.categories = <?php echo json_encode($categorias)?>
    </script>
    <script src="<?= $styles ?>/main.js"></script>
    <script src="config.js"></script>
    <script src="categories.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>