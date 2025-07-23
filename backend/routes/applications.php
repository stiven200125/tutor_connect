<?php
session_start();
require_once __DIR__ . '/../bd/conexion.php';

if (!isset($_SESSION['id']) || $_SESSION['rol'] != 2) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

$idTutor = $_SESSION['id'];

try {
    $sql = "SELECT 
                tutoria.idTutoria,
                tutoria.asunto,
                tutoria.descripcion AS descripcionTutoria,
                tutoria.fecha,
                tutoria.idFranja,
                estudiante.nombre AS nombre_estudiante,
                estudiante.apellido AS apellido_estudiante,
                estudiante.correo_electronico AS correo_estudiante,
                tutor.correo_electronico AS correo_tutor,
                estudiante.idEstudiante
            FROM tutoria
            JOIN estudiante ON tutoria.idEstudiante = estudiante.idEstudiante
            JOIN tutor ON tutoria.idTutor = tutor.idTutor
            WHERE tutoria.idTutor = :idTutor
            AND tutoria.enlace_sesion IS NULL OR tutoria.enlace_sesion = ' '";

    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':idTutor', $idTutor, PDO::PARAM_INT);
    $stmt->execute();
    $tutorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($tutorias as &$tutoria) {
        $tutoria['nombre_estudiante'] = $tutoria['nombre_estudiante'] . ' ' . $tutoria['apellido_estudiante'];
        unset($tutoria['apellido_estudiante']); // ya no lo necesitamos

        // Foto del estudiante
        $tutoria['foto_estudiante_url'] = "/tutor_connect/backend/routes/getFotoUsuario.php?id=" . $tutoria['idEstudiante'] . "&rol=1";

        // Franja horaria legible (opcional)
        $stmtFranja = $conexion->prepare("SELECT descripcion FROM franja_horaria WHERE idFranja = :idFranja");
        $stmtFranja->bindParam(':idFranja', $tutoria['idFranja'], PDO::PARAM_INT);
        $stmtFranja->execute();
        $tutoria['descripcion'] = $stmtFranja->fetchColumn();
    }

    echo json_encode($tutorias);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error en la base de datos: ' . $e->getMessage()]);
}
