<?php
session_start();
require_once __DIR__ . '/../bd/conexion.php';

if (!isset($_SESSION['id']) || !isset($_SESSION['rol'])) {
    http_response_code(401);
    exit('No autorizado');
}

$idSesion = $_SESSION['id'];
$rol = $_SESSION['rol']; // Se espera 'tutor' o 'estudiante'

try {
    // Filtrar tutorías agendadas que correspondan al usuario activo
    $query = "
        SELECT 
            t.idTutoria,
            t.fecha,
            t.asunto,
            t.descripcion,
            t.enlace_sesion,
            fr.horaInicio,
            fr.horaFin,
            est.correo AS correoEstudiante,
            tut.correo AS correoTutor,
            est.nombre AS nombreEstudiante,
            tut.nombre AS nombreTutor
        FROM tutoria t
        INNER JOIN franja_horaria fr ON t.idFranja = fr.idFranja
        INNER JOIN usuario est ON t.idEstudiante = est.idUsuario
        INNER JOIN usuario tut ON t.idTutor = tut.idUsuario
        WHERE t.enlace_sesion IS NOT NULL 
        AND t.enlace_sesion <> '' 
        AND t.idHorario IS NOT NULL 
        AND t.idHorario <> ''
    ";

    if ($rol === 'tutor') {
        $query .= " AND t.idTutor = :idSesion";
    } else {
        $query .= " AND t.idEstudiante = :idSesion";
    }

    $stmt = $conexion->prepare($query);
    $stmt->bindParam(':idSesion', $idSesion, PDO::PARAM_INT);
    $stmt->execute();

    $eventos = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $horaInicio = $row['horaInicio'];
        $fecha = $row['fecha'];
        $title = $row['asunto'] . ' - ' . ($rol === 'tutor' ? $row['nombreEstudiante'] : $row['nombreTutor']);

        $eventos[] = [
            'id' => $row['idTutoria'],
            'title' => $title,
            'start' => $fecha . 'T' . $horaInicio,
            'extendedProps' => [
                'descripcion' => $row['descripcion'],
                'correoEstudiante' => $row['correoEstudiante'],
                'correoTutor' => $row['correoTutor'],
                'horaInicio' => $row['horaInicio'],
                'horaFin' => $row['horaFin'],
                'fecha' => $fecha,
                'enlaceSesion' => $row['enlace_sesion']
            ]
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($eventos);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error en la base de datos: ' . $e->getMessage()]);
}
