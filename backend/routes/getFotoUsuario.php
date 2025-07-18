<?php
require_once __DIR__ . '/../bd/conexion.php';

if (!isset($_GET['id']) || !isset($_GET['rol'])) {
    http_response_code(400);
    exit('Parámetros faltantes: id y rol');
}

$id = intval($_GET['id']);
$rol = intval($_GET['rol']);

try {
    if ($rol === 1) {
        $stmt = $conexion->prepare("SELECT foto FROM estudiante WHERE idEstudiante = :id");
    } elseif ($rol === 2) {
        $stmt = $conexion->prepare("SELECT foto FROM tutor WHERE idTutor = :id");
    } else {
        http_response_code(400);
        exit('Rol inválido');
    }

    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $foto = $stmt->fetchColumn();

    if ($foto) {
        $finfo = finfo_open();
        $mime_type = finfo_buffer($finfo, $foto, FILEINFO_MIME_TYPE);
        finfo_close($finfo);

        header("Content-Type: $mime_type");
        echo $foto;
    } else {
        $defaultPath = __DIR__ . '/../../assets/img/img-user-default.png';
        if (file_exists($defaultPath)) {
            header("Content-Type: image/png");
            readfile($defaultPath);
        } else {
            http_response_code(404);
            echo 'Imagen por defecto no encontrada';
        }
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo "Error: " . $e->getMessage();
}
