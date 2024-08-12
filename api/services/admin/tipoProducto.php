<?php
// Se incluye la clase para trabajar con los datos de tipo de producto.
require_once('../../models/data/tipoProducto_data.php');

// Se comprueba si existe una acción a realizar.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual.
    session_start();
    // Se instancia la clase correspondiente.
    $tipoProducto = new TipoProductoData;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null);
    
    // Se verifica si existe una sesión iniciada como administrador.
    if (isset($_SESSION['idAdministrador'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'searchRows':
                if (!Validator::validateSearch($_POST['search'])) {
                    $result['error'] = Validator::getSearchError();
                } elseif ($result['dataset'] = $tipoProducto->searchRows()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
                } else {
                    $result['error'] = 'No hay coincidencias';
                }
                break;
            case 'createRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$tipoProducto->setNombre($_POST['nombreTipoProducto'])
                ) {
                    $result['error'] = $tipoProducto->getDataError();
                } elseif ($tipoProducto->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Tipo de producto creado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al crear el tipo de producto';
                }
                break;
            case 'readAll':
                if ($result['dataset'] = $tipoProducto->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No existen tipos de productos registrados';
                }
                break;
            case 'readOne':
                if (!isset($_POST['idTipoProducto'])) {
                    $result['error'] = 'ID del tipo de producto no proporcionado';
                } elseif (!$tipoProducto->setId($_POST['idTipoProducto'])) {
                    $result['error'] = $tipoProducto->getDataError();
                } elseif ($result['dataset'] = $tipoProducto->readOne()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Tipo de producto inexistente';
                }
                break;
            case 'updateRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$tipoProducto->setId($_POST['idTipoProducto']) ||
                    !$tipoProducto->setNombre($_POST['nombreTipoProducto'])
                ) {
                    $result['error'] = $tipoProducto->getDataError();
                } elseif ($tipoProducto->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Tipo de producto modificado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar el tipo de producto';
                }
                break;
            case 'deleteRow':
                if (!isset($_POST['idTipoProducto'])) {
                    $result['error'] = 'ID del tipo de producto no proporcionado';
                } elseif (!$tipoProducto->setId($_POST['idTipoProducto'])) {
                    $result['error'] = $tipoProducto->getDataError();
                } elseif ($tipoProducto->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Tipo de producto eliminado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al eliminar el tipo de producto';
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
