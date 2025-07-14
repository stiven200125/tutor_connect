<?php
session_start();

// Verificar si el usuario tiene sesión activa
if (!isset($_SESSION['id'])) {
    http_response_code(401); // No autorizado
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

require_once __DIR__ . '/../bd/conexion.php';

// Consulta las áreas o categorías desde la tabla correspondiente
$sql = "SELECT idArea, nombre_area FROM area";  
$resultado = $conexion->query($sql);
$categorias = $resultado->fetchAll();

echo json_encode($categorias);
