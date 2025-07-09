<?php
session_start();
require_once __DIR__ . '/../controllers/LoginController.php';

$correo = $_POST['correo'] ?? '';
$contrasena = $_POST['contrasena'] ?? '';

$usuario = LoginController::login($correo, $contrasena);

if ($usuario) {
    $_SESSION['id'] = $usuario['id'];
    $_SESSION['rol'] = $usuario['rol'];
    $_SESSION['nombre'] = $usuario['nombre'];

    echo "Inicio de sesión exitoso";
} else {
    echo "Correo o contraseña incorrectos";
}
