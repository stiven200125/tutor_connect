<?php
require_once __DIR__ . '/../controllers/registroController.php';
require_once __DIR__ . '/../controllers/profileController.php';

$accion = $_GET['accion'] ?? '';

switch ($accion) {
    case 'crear':
        $registroExitoso = RegistroController::registrar($_POST);
        if ($registroExitoso) {
            header("Location: ../../views/registro.html?exito=1");
        } else {
            header("Location: ../../views/registro.html?error=1");
        }
        exit();

    case 'editar':
    $exito = PerfilController::editar($_POST);
    $rol = $_POST['rol'] ?? null;

    if ($rol == 1) {
        $redirect = 'studentProfile.html';
    } elseif ($rol == 2) {
        $redirect = 'tutorProfile.html';
    } else {
        $redirect = 'login.html'; // Redirigir a login si el rol es inválido
    }

    if ($exito) {
        header("Location: ../../views/$redirect?exito=1");
    } else {
        header("Location: ../../views/$redirect?error=1");
    }
    break;


    case 'eliminar':
        $id = $_POST['id'];
        $rol = $_POST['rol'];
        $exito = PerfilController::eliminar($id, $rol);
        echo $exito ? "Cuenta eliminada" : "Error al eliminar";
        break;

    default:
        echo "Acción no válida";
        break;
}
