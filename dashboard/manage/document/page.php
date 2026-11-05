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
    <link rel="stylesheet" href="<?= $styles ?>/sigsm-buttonsgrp.css">
    <link rel="stylesheet" href="<?= $styles ?>/sigsm-input.css">
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
            <div class="col-12 d-flex flex-column justify-content-center flex-md-row">
                <?php switch ($_GET['accion']):
                case 'create': 
                    if (filter_var($id_categoria, FILTER_VALIDATE_INT) == false || $id_categoria < 1) {
                        die("La categoría no existe o se ingresó información inválida");
                    };
                    ?>
                    <div class="col-12 d-flex flex-column rounded align-items-center justify-content-center" style="border: 1px solid rgba(0, 0, 0, 0.3);">
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
                case 'update':
                    //  Obtenemos el documento
                    $document_id = $_GET['id_documento'] ?? exit('Se debe de pasar un documento');
                    
                    //  Validamos
                    if (filter_var($document_id) == false || $document_id < 1) {
                        die('Se necesita ingresar la identificación (id único) de un documento');
                    }

                    //  Consulta
                    $sql = $conexion->prepare("SELECT * FROM documento WHERE id_documento = :id");
                    $sql->bindParam(":id", $document_id, PDO::PARAM_INT);
                    $sql->execute();

                    //  Validamos obtención
                    if ($sql->rowCount() < 1) {
                        die('El documento ingresado no existe');
                    }

                    //  Información del documento
                    $document_data = $sql->fetch();
                ?>
                    <div class="col-12 col-md-2 d-flex flex-column p-1 sigsm-buttonsgrp-br">
                        <h5>Opciones<h5>
                        <hr>
                        <h5>Estado</h5>
                        <form action="<?= $repositorio ?>/php/modules/manage/document.php" method="POST" class="col-12 d-flex flex-column align-items-center gap-1">
                            <input type="text" id="document-id-input" name="document-id-input"   value="<?= $document_id ?>" hidden>
                            <?php switch ($document_data['activo']) {
                                case true:  ?> <button name="accion" value="deactivate" class="col-12 col-md-10 button-theme-lightblue rounded">Desactivar</button> <?php break;
                                case false: ?> <button name="accion" value="activate"   class="col-12 col-md-10 button-theme-lightblue rounded">Activar   </button> <?php break;
                                default:    break;
                            }?>
                            <button name="accion" value="delete" class="col-12 col-md-10 button-theme-lightred rounded">Eliminar</button>
                        </form>
                    </div>
                    <div class="col-12 col-md-8 d-flex flex-column align-items-center justify-content-center rounded" style="height: 100%;">
                        <form action="<?= $repositorio ?>php/modules/manage/document.php" method="POST" enctype="multipart/form-data" class="col-12 d-flex flex-column justify-content-center align-items-center gap-3">
                            <input type="text" id="document-id-input"   name="document-id-input"   value="<?= $document_id ?>" hidden>
                            <input type="text" id="document-name-input" name="document-name-input" class="col-12 col-md-8 sigsm-input-def" placeholder="Ingrese nuevo nombre" required>
                            <input type="file" id="document-file-input" name="document-file-input" class="col-12 col-md-8 sigsm-input-def" required>
                            
                            <div class="col-12 col-md-8 d-flex flex-column align-items-center justify-content-center gap-1">
                                <button name="accion" value="update" type="submit" class="col-12 button-panel button-theme-lightblue rounded">Actualizar</button>
                            </div>
                        </form>
                    </div>
                    <div class="col-12 col-md-2 d-flex flex-column sigsm-buttonsgrp-bl p-2">
                        <h5>Propiedades</h5>
                        <hr>
                        <!-- Lista -->
                        <div class="d-flex flex-column" style="font-weight: 300">
                            <p>Documento: <?= $document_id ?></p>
                            <p>Estado:    <?php if ($document_data['activo'] == true): echo "<p class='badge bg-success col-12'>Activo</p>"; else: echo "<p class='badge bg-danger col-12'>Desactivo</p>"; endif?></p>
                            <p>Categoria: <?= $document_data['id_categoria'] ?></p>
                        </div>
                    </div>
                    <?php 
                            break;
                        default:
                            # code...
                            break;
                        endswitch;
                    endif
                    ?>
            </div>
        </main>
    </div>
    <script src="<?= $styles ?>/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>