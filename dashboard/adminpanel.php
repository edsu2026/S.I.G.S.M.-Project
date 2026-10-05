<?php
    $dashboard = "./..";
    $styles = "$dashboard/styles";
    $assets = "$dashboard/assets";
    require "$dashboard/php/conexion.php";
    $sqlCategoria = "SELECT * FROM categoria WHERE activo = 1";
    $stmt = $conexion->query($sqlCategoria);
    $categoriaResult = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel</title>
    <link rel="stylesheet" href="<?= $styles ?>/colorthemes.css">
    <link rel="stylesheet" href="<?= $styles ?>/button.css">
    <link rel="stylesheet" href="<?= $styles ?>/nav.css">
    <link rel="stylesheet" href="<?= $styles ?>/main.css">
    <style>
        th, td {
            width: 50%;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body style="background-image: url('<?= $assets ?>/img/index-bg.jpg'); background-size: cover; background-position: center;">
    <div class="container min-vh-100 d-flex flex-column">
        
        <!-- Header -->
        <header class="row mini d-flex align-items-center justify-content-center theme-darkblue">
            <div class="col-12 mini d-flex justify-content-center gap-3" style="color: white;">
                <span>
                    <a href="<?= $dashboard ?>/index.html" class="button button-link">Inicio</a>
                </span>
            </div>
        </header>

        <!-- Main Content -->
        <main class="row rounded flex-grow-1" style="margin-top: 20px; margin-bottom: 20px; background-color: rgba(0, 0, 0, 0.7);">
            
            <!-- Menu Lateral -->
            <div class="d-flex flex-column col-md-3 col-sm-12 justify-content-between rounded-start" style="background-color: rgba(255, 255, 255, 0.05); padding: 20px;">
                <section class="d-flex flex-column gap-2">
                    <a href="src/gestion_cat.php" class="button-panel button-theme-blue rounded">
                        Documentos
                    </a>
                    <a href="src/gestion_enc.html" class="button-panel button-theme-blue rounded">
                        Encuestas
                    </a>
                </section>

                <hr>

                <section class="d-flex flex-column gap-2">
                    <a href="gestion_usu.html" class="button-panel button-theme-blue rounded">
                        Usuarios
                    </a>
                </section>

                <hr>

                <section class="d-flex flex-column gap-2">
                    <a href="#" class="button-panel button-theme-red rounded">
                        Log out
                    </a>
                </section>
            </div>

            <!-- Panel Principal -->
            <div class="col-lg-9 col-md-9 col-sm-12 d-flex flex-column p-4 gap-3">
                <form action="<?= $dashboard ?>/php/gestion/crear_cat.php" method="POST" class="col-12 d-flex flex-column bg-secondary form-group p-2 gap-2 rounded">
                    <input type="text" id="newCatName" name="newCatName" class="form-control" placeholder="Ingrese nombre de la nueva cat.">
                    <button type="submit" class="btn btn-primary">Crear categoria</button>
                </form>
                <!--Scroll panel-->
                <div class="col-12" style="max-height: 300px; overflow-y: auto; border: 3px solid #313131;">
                    <?php if(empty($categoriaResult)): ?>
                    <div class="d-flex flex-column align-items-center justify-content-center gap-3" style="height: 100%;">
                        <h2 class="text-white">No hay categorías registradas</h2>
                        <a href="gestion_cat.html" class="button button-theme-blue rounded">Agregar Categoría</a>
                    </div>
                    <?php else: ?>
                    <div class="accordion" id="categoriasAccordion">
                        <?php foreach($categoriaResult as $categoria): ?>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $categoria['id_categoria'] ?>" aria-expanded="false" aria-controls="panelsStayOpen-collapse<?= $categoria['id_categoria'] ?>">
                                    <?= htmlspecialchars($categoria['nombre_categoria']) ?>
                                </button>
                            </h2>
                            <div id="collapse<?= $categoria['id_categoria'] ?>" class="accordion-collapse collapse">
                                <table class="table table-bordered table-dark shadow">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nombre</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <div class="w-100 d-flex p-1" style="background-color: rgb(42, 42, 41)">
                                            <a href="src/nuevo_doc.php?cat_id=<?= $categoria['id_categoria'] ?>" class="btn btn-primary">Crear documento</a>
                                            <a href="src/gestion_cat.php?cat_id=<?= $categoria['id_categoria'] ?>" class="btn btn-secondary">Actualizar categoría</a>
                                        </div>
                                        <?php 
                                        $sqlDocumentos = "SELECT * FROM documento WHERE id_categoria = :id_categoria AND activo = true";
                                        $stmtDocumentos = $conexion->prepare($sqlDocumentos);
                                        $stmtDocumentos->bindParam(':id_categoria', $categoria['id_categoria'], PDO::PARAM_INT);
                                        $stmtDocumentos->execute();
                                        $documentosResult = $stmtDocumentos->fetchAll();
                                        if (empty($documentosResult)): ?>
                                            <tr>
                                                <td colspan="2" class="text-center">No hay documentos en esta categoría</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach($documentosResult as $documento): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($documento['id_documento']) ?></td>
                                                <td><?= htmlspecialchars($documento['nombre_documento']) ?></td>
                                                <td><a href="./src/gestion_doc.php?id=<?= $documento['id_documento'] ?>" class="button button-theme-blue rounded">Editar</a></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="row theme-darkblue justify-content-center mini mt-auto">
            <div class="col-12 d-flex justify-content-center gap-4 align-items-center">
                <section>
                    <p class="mb-0">E.D.S.U.</p>
                </section>
                <section>
                    <p class="mb-0">Hospital de Clínicas</p>
                </section>
            </div>
            <div class="col-12 d-flex justify-content-center">
                <p class="mb-0" style="color: rgb(212, 188, 255)">© 2026 Todos los derechos reservados</p>
            </div>
        </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>