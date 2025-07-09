<?php
require_once __DIR__ . '/../bd/conexion.php';
session_start();

$correo = $_POST['correo'];
$contrasena = $_POST['contrasena'];

// Buscar en estudiante
$sqlEstudiante = "SELECT * FROM estudiante WHERE correo_electronico = '$correo' AND contrasena = '$contrasena'";
$resultadoEstudiante = $conexion->query($sqlEstudiante);
$estudiante = $resultadoEstudiante->fetch();

if ($estudiante) {
    $_SESSION['id'] = $estudiante['idEstudiante'];
    $_SESSION['rol'] = 1;
    $_SESSION['nombre'] = $estudiante['nombre'];
    echo "Login exitoso como estudiante";
    exit();
}

// Buscar en tutor
$sqlTutor = "SELECT * FROM tutor WHERE correo_electronico = '$correo' AND contrasena = '$contrasena'";
$resultadoTutor = $conexion->query($sqlTutor);
$tutor = $resultadoTutor->fetch();

if ($tutor) {
    $_SESSION['id'] = $tutor['idTutor'];
    $_SESSION['rol'] = 2;
    $_SESSION['nombre'] = $tutor['nombre'];
    echo "Login exitoso como tutor";
    exit();
}

echo "Correo o contraseña incorrectos";
