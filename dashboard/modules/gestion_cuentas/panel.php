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
        <link rel="stylesheet" href="<?= $repositorio ?>/styles/nav.css">
        <style>
            .button-search-suggest {
                background-color: rgba(255, 255, 255, 0);
                border: none;
                border-radius: 10px;
                color: rgba(0, 0, 0, 0.7);
            }

            .button-search-suggest:hover {
                background-color: rgb(214, 212, 212);
            }
        </style>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    </head>
    <body style="background-image: url('<?= $repositorio ?>/assets/img/index-bg.jpg'); background-size: cover; background-position: center;">
        <div class="container d-flex flex-column min-vh-100">
            <header class="row mini d-flex align-items-center justify-content-center theme-darkblue">
                <div class="col-12 mini d-flex justify-content-center gap-3" style="color: white;">
                    <span>
                        <a href="<?= $repositorio ?>/dashboard/modules/admin/panel.php" class="button button-link">Volver al panel</a>
                    </span>
                </div>
            </header>
            <main class="row rounded flex-grow-1" style="margin-top: 20px; margin-bottom: 20px; background-color: rgba(0, 0, 0, 0.7);">
                    <!-- Panel lateral -->
                    <div class="col-lg-2 col-sm-12 d-flex flex-column gap-2 rounded-start p-2" style="color: white; background-color: rgba(1, 1, 1, 0.7);;">
                        <button id="option-create" class="col-12 button button-panel button-theme-blue rounded" type="button">Crear</button>
                        <hr>
                        <button id="option-update" class="col-12 button button-panel button-theme-blue rounded" type="button">Actualizar</button>
                        <button id="option-activate" class="col-12 button button-panel button-theme-blue rounded" type="button">Activar</button>
                        <button id="option-delete" class="col-12 button button-panel button-theme-red rounded" type="button">Eliminar</button>
                    </div>
                    <!-- Panel de contenido -->
                    <div class="col-10 d-flex flex-column justify-content-center align-content-center p-4 gap-3" style="color: white;">
                        
                        <!-- Seccion Superior (nav): Input que ocupa todo el ancho superior -->
                        <nav class="col-12 d-flex flex-column gap-3 w-100 align-items-center" style="position: relative;">
                            <!-- Input -->
                            <div id="inputAccountId" class="w-100 d-flex flex-row justify-content-between align-items-center nav-input-container rounded">
                                <span class="col-1 rounded-start d-flex flex-column-reverse p-2 align-items-end" style="background-color: rgb(20, 20, 20);">
                                    <img style="width: 13px; height: 13px" src="<?=$repositorio?>/assets/img/search-icon.png">
                                </span>
                                <input type="text" name="search-input" placeholder="Buscar..." class="col-11">
                            </div>
                            <div id="search-suggestions-table" class="col-12 d-flex flex-column py-2 px-1 rounded align-items-center d-none" style="position: absolute; top: 110%; z-index: 10; background-color: rgb(255, 255, 255);">
                                <button class="w-100 d-flex flex-row button-search-suggest gap-4 align-items-center">
                                    <span class="d-flex pe-2 py-1" style="border-right: 2px solid rgb(42, 63, 255);">
                                        <img style="max-width: 10px; max-height: 10px" src="<?=$repositorio?>/assets/img/search-suggest-icon.png">
                                    </span>
                                    <span class="d-flex flex-row gap-2 align-items-center" style="font-weight: 500">
                                        <span>1</span>
                                        <span>email@gmail.com</span>
                                        <span class="text-dark">paciente</span>
                                        <span><b>estado: </b>activo</span>
                                    </span>
                                </button>
                            </div>
                        </nav>

                        <!-- Seccion Inferior: Card y Formularios alineados horizontalmente -->
                        <div class="d-flex flex-column gap-3 w-100 align-items-start">
                            
                            <!-- Card de información de la cuenta -->
                            <div class="col-12 d-flex ps-4 p-3 flex-column rounded gap-0" style="background-color: rgba(49, 49, 49, 0.3); color: white; border-left: 5px solid rgb(255, 15, 15)">
                                <!--Información principal-->
                                <span style="font-weight: 700">lugardelcorreo@gmail.com</span>
                                <hr>
                                <!--Detalles-->
                                <div class="d-flex flex-row gap-3" style="font-weight: 500; color: rgb(99, 99, 99)">
                                    <span>ID: #1</span>
                                    <span>Administrador</span>
                                    <span>Estado: Activo</span>
                                </div>
                            </div>

                            <!-- Contenedor de Formularios -->
                            <div class="col-12 d-flex flex-column justify-content-center">
                                <form id="form-create" action="<?= $repositorio?>/php/gestion_cuentas.php" method="POST" class="col-12 d-flex flex-column d-block">
                                    <h4>Crear cuenta</h4>
                                    <input type="text" name="inputCorreo" class="form-control mb-2" placeholder="Correo" required>
                                    <input type="password" name="inputPassword" class="form-control mb-2" placeholder="Contraseña" required>
                                    <select name="tipoCuenta" class="form-select mb-2" required>
                                        <option value="admin">Administrador</option>
                                        <option value="worker">Administrativo</option>
                                        <option value="user">Paciente</option>
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
                                        <option value="user">Paciente</option>
                                    </select>
                                    <button type="submit" value="updateAccount" class="btn btn-primary">Actualizar cuenta</button>
                                </form>

                                <form id="form-activate" action="<?= $repositorio?>/php/gestion_cuentas.php" method="POST" class="d-flex flex-column d-none">
                                    <h4>Estado</h4>
                                    <select name="activationAction" class="form-select mb-2" required>
                                        <option value="activate">Activar</option>
                                        <option value="deactivate">Desactivar</option>
                                    </select>
                                    <button type="submit" value="activationAction" class="btn btn-primary">Actualizar activación</button>
                                </form>

                                <form id="form-delete" action="<?= $repositorio?>/php/gestion_cuentas.php" method="POST" class="d-flex flex-column d-none">
                                    <button type="submit" value="deleteAccount" class="btn btn-danger">Eliminar cuenta</button>
                                </form>
                            </div>

                        </div>
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
        <script>
            const inputAccountId = document.getElementById('inputAccountId');
            const searchSuggestionsTable = document.getElementById('search-suggestions-table');
            inputAccountId.addEventListener('focusin', () => {
                console.log(`Input de ID de cuenta enfocado: ${inputAccountId.value}`);
                searchSuggestionsTable.classList.remove('d-none');
            })

            inputAccountId.addEventListener('focusout', () => {
                console.log(`Input de ID de cuenta desenfocado: ${inputAccountId.value}`);
                setTimeout(() => {
                    searchSuggestionsTable.classList.add('d-none');
                }, 200); // Retardo para permitir que el usuario haga clic en una sugerencia
            })
        </script>
    </body>
</html>