<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['rol'] != 1) {
    http_response_code(403);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

echo json_encode([
    'id' => $_SESSION['id'],
    'nombre' => $_SESSION['nombre'],
    'correo' => $_SESSION['correo']
]);
