<?php
    $dashboard = "./../..";
    $styles = "$dashboard/styles";
    $assets = "$dashboard/assets";

    require "$dashboard/php/conexion.php";

    // 1. Validamos que el método sea GET obligatoriamente
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Método no permitido.']);
        exit; // Detiene la ejecución por completo
    }

    // 2. Validamos que 'cat_id' exista en la URL y no esté vacío
    if (!isset($_GET['cat_id']) || empty(trim($_GET['cat_id']))) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'ID de categoría no proporcionada o vacía.']);
        exit; // Detiene la ejecución por completo
    }

    $id_cat = $_GET['cat_id'];

    // 3. Buscamos en la base de datos
    $sqlCat = "SELECT * FROM categoria WHERE id_categoria = :id_cat";
    $stmtCat = $conexion->prepare($sqlCat);
    $stmtCat->bindParam(':id_cat', $id_cat);
    $stmtCat->execute();
    $cat = $stmtCat->fetch(PDO::FETCH_ASSOC);

    // 4. Si la categoría no existe en la BD, también detenemos
    if (!$cat) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'La categoría especificada no existe.']);
        exit;
    }
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
                        <h5>ID categoria: <?= htmlspecialchars($cat['id_categoria']) ?></h5>
                        <p>Nombre de la categoria: <?= htmlspecialchars($cat['nombre_categoria']) ?></p>
                    </div>
                    <form action="<?= $dashboard ?>/php/gestion/gestion_cat.php" method="POST" enctype="multipart/form-data" class="w-auto h-auto d-flex flex-column align-items-center justify-content-center gap-3 p-2 mt-2">
                        <input id="inputCatId" name="inputCatId" type="hidden" value=<?= $cat['id_categoria'] ?>>
                        <input id="inputNombre" name="inputNombre" type="text" class="form-control w-100" placeholder="Ingrese nuevo nombre" aria-label="cat-nombre">
                        <button type="submit" name="action" value="actCat" class="w-100 btn btn-primary">Actualizar</button>
                        <button type="submit" name="action" value="bajaCat" class="w-100 btn btn-primary">Eliminar</button>
                    </form>
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
    </body>
</html>