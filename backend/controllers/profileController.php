<?php
require_once __DIR__ . '/../bd/conexion.php';

class PerfilController {
    public static function obtenerPerfil($id, $rol) {
        global $conexion;
        if ($rol === 1) {
            $sql = "SELECT nombre, apellido,telefono,direccion,correo_electronico,idArea FROM estudiante WHERE idEstudiante = $id";
        } elseif ($rol === 2) {
            $sql = "SELECT nombre, apellido,telefono,direccion,correo_electronico,idArea,descripcion,precio FROM tutor WHERE idTutor = $id";
        } else {
            return false; // rol inválido
        }

        $resultado = $conexion->query($sql);
        $usuario = $resultado->fetch();

        return $usuario ?: false;
    }

public static function editar($datos) {
    global $conexion;

    $id = $datos["id"] ?? null;
    $rol = $datos["rol"] ?? null;
    $nombre = $datos["nombre"] ?? '';
    $apellido = $datos["apellido"] ?? '';
    $direccion = $datos["direccion"] ?? '';
    $area = $datos["area"] ?? '';
    $correo = $datos["correo"] ?? '';
    $telefono = $datos["telefono"] ?? '';
    $descripcion = $datos["descripcion"] ?? '';
    $precio = $datos["precio"] ?? 0.0;
    $foto = null;

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $foto = file_get_contents($_FILES['foto']['tmp_name']);
    }

    if (!$id || !$rol) {
        error_log("Falta el ID o ROL");
        return false;
    }

    if ($rol == 1) {
        $sql = "UPDATE estudiante 
                SET nombre = :nombre,
                    apellido = :apellido,
                    correo_electronico = :correo,
                    direccion = :direccion,
                    telefono = :telefono,
                    idArea = :area";
        if ($foto !== null) {
            $sql .= ", foto = :foto";
        }
        $sql .= " WHERE idEstudiante = :id";
    } elseif ($rol == 2) {
        $sql = "UPDATE tutor 
                SET nombre = :nombre,
                    apellido = :apellido,
                    correo_electronico = :correo,
                    direccion = :direccion,
                    telefono = :telefono,
                    idArea = :area,
                    descripcion = :descripcion,
                    precio = :precio";

        if ($foto !== null) {
            $sql .= ", foto = :foto";
        }
        $sql .= " WHERE idTutor = :id";
    } else {
        error_log("ROL no reconocido: $rol");
        return false;
    }

    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':apellido', $apellido);
    $stmt->bindParam(':correo', $correo);
    $stmt->bindParam(':direccion', $direccion);
    $stmt->bindParam(':telefono', $telefono);
    $stmt->bindParam(':area', $area);

    if ($rol == 2) {
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':precio', $precio);
    }

    if ($foto !== null) {
        $stmt->bindParam(':foto', $foto, PDO::PARAM_LOB);
    }

    $stmt->bindParam(':id', $id, PDO::PARAM_INT);


    $resultado = $stmt->execute();

    if (!$resultado) {
        error_log("Error al ejecutar SQL: " . print_r($stmt->errorInfo(), true));
    }

    return $resultado;
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