<?php
    $dsn = 'mysql:host=localhost;dbname=hospital;charset=utf8mb4';
    try {
        $conexion = new PDO($dsn, "root", "", [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    } catch (PDOException $e) {
        echo "Error de conexión: " . $e->getMessage();
    }
?>