<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    $host = "127.0.0.1";
    $port = "";
    $user = "root";
    $password = "";
    $database = "gestion_tutorias";
    $motordb = "mysql"; 


    try{
        $dsn = "$motordb:host=$host;port=$port;dbname=$database;charset=utf8mb4";
        $conexion = new PDO($dsn, $user, $password,[
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,          
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC     
        ]);

        

    }catch(PDOException $e){

        die ("Hubo un error para conectar con la base de datos". $e->getMessage());
    }

