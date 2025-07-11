<?php
require_once __DIR__ . '/../bd/conexion.php';

class PerfilController {
    public static function obtenerPerfil($id, $rol) {
        global $conexion;

        if ($rol === 1) {
            $sql = "SELECT nombre, apellido FROM estudiante WHERE idEstudiante = $id";
        } elseif ($rol === 2) {
            $sql = "SELECT nombre, apellido FROM tutor WHERE idTutor = $id";
        } else {
            return false; // rol inválido
        }

        $resultado = $conexion->query($sql);
        $usuario = $resultado->fetch();

        return $usuario ?: false;
    }
}