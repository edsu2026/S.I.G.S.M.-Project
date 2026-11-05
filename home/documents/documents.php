<?php
    $repository = "../..";

    require($repository . '/php/conexion.php');
    $sqlCategorias = "SELECT id_categoria, nombre_categoria FROM categoria"; // Primera consulta: obtener las categorias
    $stmtCategorias = $conexion->prepare($sqlCategorias);
    $stmtCategorias->execute();

    if($stmtCategorias->rowCount()> 0){ //verificar si trajimos datos, en el caso de que sea mayor a 0, significa que trajimos datos
    //si hay categorias las guardamos y recorremos
        $categorias = $stmtCategorias->fetchAll(PDO::FETCH_ASSOC);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= $repository ?>/styles/main.css">
    <link rel="stylesheet" href="<?= $repository ?>/styles/colorthemes.css">
    <link rel="stylesheet" href="<?= $repository ?>/styles/button.css">
    <link rel="stylesheet" href="<?= $repository ?>/styles/sigsm-indicator.css">
</head>
<body>
    <div class="container-fluid">
        <header class="row" style="z-index: 1">
            <div id="header-main" class="col-12 d-flex flex-column sigsm-shadow-y-1">
                <div id="header-main-options" class="col-12 d-flex flex-row justify-content-center align-items-center">
                    <div class="col-6 d-flex flex-row justify-content-center">
                        <span>
                            <a href="#" class="button button-link">Hospital de Clínicas</a>
                        </span>
                        <span>
                            <a href="login/page.php" class="button button-link"> Iniciar sesion </a>
                        </span>
                    </div>
                </div>
            </div>
        </header>
        <main class="row">
            <div class="accordion" id="accordionCategorias">
                <?php
                if($stmtCategorias->rowCount() > 0){
                    $categorias = $stmtCategorias->fetchAll(PDO::FETCH_ASSOC);

                    foreach($categorias as $categoria){
                        // Consultar documentos por cada categoría
                        $sqlDocumentos = "SELECT id_documento, nombre_documento FROM documento WHERE id_categoria = :id_categoria AND activo = true";
                        $stmtDocumentos = $conexion->prepare($sqlDocumentos);
                        $stmtDocumentos->bindParam(':id_categoria', $categoria['id_categoria'], PDO::PARAM_INT);
                        $stmtDocumentos->execute();
                ?>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button style="background-color: rgba(0, 0, 0, 1); border: 1px solid rgba(255, 255, 255, 0.1); color: white;" class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="false" aria-controls="panelsStayOpen-collapse1">
                            Categoria de ejemplo: Protocolos Medicos
                        </button>
                    </h2>
                    <div id="collapse1" class="accordion-collapse collapse" data-bs-parent="#accordionCategorias">
                        <div class="accordion-body" style="background-color: rgba(0,0,0,01); color: white;">
                            <table class="table table-dark table-striped table-hover table-bordered text-white">
                                <thead>
                                    <tr>
                                        <th>Documento</th>
                                        <th style="width: 100px; text-align: center;">Accion</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Guia de actuacion clinica general.pdf</td>
                                        <td class="text-center">
                                            <a href="#" class="button button-theme-blue p-1 rounded" target="_blank">Abrir</a>
                                        </td>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button style="background-color: rgba(0, 0, 0, 1); border: 1px solid rgba(255, 255, 255, 0.1); color: white;" class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="false" aria-controls="panelsStayOpen-collapse2">
                                    Categoría de Ejemplo: Formularios y Solicitudes
                                </button>
                            </h2>
                            <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#accordionCategorias">
                                <div class="accordion-body" style="background-color: rgba(0, 0, 0, 0.01); color: white;">
                                    <table class="table table-dark table-striped table-hover table-bordered text-white">
                                        <thead>
                                            <tr>
                                                <th>Documento</th>
                                                <th style="width: 100px; text-align: center;">Acción</th>
                                            </tr>
                                        </thead>
                                       <tbody>
                                            <?php
                                            if($stmtDocumentos->rowCount() > 0){
                                                $documentos = $stmtDocumentos->fetchAll(PDO::FETCH_ASSOC);
                                                foreach ($documentos as $documento){
                                            ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($documento['nombre_documento']) ?></td>
                                                        <td class="text-center">
                                                            <a href="#" class="button button-theme-blue p-1 rounded" target="_blank">Abrir</a>
                                                        </td>
                                                    </tr>
                                            <?php 
                                                }
                                            } else {
                                            ?>
                                                <tr>
                                                    <td colspan="2" class="text-center">No hay documentos en esta categoria.</td>
                                                </tr>
                                            <?php 
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                <?php 
                    }
                } else {
                    echo '<p class="text-white text-center p-3">No hay categorias disponibles.</p>';
                }
                ?>
            </div>
        </main>
    </div>
    </div>  
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>