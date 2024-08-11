<?php
require_once('../../models/data/distribuidor_data.php');

if (isset($_GET['action'])) {
    session_start();
    $Distribuidor = new DistribuidorData;
    $result = array('status' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null, 'fileStatus' => null);

    if (isset($_SESSION['idAdministrador'])) {
        switch ($_GET['action']) {
            case 'searchRows':
                if (!Validator::validateSearch($_POST['search'])) {
                    $result['error'] = Validator::getSearchError();
                } elseif ($result['dataset'] = $Distribuidor->searchRows()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
                } else {
                    $result['error'] = 'No hay coincidencias';
                }
                break;
            case 'createRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$Distribuidor->setNombre($_POST['nombreDistribuidor']) or
                    !$Distribuidor->setTelefono($_POST['telefonoDistribuidor'])
                ) {
                    $result['error'] = $Distribuidor->getDataError();
                } elseif ($Distribuidor->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Distribuidor creado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al crear el distribuidor';
                }
                break;
            case 'readAll':
                if ($result['dataset'] = $Distribuidor->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No existen distribuidores registrados';
                }
                break;
            case 'readOne':
                if (!$Distribuidor->setId($_POST['idDistribuidor'])) {
                    $result['error'] = $Distribuidor->getDataError();
                } elseif ($result['dataset'] = $Distribuidor->readOne()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Distribuidor inexistente';
                }
                break;
            case 'updateRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$Distribuidor->setId($_POST['idDistribuidor']) or
                    !$Distribuidor->setNombre($_POST['nombreDistribuidor']) or
                    !$Distribuidor->setTelefono($_POST['telefonoDistribuidor'])
                ) {
                    $result['error'] = $Distribuidor->getDataError();
                } elseif ($Distribuidor->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Distribuidor modificado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar el distribuidor';
                }
                break;
            case 'deleteRow':
                if (!$Distribuidor->setId($_POST['idDistribuidor'])) {
                    $result['error'] = $Distribuidor->getDataError();
                } elseif ($Distribuidor->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Distribuidor eliminado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al eliminar el distribuidor';
                }
                break;
            default:
                $result['error'] = 'Acción no disponible dentro de la sesión';
        }

        $result['exception'] = Database::getException();
        header('Content-type: application/json; charset=utf-8');
        print(json_encode($result));
    } else {
        print(json_encode('Acceso denegado'));
    }
} else {
    print(json_encode('Recurso no disponible'));
}
