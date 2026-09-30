<?php
    require "./php/conexion.php";
    $sql = "SELECT * FROM documento ORDER by id_categoria ASC, id_documento DESC"; // Trae los documentos ordenados por categoria de manera alfabetica y del mas nuevo al mas viejo
    $stmt = $conexion->query($sql);
    $documentosResult = $stmt->fetchAll();
    $categoriaAgrupadas = [];
        foreach ($documentosResult as $doc) {
                $catSegura = htmlspecialchars($doc['id_categoria'], ENT_QUOTES, 'UTF-8');
                $nombSeguro = htmlspecialchars($doc['id_documento'], ENT_QUOTES, 'UTF-8');
                $rutaSegura = htmlspecialchars($doc['ruta_documento'], ENT_QUOTES, 'UTF-8');

                $categoriaAgrupadas[$catSegura][] = [
                    'nombre' => $nombSeguro,
                    'ruta' => $rutaSegura
                ];
        }

// Por las dudas guardo esto -Mateo Ducasse :
        // foreach($documentosResult as $doc) {
             //$catSegura = htmlspecialchars(
                    //$doc['categoria'], 
                     //ENT_QUOTES, 'UTF-8'
                        //);// Se utilizo ENT_QUOTES como bandera de seguridad, para que si se utilzen comillas (simples o dobles) las conviertan en texto plano, de manera de que no se rompa la web y a su vez evitar ataques, y el UTF-8 para que acepte todo tipo de caracteres
                    //$nomSeguro = htmlspecialchars(
                         //$doc['nombre'], 
                         //ENT_QUOTES, 'UTF-8'
                         //);
                             //$categoriaAgrupadas[$catSegura][] = [ //Array asociativo que guarda dentro una lista de arrays, que son las categorias previamente ordenadas en el $sql, a su vez, guarda los documentos que fueron ordenados del mas nuevo al mas viejo dentro de cada array
                                 //'nombre' => $nomSeguro
                                 //];
       //} 
    //?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentos</title>
    <link rel="stylesheet" href="src/images.css">
    <link rel="stylesheet" href="styles/colorthemes.css">
    <link rel="stylesheet" href="styles/button.css">
    <link rel="stylesheet" href="src/nav.css">
    <link rel="stylesheet" href="src/main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous"> 
</head>
<body style="background-image: url('assets/img/index-bg.jpg'); background-size: cover; background-position: center;">
    <div class="container d-flex flex-column min-vh-100">
        <header class="row mini d-flex align-items-center justify-content-center theme-darkblue">
            <div class="col-12 mini d-flex justify-content-center gap-3" style="color: white;">
                <span>
                    <a href="index.html" class="button button-link">inicio</a>
                </span>
                <span>
                    <a href="encuestas.html" class="button button-link">encuestas</a>
                </span>
                <span>
                    <a href="preguntas.html" class="button button-link">ayuda</a>
                </span>
            </div>
        </header>
        <main class="row d-flex flex-column" style="margin-bottom: 20px; margin-top: 20px;">
            <div class="col p-4 rounded" style="background-color: rgba(0, 0, 0, 0.7);"> <!--Documentos-->
                <nav class="col d-flex" mb-3>
                    <div class="col d-flex align-items-center justify-content-end"> <!--Parte derecha del header-->
                    <button class="dropdown-toggle" data-bs-toggle="dropdown" style="height: auto;">Filtros</button>
                        <ul class="dropdown-menu">
                            <li><button class="dropdown-item" type="button" id="ordenFecha">FECHA</button></li>
                            <li><button class="dropdown-item" type="button" id="ordenAZ">A-Z</button></li>
                        </ul>
             </nav>
                <div class="accordion" id="Accordion">
                    <?php foreach($categoriaResult as $categoria): ?>
                        <?php $idUnico = md5($categoria);?>
                        <div class="accordion-item">
                          <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?php echo $idUnico; ?>" aria expanded="false" aria-controls="collapse-<?php echo $idUnico; ?>" >
                                <b><?php echo $categoria; ?></b>
                            </button>    
                        </h2>  
                        <div id="collapse<?php echo $idUnico; ?>" class="accordion-collapse collapse" data-bs-parent="#Accordion">
                            <div class="accordion-body">
                                <ul class="list-group list-group-flush">
                                    <?php foreach($documentos as $doc):?> <!--Entra al cuerpo del panel, y abre una lista de bootstrap, a su vez inicia otro bucle para recorrer todos los documentos que pertenecen a esta categoria especifica-->
                                        <li class="list-group-item">
                                            <a href="<?php echo $doc ['ruta']; ?>" class="button button-source">
                                                <?php echo $doc['nombre']; ?>
                                           </a>
                                       </li>   
                                       <?php endforeach; ?>
                                    </ul>       
                         </div>
                    </div>        
                </div>  
             <?php endforeach; ?>
            </div>   
                       <!-- 
                            Esta raro esto - Mateo ducasse :
                            <button class="dropdown-toggle" data-bs-toggle="dropdown" style="height: auto;">Filtros</button>
                        <ul class="dropdown-menu">
                            <li><button class="dropdown-item" href="#">FECHA</button></li>
                            <li><button class="dropdown-item" href="#">A-Z</button></li>
                        </ul>
                        <input class="nav-input" type="text" placeholder="Ingrese nombre de documento">
                        -->
                    </div>
                </nav>
                <!-- Aca va el codigo de PHP para conectarlo y Reemplazar la lista de HTML ESTATICO a PHP Backend -->
                <div class="accordion" id="Accordion">
    <?php foreach ($categoriaAgrupadas as $categoria => $documentos): ?>

        <?php
        $idUnico = md5($categoria);
        ?>

        <div class="accordion-item categoria-item" data-categoria="<?php echo mb_strtolower($categoria, 'UTF-8'); ?>">

            <h2 class="accordion-header">
                <button 
                    class="accordion-button collapsed" 
                    type="button" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#collapse-<?php echo $idUnico; ?>" 
                    aria-expanded="false" 
                    aria-controls="collapse-<?php echo $idUnico; ?>"
                >
                    <b><?php echo $categoria; ?></b>
                </button>
            </h2>

            <div 
                id="collapse-<?php echo $idUnico; ?>" 
                class="accordion-collapse collapse" 
                data-bs-parent="#Accordion"
            >
                <div class="accordion-body">

                    <ul class="list-group list-group-flush">

                        <?php foreach ($documentos as $documento): ?>

                            <li class="list-group-item documento-item" data-nombre="<?php echo mb_strtolower($documento['nombre'], 'UTF-8'); ?>">
                                <a 
                                    href="<?php echo $documento['ruta']; ?>"
                                    <!-- href="<?php  // echo $documento['nombre']; ?>" -->
                                    class="button button-source"
                                >
                                    <?php echo $documento['nombre']; ?>
                                </a>
                            </li>

                        <?php endforeach; ?>

                    </ul>

                </div>

            </div>

        </div>
    <?php endforeach; ?>

</div>

    <div id="mensajeSinResultados" class="text-white" text-center p-4 d-none>
        <p>No se encontraron documentos</p>
    </div>
        </main>
        <footer class="row theme-darkblue justify-content-center mini mt-auto">
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>