<?php
    $repositorio = "../../.."
?>

<!doctype html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Cuentas</title>
        <link rel="stylesheet" href="<?= $repositorio ?>/styles/main.css">
        <link rel="stylesheet" href="<?= $repositorio ?>/styles/colorthemes.css">
        <link rel="stylesheet" href="<?= $repositorio ?>/styles/button.css">
        <link rel="stylesheet" href="<?= $repositorio ?>/styles/paddings.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    </head>
    <body style="background-image: url('<?= $repositorio ?>/assets/img/index-bg.jpg'); background-size: cover; background-position: center;">
        <div class="container d-flex flex-column min-vh-100">
            <header class="row mini d-flex align-items-center justify-content-center theme-darkblue">
                <div class="col-12 mini d-flex justify-content-center gap-3" style="color: white;">
                    <span>
                        <a href="<?= $repositorio ?>/dashboard/adminpanel.php" class="button button-link">Volver al panel</a>
                    </span>
                </div>
            </header>
            <main class="row rounded flex-grow-1" style="margin-top: 20px; margin-bottom: 20px; background-color: rgba(0, 0, 0, 0.7);">
                <!-- Panel lateral -->
                    <div class="col-2 d-flex flex-column gap-2 rounded-start p-2" style="color: white; background-color: rgba(1, 1, 1, 0.7);;">
                        <button id="option-create" class="col-12 button button-panel button-theme-blue rounded" type="button">Crear</button>
                        <button id="option-update" class="col-12 button button-panel button-theme-blue rounded" type="button">Actualizar</button>
                        <button id="option-activate" class="col-12 button button-panel button-theme-blue rounded" type="button">Activar</button>
                        <button id="option-delete" class="col-12 button button-panel button-theme-red rounded" type="button">Eliminar</button>
                    </div>
                    <!-- Panel de contenido -->
                    <div class="col-10 p-4" style="color: white;">
                        <form id="form-create" action="<?= $repositorio?>/php/gestion_cuentas.php" method="POST" class="d-flex flex-column d-block">
                            <h4>Crear cuenta</h4>
                            <input type="text" name="inputCorreo" class="form-control mb-2" placeholder="Correo" required>
                            <input type="password" name="inputPassword" class="form-control mb-2" placeholder="Contraseña" required>
                            <select name="tipoCuenta" class="form-select mb-2" required>
                                <option value="admin">Administrador</option>
                                <option value="worker">Administrativo</option>
                                <option value="user">Usuario</option>
                            </select>
                            <button type="submit" value="createAccount" class="btn btn-primary">Crear cuenta</button>
                        </form>
                        <form id="form-update" action="<?= $repositorio?>/php/gestion_cuentas.php" method="POST" class="d-flex flex-column d-none">
                            <h4>Actualizar cuenta</h4>
                            <input type="text" name="inputCorreo" class="form-control mb-2" placeholder="Correo (opcional)">
                            <input type="password" name="inputPassword" class="form-control mb-2" placeholder="Contraseña (opcional)">
                            <select name="tipoCuenta" class="form-select mb-2" required>
                                <option value="admin">Administrador</option>
                                <option value="worker">Administrativo</option>
                                <option value="user">Usuario</option>
                            </select>
                            <button type="submit" value="updateAccount" class="btn btn-primary">Actualizar cuenta</button>
                        </form>
                        <form id="form-activate" action="<?= $repositorio?>/php/gestion_cuentas.php" method="POST" class="d-flex flex-column d-none">
                            <h4>Activación</h4>
                            <input type="number" name="inputAccountId" class="form-control mb-2" placeholder="ID de la cuenta" required>
                            <select name="activationAction" class="form-select mb-2" required>
                                <option value="activate">Activar</option>
                                <option value="deactivate">Desactivar</option>
                            </select>
                            <button type="submit" value="activationAction" class="btn btn-primary">Actualizar activación</button>
                        </form>
                        <form id="form-delete" action="<?= $repositorio?>/php/gestion_cuentas.php" method="POST" class="d-flex flex-column d-none">
                            <h4>Eliminar cuenta</h4>
                            <input type="number" name="inputAccountId" class="form-control mb-2" placeholder="ID de la cuenta" required>
                            <button type="submit" value="deleteAccount" class="btn btn-danger">Eliminar cuenta</button>
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
        <script src="config.js"></script>
        <script src="<?= $repositorio ?>/validar.js"></script>
    </body>
</html>