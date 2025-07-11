<?php
require_once __DIR__ . '/../bd/conexion.php';

class PerfilController {
    public static function obtenerPerfil($id, $rol) {
        global $conexion;
        if ($rol === 1) {
            $sql = "SELECT nombre, apellido,telefono,direccion,correo_electronico,idArea FROM estudiante WHERE idEstudiante = $id";
        } elseif ($rol === 2) {
            $sql = "SELECT nombre, apellido FROM tutor WHERE idTutor = $id";
        } else {
            return false; // rol inválido
        }

        $resultado = $conexion->query($sql);
        $usuario = $resultado->fetch();

        return $usuario ?: false;
    }

    public static function editar($datos) {
    global $conexion;

    // Depurar datos recibidos
    error_log("== DATOS RECIBIDOS EN editar() ==");
    error_log(print_r($datos, true));

    $id = $datos["id"] ?? null;
    $rol = $datos["rol"] ?? null;
    $nombre = $datos["nombre"] ?? '';
    $apellido = $datos["apellido"] ?? '';
    $direccion = $datos["direccion"] ?? '';
    $area = $datos["area"] ?? '';
    $correo = $datos["correo"] ?? '';
    $telefono = $datos["telefono"] ?? '';

    if (!$id || !$rol) {
        error_log("Falta el ID o ROL");
        return false;
    }

    if ($rol == 1) {
        $sql = "UPDATE estudiante 
                SET nombre = '$nombre', 
                    apellido = '$apellido', 
                    correo_electronico = '$correo', 
                    direccion = '$direccion', 
                    telefono = '$telefono', 
                    idArea = '$area' 
                WHERE idEstudiante = $id";
    } elseif ($rol == 2) {
        $sql = "UPDATE tutor 
                SET nombre = '$nombre', 
                    apellido = '$apellido', 
                    correo_electronico = '$correo', 
                    direccion = '$direccion', 
                    telefono = '$telefono', 
                    idArea = '$area' 
                WHERE idTutor = $id";
    } else {
        error_log("ROL no reconocido: $rol");
        return false;
    }

    // Ver el SQL que se va a ejecutar
    error_log("SQL a ejecutar: $sql");

    $resultado = $conexion->exec($sql);

    if ($resultado === false) {
        $errorInfo = $conexion->errorInfo();
        error_log("Error SQL: " . print_r($errorInfo, true));
    }

    return $resultado !== false;
}


    public static function eliminar($id, $rol) {
        global $conexion;
        if ($rol == 1) {
            $sql = "DELETE FROM estudiante WHERE idEstudiante = $id";
        } elseif ($rol == 2) {
            $sql = "DELETE FROM tutor WHERE idTutor = $id";
        } else {
            return false;
        }

        return $conexion->exec($sql) !== false;
    }
}