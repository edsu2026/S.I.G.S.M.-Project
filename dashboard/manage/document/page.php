<?php
    # carpeta principal
    $repositorio = '../../../';

    # subcarpetas
    $styles = $repositorio . 'styles';
    $assets = $repositorio . 'assets';
    $home = $repositorio . 'home';

    # PDO
    require $repositorio . 'php/conexion.php';

    $id_categoria = 0;
    $id_documento = 0;

    if ($_SERVER["REQUEST_METHOD"] == "GET"):
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
        <main class="row d-flex justify-content-center main-mt">
            <!-- Contenedor del panel -->
            <?php switch ($_GET['accion']):
                case 'create': ?>
                <div class="col-10 d-flex flex-column rounded align-items-center justify-content-center" style="border: 1px solid rgba(0, 0, 0, 0.3); max-height: 60%;">
                    <h5>Categoria: <?= $_GET['id_categoria'] ?></h5>
                    <form action="<?= $repositorio ?>php/modules/manage/document.php" method="POST" enctype="multipart/form-data" class="col-12 d-flex flex-column justify-content-center align-items-center gap-3">
                        
                        <input type="hidden" id="document-category-input" name="category_id" value="<?= $id_categoria ?>" required>
                        <input type="text" id="document-name-input" name="document-name-input" class="col-12 col-md-8 input-def" placeholder="Ingrese nombre del documento" required>
                        <input type="file" id="document-file-input" name="document-file-input" class="col-12 col-md-8 input-def" required>
                        
                        <button name="accion" value="create" type="submit" class="col-12 col-md-8 button-panel button-theme-lightblue rounded">Crear</button>
                    </form>
                </div>
            <?php 
                    break;
                case 'update': ?>
                    <div class="col-10 d-flex flex-column rounded align-items-center justify-content-center" style="border: 1px solid rgba(0, 0, 0, 0.3); max-height: 60%;">
                        <h5>Documento: <?= $_GET['id_documento'] ?></h5>
                        <form action="<?= $repositorio ?>php/modules/manage/document.php" method="POST" enctype="multipart/form-data" class="col-12 d-flex flex-column justify-content-center align-items-center gap-3">
                            
                            <input type="hidden" id="document-category-input" name="document_id" value="<?= $_GET['id_documento'] ?>" required>
                            <input type="text" id="document-name-input" name="document-name-input" class="col-12 col-md-8 input-def" placeholder="Ingrese nuevo nombre" required>
                            <input type="file" id="document-file-input" name="document-file-input" class="col-12 col-md-8 input-def" required>
                            
                            <div class="col-12 col-md-8 d-flex flex-column align-items-center justify-content-center gap-1">
                                <button name="accion" value="create" type="submit" class="col-12 button-panel button-theme-lightblue rounded">Actualizar</button>
                                <button name="accion" value="delete" type="submit" class="col-12 button-panel button-theme-lightred rounded">Eliminar</button>
                            </div>
                        </form>
                    </div>
            <?php 
                    break;
                default:
                    # code...
                    break;
                endswitch;
            endif
            ?>
            
        </main>
    </div>
    <script src="<?= $styles ?>/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>