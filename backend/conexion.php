<?php
    /* $nombre = $_POST["nombre"];
    $contrasena = $_POST["contrasena"];

    if ($nombre == "juliana"){
        echo "Inicio de sesión exitoso";
    }else{
        echo "Inicio de sesión Invalido";
    } */
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

$accion = $_GET['accion'] ?? '';

if($accion === 'crear'){
    
    $numId = NULL;
    $nameUser = $_POST["nameUser"]; 
    $lastnameUser = $_POST["lastnameUser"];
    $phone = NULL;
    $address = NULL;
    $email = $_POST["email"];
    $password = $_POST["password"];
    $age = NULL;
    $idVerification = NULL;
    $idArea = NULL;
    $idMetodologia = NULL;
    $rol = $_POST["rol"];
    $foto = NULL;
    $description = NULL;
    $price = NULL;

    if($rol === "1"){
        $sql = "INSERT INTO estudiante (
            num_identificacion, nombre, apellido, telefono, direccion,
            correo_electronico, contrasena, verificacion_edad,
            idVerificacion, idArea, idRol, foto
        ) VALUES (
            NULL, '$nameUser', '$lastnameUser', NULL, NULL,
            '$email', '$password', NULL,
            NULL, NULL, $rol, NULL
        )";

        $resultado = $conexion->exec($sql);
        if($resultado !== false){
            header("Location: ../views/registro.html?exito=1");
            exit();
        } else {
            header("Location: ../views/registro.html?error=1");
            exit();
        }
    } elseif($rol === "2"){
        $sql = "INSERT INTO tutor(
            idVerificacion, idArea, idRol, idMetodologia,
            nombre, apellido, foto, telefono, descripcion, direccion,
            correo_electronico, contrasena, precio, verificacion_edad,
            num_identificacion	
        )VALUES 
            (NULL, NULL, '$rol', NULL, '$nameUser', '$lastnameUser', NULL, 
            NULL, NULL, NULL, '$email' ,'$password' ,NULL ,NULL ,NULL 
        )";

        $resultado = $conexion->exec($sql);
        if($resultado !== false){
            header("Location: ../views/registro.html?exito=1");
            exit();
            
        } else {
            header("Location: ../views/registro.html?error=1");
            exit();
        }
    } else {
        echo "No se pudo agregar"; 
    }
}
?>