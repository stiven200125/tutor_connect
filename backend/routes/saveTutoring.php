<?php
session_start();
require_once __DIR__ . '/../bd/conexion.php'; // Ajusta la ruta si es necesario

// Verificar que el usuario esté autenticado y sea estudiante
if (!isset($_SESSION['id']) || $_SESSION['rol'] != 1) {
    http_response_code(401);
    exit('No autorizado');
}

$idEstudiante = $_SESSION['id'];

// Validar que todos los campos requeridos están presentes
if (
    !isset($_POST['idTutor']) ||
    !isset($_POST['asunto']) ||
    !isset($_POST['descripcion']) ||
    !isset($_POST['fecha']) ||
    !isset($_POST['idFranja'])
) {
    http_response_code(400);
    exit('Faltan campos obligatorios');
}

// Recibir datos del formulario
$idTutor = $_POST['idTutor'];
$asunto = trim($_POST['asunto']);
$descripcion = trim($_POST['descripcion']);
$fecha = $_POST['fecha'];
$idFranja = $_POST['idFranja'];

try {
    $stmt = $conexion->prepare("
        INSERT INTO tutoria (idTutor, idEstudiante, asunto, descripcion, fecha, idFranja)
        VALUES (:idTutor, :idEstudiante, :asunto, :descripcion, :fecha, :idFranja)
    ");

    $stmt->bindParam(':idTutor', $idTutor, PDO::PARAM_INT);
    $stmt->bindParam(':idEstudiante', $idEstudiante, PDO::PARAM_INT);
    $stmt->bindParam(':asunto', $asunto, PDO::PARAM_STR);
    $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
    $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);
    $stmt->bindParam(':idFranja', $idFranja, PDO::PARAM_INT);

    $stmt->execute();

    echo 'Tutoría agendada exitosamente.';
} catch (PDOException $e) {
    http_response_code(500);
    echo 'Error al guardar la tutoría: ' . $e->getMessage();
}
