<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');

/*
 *  Clase para manejar el comportamiento de los datos de la tabla TipoProducto.
 */
class TipoProductoHandler
{
    /*
     *  Declaración de atributos para el manejo de datos.
     */
    protected $id = null;
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
        $sql = 'SELECT  nombre_TipoProducto
                FROM TipoProducto
                WHERE nombre_TipoProducto LIKE ?
                ORDER BY nombre_TipoProducto';
        $params = array($value);
        return Database::getRows($sql, $params);
    }

    /*
    *  Método para crear un nuevo registro.
    */
    public function createRow()
    {
        $sql = 'INSERT INTO TipoProducto(nombre_TipoProducto)
                VALUES(?)';
        $params = array($this->nombre);
        return Database::executeRow($sql, $params);
    }

    /*
    *  Método para leer todos los registros.
    */
    public function readAll()
    {
        $sql = 'SELECT id_TipoProducto, nombre_TipoProducto 
                FROM TipoProducto
                ORDER BY nombre_TipoProducto';
        return Database::getRows($sql);
    }

    /*
    *  Método para leer un registro específico.
    */
    public function readOne()
    {
        $sql = 'SELECT nombre_TipoProducto
                FROM TipoProducto
                WHERE id_TipoProducto = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    /*
    *  Método para actualizar un registro.
    */
    public function updateRow()
    {
        $sql = 'UPDATE TipoProducto
                SET nombre_TipoProducto = ?
                WHERE id_TipoProducto = ?';
        $params = array($this->nombre, $this->id);
        return Database::executeRow($sql, $params);
    }

    /*
    *  Método para eliminar un registro.
    */
    public function deleteRow()
    {
        $sql = 'DELETE FROM TipoProducto
                WHERE id_TipoProducto = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    /*
    *   Métodos para generar reportes.
    */
    public function tipoProducto()
    {
        $sql = 'SELECT p.id_producto, p.nombre_producto, p.precio_producto, p.estado_producto
        FROM Productos p
        WHERE p.id_TipoProducto = ?
        ORDER BY p.nombre_producto';
       $params = array($this->id);
       return Database::getRows($sql, $params);
    }
}
?>
