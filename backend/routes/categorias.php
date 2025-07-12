<?php
require_once __DIR__ . '/../bd/conexion.php';

// Consulta las áreas o categorías desde la tabla correspondiente
$sql = "SELECT idArea, nombre FROM area";  
$resultado = $conexion->query($sql);

$categorias = $resultado->fetchAll();

echo json_encode($categorias);
