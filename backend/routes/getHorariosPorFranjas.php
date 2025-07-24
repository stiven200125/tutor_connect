<?php
require_once __DIR__ . '/../bd/conexion.php';

if (!isset($_GET['idFranja'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Falta el parámetro idFranja']);
    exit;
}

$idFranja = intval($_GET['idFranja']);

try {
    $stmt = $conexion->prepare("SELECT idHorario, horaTutoria FROM horario WHERE idFranja = :idFranja ORDER BY horaTutoria");
    $stmt->bindParam(':idFranja', $idFranja, PDO::PARAM_INT);
    $stmt->execute();
    $horas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($horas);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error en la base de datos: ' . $e->getMessage()]);
}
