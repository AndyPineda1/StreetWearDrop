<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');

/*
 *  Clase para manejar el comportamiento de los datos de la tabla distribuidores.
 */
class DistribuidorHandler
{
    /*
     *  Declaración de atributos para el manejo de datos.
     */
    protected $id = null;
    protected $telefono = null;
    protected $nombre = null;

    /*
     *  Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */

    /*
    *  Método para buscar registros.
    */
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_Distribuidor, nombre_Distribuidor, telefono_Distribuidor
                FROM distribuidores
                WHERE nombre_Distribuidor LIKE ? OR telefono_Distribuidor LIKE ?
                ORDER BY nombre_Distribuidor';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }

    /*
    *  Método para crear un nuevo registro.
    */
    public function createRow()
    {
        $sql = 'INSERT INTO distribuidores(nombre_Distribuidor, telefono_Distribuidor)
                VALUES(?, ?)';
        $params = array($this->nombre, $this->telefono);
        return Database::executeRow($sql, $params);
    }

    /*
    *  Método para leer todos los registros.
    */
    public function readAll()
    {
        $sql = 'SELECT id_Distribuidor, nombre_Distribuidor, telefono_Distribuidor 
                FROM distribuidores
                ORDER BY nombre_Distribuidor';
        return Database::getRows($sql);
    }

    /*
    *  Método para leer un registro específico.
    */
    public function readOne()
    {
        $sql = 'SELECT nombre_Distribuidor, telefono_Distribuidor
                FROM distribuidores
                WHERE id_Distribuidor = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    /*
    *  Método para actualizar un registro.
    */
    public function updateRow()
    {
        $sql = 'UPDATE distribuidores
                SET nombre_Distribuidor = ?, telefono_Distribuidor = ?
                WHERE id_Distribuidor = ?';
        $params = array($this->nombre, $this->telefono, $this->id);
        return Database::executeRow($sql, $params);
    }

    /*
    *  Método para eliminar un registro.
    */
    public function deleteRow()
    {
        $sql = 'DELETE FROM distribuidores
                WHERE id_Distribuidor = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
}
?>
