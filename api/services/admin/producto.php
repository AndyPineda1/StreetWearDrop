<?php
// Se incluye la clase del modelo.
require_once('../../models/data/producto_data.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    
    // Se instancia la clase correspondiente.
    $producto = new ProductoData;

    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array(
        'status' => 0,
        'message' => null,
        'dataset' => null,
        'error' => null,
        'exception' => null,
        'fileStatus' => null
    );

    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['idAdministrador'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'searchRows':
                if (!Validator::validateSearch($_POST['search'])) {
                    $result['error'] = Validator::getSearchError();
                } else {
                    $result['dataset'] = $producto->searchRows();
                    if ($result['dataset']) {
                        $result['status'] = 1;
                        $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
                    } else {
                        $result['error'] = 'No hay coincidencias';
                    }
                }
                break;

                case 'createRow':
                    $_POST = Validator::validateForm($_POST);
                
                    if (
                        !$producto->setNombre($_POST['nombreProducto']) ||
                        !$producto->setDescripcion($_POST['descripcionProducto']) ||
                        !$producto->setPrecio($_POST['precioProducto']) ||
                        !$producto->setExistencias($_POST['cantidadProducto']) ||
                        !$producto->setCategoria($_POST['categoriaProducto']) ||
                        !$producto->setEstado(isset($_POST['estadoProducto']) ? 1 : 0) ||
                        !$producto->setImagen($_FILES['imagenProducto']) ||
                        !$producto->setTalla($_POST['tallaProducto']) ||
                        !$producto->setColor($_POST['colorProducto']) ||
                        !$producto->setTipoProducto($_POST['tipoProducto']) ||
                        !$producto->setDistribuidor($_POST['distribuidorProducto'])
                    ) {
                        $result['error'] = $producto->getDataError();
                    } else if ($producto->createRow()) {
                        $result['status'] = 1;
                        $result['message'] = 'Producto creado correctamente';
                        $result['fileStatus'] = Validator::saveFile($_FILES['imagenProducto'], ProductoHandler::RUTA_IMAGEN);
                    } else {
                        $result['error'] = 'Ocurrió un problema al crear el producto';
                    }
                    break;
                
                

            case 'readAll':
                $result['dataset'] = $producto->readAll();
                if ($result['dataset']) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No existen productos registrados';
                }
                break;

            case 'readOne':
                if (!$producto->setId($_POST['idProducto'])) {
                    $result['error'] = $producto->getDataError();
                } else {
                    $result['dataset'] = $producto->readOne();
                    if ($result['dataset']) {
                        $result['status'] = 1;
                    } else {
                        $result['error'] = 'Producto inexistente';
                    }
                }
                break;

            case 'updateRow':
                $_POST = Validator::validateForm($_POST);

                if (
                    !$producto->setId($_POST['idProducto']) ||
                    !$producto->setFilename() ||
                    !$producto->setNombre($_POST['nombreProducto']) ||
                    !$producto->setDescripcion($_POST['descripcionProducto']) ||
                    !$producto->setPrecio($_POST['precioProducto']) ||
                    !$producto->setCategoria($_POST['categoriaProducto']) ||
                    !$producto->setEstado(isset($_POST['estadoProducto']) ? 1 : 0) ||
                    !$producto->setImagen($_FILES['imagenProducto'], $producto->getFilename()) ||
                    !$producto->setTalla($_POST['tallaProducto']) ||
                    !$producto->setColor($_POST['colorProducto']) ||
                    !$producto->setTipoProducto($_POST['tipoProducto']) ||
                    !$producto->setDistribuidor($_POST['distribuidorProducto'])
                ) {
                    $result['error'] = $producto->getDataError();
                } else if ($producto->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Producto modificado correctamente';
                    // Se asigna el estado del archivo después de actualizar.
                    $result['fileStatus'] = Validator::changeFile($_FILES['imagenProducto'], $producto::RUTA_IMAGEN, $producto->getFilename());
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar el producto';
                }
                break;

            case 'deleteRow':
                if (
                    !$producto->setId($_POST['idProducto']) ||
                    !$producto->setFilename()
                ) {
                    $result['error'] = $producto->getDataError();
                } else if ($producto->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Producto eliminado correctamente';
                    // Se asigna el estado del archivo después de eliminar.
                    $result['fileStatus'] = Validator::deleteFile($producto::RUTA_IMAGEN, $producto->getFilename());
                } else {
                    $result['error'] = 'Ocurrió un problema al eliminar el producto';
                }
                break;

            case 'cantidadProductosCategoria':
                $result['dataset'] = $producto->cantidadProductosCategoria();
                if ($result['dataset']) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'No hay datos disponibles';
                }
                break;
                case 'readTopProducts':
                    if ($result['dataset'] = $producto->readTopProductos()) {
                        $result['status'] = 1;
                    } else {
                        $result['error'] = 'No existen productos vendidos por el momento';
                    }
                    break;

            case 'porcentajeProductosCategoria':
                $result['dataset'] = $producto->porcentajeProductosCategoria();
                if ($result['dataset']) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'No hay datos disponibles';
                }
                break;
            default:
                $result['error'] = 'Acción no disponible dentro de la sesión';
        }

        // Se obtiene la excepción del servidor de base de datos por si ocurrió un problema.
        $result['exception'] = Database::getException();

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
