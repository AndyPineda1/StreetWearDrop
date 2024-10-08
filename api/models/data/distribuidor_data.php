<?php
// Se incluye la clase para validar los datos de entrada.
require_once('../../helpers/validator.php');
// Se incluye la clase padre.
require_once('../../models/handler/distribuidor_handler.php');

/*
 *	Clase para manejar el encapsulamiento de los datos de la tabla DISTRIBUIDOR.
 */
class DistribuidorData extends DistribuidorHandler
{
    /*
     *  Atributos adicionales.
     */
    private $data_error = null;
    private $filename = null;

    /*
     *   Métodos para validar y establecer los datos.
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

    // Método para establecer y validar el número de teléfono.
    public function setTelefono($value, $min = 2, $max = 15)
    {
        if (Validator::validatePhone($value)) {
            $this->telefono = $value;
            return true;
        } else {
            $this->data_error = 'El número de teléfono es incorrecto';
            return false;
        }
    }

    // Método para establecer y validar el nombre.
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
     *  Métodos para obtener los atributos adicionales.
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
