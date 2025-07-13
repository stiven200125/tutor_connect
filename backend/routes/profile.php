<?php
session_start();
error_log("SESSION: " . print_r($_SESSION, true));
require_once __DIR__ . '/../controllers/profileController.php';

if (!isset($_SESSION['id']) || !isset($_SESSION['rol'])) {
    echo json_encode(["error" => "No autenticado"]);
    exit();
}

$id = $_SESSION['id'];
$rol = $_SESSION['rol'];

$usuario = PerfilController::obtenerPerfil($id, $rol);

if ($usuario) {
    $response = [
    "id" => $id,
    "nombre" => $usuario['nombre'],
    "apellido" => $usuario['apellido'],
    "direccion" => $usuario['direccion'],
    "telefono" => $usuario['telefono'],
    "correo_electronico" => $usuario['correo_electronico'],
    "idArea" => $usuario['idArea'],
    "rol" => $rol
];

if ($rol === 2) {
    $response["descripcion"] = $usuario['descripcion'];
    $response["precio"] = $usuario['precio'];
}

echo json_encode($response);

} else {
    echo json_encode(["error" => "Usuario no encontrado"]);
}
