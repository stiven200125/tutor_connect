<?php
session_start();
require_once __DIR__ . '/../bd/conexion.php';

if (!isset($_SESSION['id']) || !isset($_SESSION['rol'])) {
    http_response_code(401);
    exit('No autorizado');
}

$id = $_SESSION['id'];
$rol = $_SESSION['rol'];

try {
    if ($rol == 1) {
        $stmt = $conexion->prepare("SELECT foto FROM estudiante WHERE idEstudiante = :id");
    } elseif ($rol == 2) {
        $stmt = $conexion->prepare("SELECT foto FROM tutor WHERE idTutor = :id");
    } else {
        http_response_code(400);
        exit('Rol inválido');
    }

    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $foto = $stmt->fetchColumn();

    if ($foto) {
        // Detectar tipo de imagen (suponiendo que guardas .jpg o .png únicamente)
        $finfo = finfo_open();
        $mime_type = finfo_buffer($finfo, $foto, FILEINFO_MIME_TYPE);
        finfo_close($finfo);

        header("Content-Type: $mime_type");
        echo $foto;
    } else {
        // Imagen por defecto
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
    echo "Error al obtener la foto: " . $e->getMessage();
}
