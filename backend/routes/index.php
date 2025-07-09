<?php
require_once __DIR__ . '/../controllers/RegistroController.php';
require_once __DIR__ . '/../controllers/PerfilController.php';

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
        echo $exito ? "Edición exitosa" : "Error al editar";
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
