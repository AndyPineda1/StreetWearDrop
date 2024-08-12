<?php
// Se incluye la clase para validar los datos de entrada.
require_once('../../helpers/validator.php');
// Se incluye la clase padre.
require_once('../../models/handler/tipoProducto_handler.php');

/*
 * Clase para manejar el encapsulamiento de los datos de la tabla TIPO_PRODUCTO.
 */
class TipoProductoData extends TipoProductoHandler
{
    /*
     * Atributos adicionales.
     */
    private $filename = null;
    private $data_error = null;

    /*
     * Métodos para validar y establecer los datos.
     */

    // Método para establecer y validar el ID.
    public function setId($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id = $value;
            return true;
        } else {
            $this->data_error = 'El identificador es incorrecto';
            return false;
        }
    }

    // Método para establecer y validar el nombre del tipo de producto.
    public function setNombre($value, $min = 2, $max = 50)
    {
        if (Validator::validateAlphabetic($value) && Validator::validateLength($value, $min, $max)) {
            $this->nombre = $value;
            return true;
        } else {
            $this->data_error = 'El nombre debe contener solo letras y espacios, y debe tener una longitud entre ' . $min . ' y ' . $max . ' caracteres';
            return false;
        }
    }

    /*
     * Métodos para obtener los atributos adicionales.
     */
    public function getDataError()
    {
        return $this->data_error;
    }

    public function getFilename()
    {
        return $this->filename;
    }
}
?>
