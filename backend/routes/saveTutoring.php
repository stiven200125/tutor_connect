<?php
session_start();
require_once __DIR__ . '/../bd/conexion.php';

if (!isset($_SESSION['id'])) {
    http_response_code(401);
    exit('No autorizado');
}

try {
    if (isset($_POST['idTutoria'])) {
        // Modo actualización (agendar)
        $idTutoria = $_POST['idTutoria'];
        $idHorario = $_POST['idHorario'];
        $enlaceSesion = trim($_POST['linkSesion']);

        $stmt = $conexion->prepare("UPDATE tutoria 
            SET idHorario = :idHorario, enlace_sesion = :enlace 
            WHERE idTutoria = :idTutoria");
        $stmt->bindParam(':idHorario', $idHorario, PDO::PARAM_INT);
        $stmt->bindParam(':enlace', $enlaceSesion, PDO::PARAM_STR);
        $stmt->bindParam(':idTutoria', $idTutoria, PDO::PARAM_INT);
        $stmt->execute();

        echo "Tutoría Agendada con Éxito";
    } else {
        // Modo creación (cuando el estudiante agenda inicialmente)
        $idEstudiante = $_SESSION['id'];
        $idTutor = $_POST['idTutor'];
        $asunto = trim($_POST['asunto']);
        $descripcion = trim($_POST['descripcion']);
        $fecha = $_POST['fecha'];
        $idFranja = $_POST['idFranja'];

        $stmt = $conexion->prepare("INSERT INTO tutoria 
            (idEstudiante, idTutor, asunto, descripcion, fecha, idFranja) 
            VALUES (:idEstudiante, :idTutor, :asunto, :descripcion, :fecha, :idFranja)");

        $stmt->bindParam(':idEstudiante', $idEstudiante);
        $stmt->bindParam(':idTutor', $idTutor);
        $stmt->bindParam(':asunto', $asunto);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':idFranja', $idFranja);
        $stmt->execute();

        echo "Solicitud Enviada con Éxito";
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo "Error: " . $e->getMessage();
}
