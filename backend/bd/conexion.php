<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $host = "localhost";
    $port = "";
    $user = "root";
    $password = "";
    $database = "gestion_tutorias";
    $motordb = "mysql"; 


    try{
        $dsn = "$motordb:host=$host;port=$port;dbname=$database;charset=utf8mb4";
        $conexion = new PDO($dsn, $user, $password,[
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,          // Lanzar excepciones en errores
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC      // Retornar resultados como arrays asociativos
        ]);

        

    }catch(PDOException $e){

        die ("Hubo un error para conectar con la base de datos". $e->getMessage());
    }

