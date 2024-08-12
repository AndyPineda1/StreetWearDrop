<?php
// Se incluye la clase del modelo.
require_once('../../models/data/distribuidor_data.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $distribuidor = new DistribuidorData;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null, 'fileStatus' => null);
    
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['idAdministrador'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'searchRows':
                if (!Validator::validateSearch($_POST['search'])) {
                    $result['error'] = Validator::getSearchError();
                } elseif ($result['dataset'] = $distribuidor->searchRows()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
                } else {
                    $result['error'] = 'No hay coincidencias';
                }
                break;
            case 'createRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$distribuidor->setNombre($_POST['nombreDistribuidor']) ||
                    !$distribuidor->setTelefono($_POST['telefonoDistribuidor'])
                ) {
                    $result['error'] = $distribuidor->getDataError();
                } elseif ($distribuidor->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Distribuidor creado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al crear el distribuidor';
                }
                break;
            case 'readAll':
                if ($result['dataset'] = $distribuidor->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No existen distribuidores registrados';
                }
                break;
            case 'readOne':
                if (!isset($_POST['idDistribuidor'])) {
                    $result['error'] = 'ID del distribuidor no proporcionado';
                } elseif (!$distribuidor->setId($_POST['idDistribuidor'])) {
                    $result['error'] = $distribuidor->getDataError();
                } elseif ($result['dataset'] = $distribuidor->readOne()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Distribuidor inexistente';
                }
                break;
            case 'updateRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$distribuidor->setId($_POST['idDistribuidor']) ||
                    !$distribuidor->setNombre($_POST['nombreDistribuidor']) ||
                    !$distribuidor->setTelefono($_POST['telefonoDistribuidor'])
                ) {
                    $result['error'] = $distribuidor->getDataError();
                } elseif ($distribuidor->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Distribuidor modificado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar el distribuidor';
                }
                break;
            case 'deleteRow':
                if (!isset($_POST['idDistribuidor'])) {
                    $result['error'] = 'ID del distribuidor no proporcionado';
                } elseif (!$distribuidor->setId($_POST['idDistribuidor'])) {
                    $result['error'] = $distribuidor->getDataError();
                } elseif ($distribuidor->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Distribuidor eliminado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al eliminar el distribuidor';
                }
                break;
                
            default:
                $result['error'] = 'Acción no disponible dentro de la sesión';
        }

        // Se obtiene la excepción del servidor de base de datos por si ocurrió un problema.
        $exception = Database::getException();
        if ($exception) {
            $result['exception'] = $exception;
        }

        // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
        header('Content-type: application/json; charset=utf-8');
        // Se imprime el resultado en formato JSON y se retorna al controlador.
        echo json_encode($result);
    } else {
        echo json_encode(array('error' => 'Acceso denegado'));
    }
} else {
    echo json_encode(array('error' => 'Recurso no disponible'));
}
?>
