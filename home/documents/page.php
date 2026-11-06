<?php
    $repository = "../..";

    $styles     = $repository . '/styles';

    require $repository . '/php/conexion.php';
    
    $categorias = "";
    $documentos = "";

    try {
        $sqlCategorias  = "SELECT * FROM categoria WHERE activo = 1"; 
        $stmtCategorias = $conexion->prepare($sqlCategorias);
        $stmtCategorias->execute();

        $categorias     = $stmtCategorias->fetchAll();

        $sqlDocumentos  = "SELECT * FROM documento WHERE activo = 1";
        $stmtDocumentos = $conexion->prepare($sqlDocumentos);
        $stmtDocumentos->execute();

        $documentos     = $stmtDocumentos->fetchAll();
    } catch (\Throwable $th) {
        throw $th;
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
    <link rel="stylesheet" href="<?= $styles ?>/sigsm-input.css">
    <link rel="stylesheet" href="<?= $styles ?>/sigsm-indicator.css">
    <link rel="stylesheet" href="<?= $styles ?>/shadow.css">
    <style>
        .document-container {
            text-decoration:        none;
            border:                 none;
            margin-top:             10px;
            overflow:               hidden;
            border-radius:          10px;
            transition:
                background-color    .2s ease-out,
                border-radius       .2s ease-out;
        }

        .document-container:hover {
            background-color:   rgb(242, 220, 255);
            border-radius:        20px;
        }

        .document-container .document-title      {
            color:              rgb(36, 33, 38);
            font-size:            15px;
            font-weight:          700;
        }

        .document-container .document-category    {
            color: rgb(197, 192, 192);
            font-size:            12px;
        }
    </style>
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
                            <a href="../login/page.php" class="button button-link">Iniciar sesión</a>
                        </span>
                    </div>
                </div>
            </div>
        </header>

        <main class="row main-mt d-flex flex-column justify-content-center align-items-center">
            <div class="col-12 d-flex flex-column justify-content-center align-items-center h-100 gap-3">
                <!-- Barra de búsqueda -->
                <div id="content-documents-input" class="col-12 d-flex flex-row align-items-center justify-content-center gap-3">
                    <input class="col-6 sigsm-input-def" id="document-to-search-input" name="document-to-search-input" placeholder="Buscar...">
                </div>

                <hr class="col-6" style="align-self: center; margin: 0; border: 2px solid rgb(212, 202, 202);">

                <!-- Resultados (Añadimos la clase document-card y el atributo data-category) -->
                <div id="content-documents-results" class="col-12 col-md-11 d-flex flex-md-row flex-wrap justify-content-center align-items-center gap-2">
                </div>
            </div>
        </main>
    </div>
    <script>
        window.categories = <?php echo json_encode($categorias); ?>;
        window.documents  = <?php echo json_encode($documentos); ?>;
    </script>
    <script src="config.js"></script>
    <script src="<?= $styles ?>/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>