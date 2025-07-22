<?php
require_once __DIR__ . '/../bd/conexion.php';

try {
    $stmt = $conexion->query("SELECT idFranja, descripcion FROM franja_horaria");
    $franjas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    header('Content-Type: application/json');
    echo json_encode($franjas);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Error al obtener las franjas: " . $e->getMessage()]);
}
