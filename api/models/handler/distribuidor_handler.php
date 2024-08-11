<?php
require_once('../../helpers/database.php');

class DistribuidorHandler
{
    protected $id = null;
    protected $nombre = null;
    protected $telefono = null;

    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_Distribuidor, nombre_Distribuidor, telefono_Distribuidor
                FROM distribuidor
                WHERE nombre_Distribuidor LIKE ? OR telefono_Distribuidor LIKE ?
                ORDER BY nombre_Distribuidor';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO Distribuidor(nombre_Distribuidor,telefono_Distribuidor)
                VALUES(?, ?)';
        $params = array($this->nombre, $this->telefono);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_Distribuidor, nombre_Distribuidor,telefono_Distribuidor
                FROM distribuidor
                ORDER BY nombre_Distribuidor';
        return Database::getRows($sql);
    }

    public function readOne()
    {
        $sql = 'SELECT id_Distribuidor, nombre_Distribuidor,telefono_Distribuidor
                FROM distribuidor
                WHERE id_Distribuidor = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE Distribuidor
                SET nombre_Distribuidor = ?, telefono_Distribuidor = ?
                WHERE id_Distribuidor = ?';
        $params = array($this->nombre, $this->telefono, $this->id);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM distribuidor
                WHERE id_Distribuidor = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
}
