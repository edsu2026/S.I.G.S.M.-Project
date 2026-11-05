<?php
    require "./conexion.php";
    
    $sql  = "INSERT INTO usuario (email, contraseña, rol) VALUES ('main@root', :passwd, 'administrador')";
    $processed = password_hash('HSP2026CLN@SIGSME.D.S.U.', PASSWORD_DEFAULT);

    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(":passwd", $processed, PDO::PARAM_STR);
    $stmt->execute();
?>