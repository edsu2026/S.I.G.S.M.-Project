<?php
    require "php/conexion.php";
?>
<!doctype html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Documentos</title>
        <link rel="stylesheet" href="styles/main.css">
        <link rel="stylesheet" href="styles/colorthemes.css">
        <link rel="stylesheet" href="styles/button.css">
        <link rel="stylesheet" href="styles/paddings.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    </head>
    <body style="background-image: url('assets/img/index-bg.jpg');">
        <div class="container d-flex flex-column min-vh-100">
            <header class="row mini d-flex align-items-center justify-content-center theme-darkblue">
                <div class="col-12 mini d-flex justify-content-center gap-3" style="color: white;">
                    <span>
                        <a href="index.html" class="button button-link">inicio</a>
                    </span>
                    <span>
                        <a href="encuestas.php" class="button button-link">encuestas</a>
                    </span>
                    <span>
                        <a href="preguntas.html" class="button button-link">ayuda</a>
                    </span>
                </div>
            </header>
            <main class="row frame flex-grow-1">
                <div class="col-12 align-items-center justify-content-center gap-3 p-4 rounded" style="background-color: rgba(0, 0, 0, 0.7); margin-top: 20px; margin-bottom: 20px;">
                    <?php
                    $sqlCategorias = "SELECT id_categoria, nombre_categoria FROM categoria";
                    $stmtCategorias = $conexion->prepare($sqlCategorias);
                    $stmtCategorias->execute();
                    $categorias = $stmtCategorias->fetchAll(PDO::FETCH_ASSOC);
                    if (!empty($categorias)): ?>
                    <div class="accordion" id="accordionCategorias">
                        <?php foreach ($categorias as $categoria): ?>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button style="background-color: rgba(0, 0, 0, 1); border: 1px solid rgba(255, 255, 255, 0.1); color: white;" class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $categoria['id_categoria'] ?>" aria-expanded="false" aria-controls="panelsStayOpen-collapse<?= $categoria['id_categoria'] ?>">
                                    <?= htmlspecialchars($categoria['nombre_categoria']) ?>
                                </button>
                            </h2>
                            <div id="collapse<?= $categoria['id_categoria'] ?>" class="accordion-collapse collapse" data-bs-parent="#accordionCategorias">
                                <div class="accordion-body" style="background-color: rgba(0, 0, 0, 0.01); color: white;">
                                    <table class="table table-dark table-striped table-hover table-bordered text-white">
                                        <thead>
                                            <tr>
                                                <th>Documento</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sqlDocumentos = "SELECT id_documento, nombre_documento FROM documento WHERE id_categoria = :id_categoria AND activo = true";
                                            $stmtDocumentos = $conexion->prepare($sqlDocumentos);
                                            $stmtDocumentos->bindParam(':id_categoria', $categoria['id_categoria'], PDO::PARAM_INT);
                                            $stmtDocumentos->execute();
                                            $documentos = $stmtDocumentos->fetchAll(PDO::FETCH_ASSOC);
                                            if (empty($documentos)): ?>
                                                <tr>
                                                    <td colspan="1" class="text-center">No hay documentos en esta categoría</td>
                                                </tr>
                                            <?php else: 
                                                 foreach ($documentos as $documento): ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($documento['nombre_documento']) ?></td>
                                                        <td><a href="" class="button button-theme-blue p-1 rounded">Abrir</a></td>
                                                    </tr>
                                                <?php endforeach; 
                                            endif ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                        <p>No hay categorías disponibles.</p>
                    <?php endif; ?>
                </div>
            </main>
            <footer class="row theme-darkblue justify-content-center mini">
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
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
        <script src="validar.js"></script>
    </body>
</html>