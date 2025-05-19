<?php
    $host = "localhost";
    $dbname = "acortador_urls";
    $username = "root";
    $password = "";

    // Conexión a la base de datos
    // Cambia los valores de host, dbname, username y password según tu configuración
    try{
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }catch(PDOException $e){
        die("Error de conexión: " . $e->getMessage());
    }
?>