<?php
require_once __DIR__ . '/../bd/conexion.php';

class PerfilController {

    public static function editar($datos) {
        global $conexion;

        $id = $datos['id'];
        $rol = $datos['rol'];
        $nombre = $datos['nombre'];
        $apellido = $datos['apellido'];
        $correo = $datos['correo_electronico'];

        if ($rol === "1") {
            $sql = "UPDATE estudiante 
                    SET nombre='$nombre', apellido='$apellido', correo_electronico='$correo' 
                    WHERE idEstudiante=$id";
        } elseif ($rol === "2") {
            $sql = "UPDATE tutor 
                    SET nombre='$nombre', apellido='$apellido', correo_electronico='$correo' 
                    WHERE idTutor=$id";
        } else {
            return false;
        }

        $resultado = $conexion->exec($sql);
        return $resultado !== false;
    }

    public static function eliminar($id, $rol) {
        global $conexion;

        if ($rol === "1") {
            $sql = "DELETE FROM estudiante WHERE idEstudiante=$id";
        } elseif ($rol === "2") {
            $sql = "DELETE FROM tutor WHERE idTutor=$id";
        } else {
            return false;
        }

        $resultado = $conexion->exec($sql);
        return $resultado !== false;
    }
}
