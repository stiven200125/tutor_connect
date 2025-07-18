<?php
session_start();
require_once __DIR__ . '/../bd/conexion.php';

// Validar si el usuario tiene sesión y es estudiante
if (!isset($_SESSION['id']) || $_SESSION['rol'] != 1) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

try {
    $idArea = isset($_GET['idArea']) ? intval($_GET['idArea']) : null;

    $sql = "SELECT t.idTutor, t.nombre, t.apellido, t.descripcion, t.precio, a.nombre_area
            FROM tutor t
            JOIN area a ON t.idArea = a.idArea";

    if ($idArea !== null) {
        $sql .= " WHERE t.idArea = :idArea";
    }

    $stmt = $conexion->prepare($sql);

    if ($idArea !== null) {
        $stmt->bindParam(':idArea', $idArea, PDO::PARAM_INT);
    }

    $stmt->execute();
    $tutores = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($tutores as &$tutor) {
        $tutor['foto_url'] = '/tutor_connect/backend/routes/getFotoUsuario.php?id=' . $tutor['idTutor'] . '&rol=2';
    }

    echo json_encode($tutores);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error en la base de datos: ' . $e->getMessage()]);
}
