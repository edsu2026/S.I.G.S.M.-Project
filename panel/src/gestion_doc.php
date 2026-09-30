<?php
    $dashboard = "./../..";
    $styles = "$dashboard/styles";
    $assets = "$dashboard/assets";
//uso de PDO
    require "$dashboard/php/conexion.php";
    $id_documento = NULL;
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $_GET['id'] = $_GET['id'] ?? '';
        $id_documento = $_GET['id'];
    } else {
        echo json_encode(['success' => false, 'error' => 'Método no permitido.']);
    }
    if ($id_documento === NULL) {
        echo json_encode(['success' => false, 'error' => 'ID de documento no proporcionado.']);
    }
    $sqlDoc = "SELECT * FROM documento WHERE id_documento = :id_documento";
    $stmtDoc = $conexion->prepare($sqlDoc);
    $stmtDoc->bindParam(':id_documento', $id_documento);
    $stmtDoc->execute();
    $documento = $stmtDoc->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="<?= $styles ?>/colorthemes.css">
        <link rel="stylesheet" href="<?= $styles ?>/main.css">
        <link rel="stylesheet" href="<?= $styles ?>/button.css">
        <link rel="stylesheet" href="<?= $styles ?>/paddings.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    </head>
    <body style="background-image: url('<?= $assets ?>/img/index-bg.jpg'); background-size: cover; background-position: center;">
        <div class="container d-flex flex-column min-vh-100">
            <header class="row mini d-flex align-items-center justify-content-center theme-darkblue">
                <div class="col-12 mini d-flex justify-content-center gap-3" style="color: white;">
                    <span>
                        <a href="<?= $dashboard ?>/panel/adminpanel.php" class="button button-link">Regreso al Panel</a>
                    </span>
                </div>
            </header>
            <main class="row frame flex-grow-1">
                <div class="col-8 mx-auto h-auto d-flex flex-column align-items-center justify-content-center gap-1 p-3 rounded" style="background-color: rgba(0, 0, 0, 0.7); margin-top: 20px; margin-bottom: 20px;">
                    <div class="w-50 h-auto d-flex flex-column p-2" style="background-color: rgba(255, 255, 255, 1); border-radius: 10px; padding: 20px;">
                        <!--Doc info-->
                        <h5>ID: <?= htmlspecialchars($documento['id_documento']) ?></h5>
                        <p>Nombre: <?= htmlspecialchars($documento['nombre_documento']) ?></p>
                    </div>
                    <form action="<?= $dashboard ?>/php/gestion/gestion_doc.php" method="POST" enctype="multipart/form-data" class="w-auto h-auto d-flex flex-column align-items-center justify-content-center gap-3 p-2 mt-2">
                        <input id="inputCatId" name="inputDocId" type="hidden" value=<?= $documento['id_documento'] ?>>
                        <input id="inputNombre" name="inputNombre" type="text" class="form-control w-100" placeholder="Ingrese nuevo nombre" aria-label="doc-nombre">
                        <button type="submit" name="action" value="actDoc" class="w-100 btn btn-primary">Actualizar</button>
                        <button type="submit" name="action" value="bajaDoc" class="w-100 btn btn-primary">Eliminar</button>
                    </form>
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