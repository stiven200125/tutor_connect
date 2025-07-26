<?php
session_start();
require_once __DIR__ . '/../bd/conexion.php';

if (!isset($_SESSION['id']) || !isset($_SESSION['rol'])) {
    http_response_code(401);
    exit('No autorizado');
}

$idSesion = $_SESSION['id'];
$rol = $_SESSION['rol']; 

try {
    $query = "
        SELECT 
            t.idTutoria,
            t.fecha,
            t.asunto,
            t.descripcion,
            t.enlace_sesion,
            fr.descripcion AS franjaDescripcion,
            h.horaTutoria,
            est.correo_electronico AS correoEstudiante,
            tut.correo_electronico AS correoTutor,
            CONCAT(est.nombre, ' ', est.apellido) AS nombreEstudiante,
            CONCAT(tut.nombre, ' ', tut.apellido) AS nombreTutor
        FROM tutoria t
        INNER JOIN franja_horaria fr ON t.idFranja = fr.idFranja
        INNER JOIN horario h ON t.idHorario = h.idHorario
        INNER JOIN estudiante est ON t.idEstudiante = est.idEstudiante
        INNER JOIN tutor tut ON t.idTutor = tut.idTutor
        WHERE t.enlace_sesion IS NOT NULL 
        AND t.enlace_sesion <> '' 
        AND t.idHorario IS NOT NULL 
        AND t.idHorario <> ''
    ";

    if ((int)$rol === 2) {
        $query .= " AND t.idTutor = :idSesion";
    } elseif ((int)$rol === 1) {
        $query .= " AND t.idEstudiante = :idSesion";
    } else {
        http_response_code(403);
        exit('Rol no reconocido');
    }

    $stmt = $conexion->prepare($query);
    $stmt->bindParam(':idSesion', $idSesion, PDO::PARAM_INT);
    $stmt->execute();

    $eventos = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $fecha = $row['fecha'];
        $hora = $row['horaTutoria'];
        $title = $row['asunto'] . ' - ' . ((int)$rol === 2 ? $row['nombreEstudiante'] : $row['nombreTutor']);

        $eventos[] = [
            'id' => $row['idTutoria'],
            'title' => $title,
            'start' => $fecha . 'T' . $hora,
            'classNames' => ['evento-tutoria'],
            'extendedProps' => [
                'descripcion' => $row['descripcion'],
                'correoEstudiante' => $row['correoEstudiante'],
                'correoTutor' => $row['correoTutor'],
                'fecha' => $fecha,
                'horaTutoria' => $hora,
                'franja' => $row['franjaDescripcion'],
                'enlaceSesion' => $row['enlace_sesion']
            ]
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($eventos);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error en la base de datos']);
}
