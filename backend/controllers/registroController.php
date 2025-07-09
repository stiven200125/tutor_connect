<?php
require_once __DIR__ . '/../bd/conexion.php';

class RegistroController {
    public static function registrar($datos) {
        global $conexion;

        $rol = $datos["rol"];
        $name = $datos["nameUser"];
        $lastname = $datos["lastnameUser"];
        $email = $datos["email"];
        $password = $datos["password"];

        if ($rol === "1") {
            $sql = "INSERT INTO estudiante (
                num_identificacion, nombre, apellido, telefono, direccion,
                correo_electronico, contrasena, verificacion_edad,
                idVerificacion, idArea, idRol, foto
            ) VALUES (
                NULL, '$name', '$lastname', NULL, NULL,
                '$email', '$password', NULL,
                NULL, NULL, $rol, NULL
            )";
        } elseif ($rol === "2") {
            $sql = "INSERT INTO tutor(
                idVerificacion, idArea, idRol, idMetodologia,
                nombre, apellido, foto, telefono, descripcion, direccion,
                correo_electronico, contrasena, precio, verificacion_edad,
                num_identificacion
            ) VALUES (
                NULL, NULL, '$rol', NULL, '$name', '$lastname', NULL,
                NULL, NULL, NULL, '$email', '$password', NULL, NULL, NULL
            )";
        } else {
            return false;
        }

        $resultado = $conexion->exec($sql);
        return $resultado !== false;
    }
}