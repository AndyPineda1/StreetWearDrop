<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');

/*
*   Clase para manejar el comportamiento de los datos de la tabla PRODUCTO.
*/
class ProductoHandler
{
    /*
    *   Declaración de atributos para el manejo de datos.
    */
    protected $id = null;
    protected $nombre = null;
    protected $descripcion = null;
    protected $precio = null;
    protected $existencias = null;
    protected $imagen = null;
    protected $categoria = null;
    protected $estado = null;
    protected $talla = null;
    protected $color = null;
    protected $tipoProducto = null;
    protected $distribuidor = null;

    // Constante para establecer la ruta de las imágenes.
    const RUTA_IMAGEN = '../../images/productos/';

    /*
    *   Método para validar si los atributos necesarios están definidos.
    */
    private function validateAttributes($isUpdate = false)
    {
        if ($isUpdate && $this->id === null) {
            throw new Exception("El ID del producto es necesario para la actualización.");
        }

        if (
            $this->nombre === null || $this->descripcion === null || $this->precio === null ||
            $this->existencias === null || $this->estado === null || $this->categoria === null ||
            $this->tipoProducto === null || $this->distribuidor === null || $this->talla === null ||
            $this->color === null
        ) {
            throw new Exception("Faltan datos para realizar la operación en el producto.");
        }
    }

    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
    */
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_producto, imagen_producto, nombre_producto, descripcion_producto, precio_producto, nombre_categoria, estado_producto
                FROM productos
                INNER JOIN categoria USING(id_categoria)
                WHERE nombre_producto LIKE ? OR descripcion_producto LIKE ?
                ORDER BY nombre_producto';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }

    public function createRow()
    {
        $this->validateAttributes();

        $sql = 'INSERT INTO productos(nombre_producto, cantidad_producto, descripcion_producto, precio_producto, imagen_producto, talla_producto, color_producto, estado_producto, id_categoria, id_TipoProducto, id_Distribuidor)
                VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $params = array(
            $this->nombre,
            $this->existencias,
            $this->descripcion,
            $this->precio,
            $this->imagen,
            $this->talla,
            $this->color,
            $this->estado,
            $this->categoria,
            $this->tipoProducto,
            $this->distribuidor
        );

        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT 
                p.id_producto, 
                p.imagen_producto, 
                p.nombre_producto, 
                p.descripcion_producto, 
                p.precio_producto, 
                p.cantidad_producto,
                p.talla_producto,
                p.color_producto,
                COALESCE(c.nombre_categoria, "No disponible") AS nombre_categoria, 
                COALESCE(tp.nombre_TipoProducto, "No disponible") AS nombre_tipo_producto, 
                COALESCE(d.nombre_Distribuidor, "No disponible") AS nombre_distribuidor, 
                p.estado_producto
            FROM 
                Productos p
            LEFT JOIN 
                Categoria c ON p.id_categoria = c.id_categoria
            LEFT JOIN 
                TipoProducto tp ON p.id_TipoProducto = tp.id_TipoProducto
            LEFT JOIN 
                Distribuidores d ON p.id_Distribuidor = d.id_Distribuidor
            ORDER BY 
                p.nombre_producto';

        return Database::getRows($sql);
    }


    public function readOne()
    {
        $sql = 'SELECT id_producto, nombre_producto, descripcion_producto, precio_producto, cantidad_producto, imagen_producto, id_categoria, estado_producto, talla_producto, color_producto, id_TipoProducto, id_distribuidor
                FROM productos
                WHERE id_producto = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function readFilename()
    {
        $sql = 'SELECT imagen_producto
                FROM productos
                WHERE id_producto = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $this->validateAttributes(true);

        $sql = 'UPDATE productos
                SET nombre_producto = ?, descripcion_producto = ?, precio_producto = ?, cantidad_producto = ?, imagen_producto = ?, estado_producto = ?, id_categoria = ?, talla_producto = ?, color_producto = ?, id_TipoProducto = ?, id_Distribuidor = ?
                WHERE id_producto = ?';
        $params = array(
            $this->nombre,
            $this->descripcion,
            $this->precio,
            $this->existencias,
            $this->imagen,
            $this->estado,
            $this->categoria,
            $this->talla,
            $this->color,
            $this->tipoProducto,
            $this->distribuidor,
            $this->id
        );

        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM productos
                WHERE id_producto = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    public function readProductosCategoria()
    {
        $sql = 'SELECT id_producto, imagen_producto, nombre_producto, descripcion_producto, precio_producto, cantidad_producto
                FROM productos
                INNER JOIN categoria USING(id_categoria)
                WHERE id_categoria = ? AND estado_producto = true
                ORDER BY nombre_producto';
        $params = array($this->categoria);
        return Database::getRows($sql, $params);
    }

    public function readProductosTipo()
    {
        $sql = 'SELECT id_producto, imagen_producto, nombre_producto, descripcion_producto, precio_producto, cantidad_producto
                FROM productos
                INNER JOIN tipo_producto USING(id_TipoProducto)
                WHERE id_TipoProducto = ? AND estado_producto = true
                ORDER BY nombre_producto';
        $params = array($this->tipoProducto);
        return Database::getRows($sql, $params);
    }

    public function readProductosDistribuidor()
    {
        $sql = 'SELECT id_producto, imagen_producto, nombre_producto, descripcion_producto, precio_producto, cantidad_producto
                FROM productos
                INNER JOIN distribuidor USING(id_distribuidor)
                WHERE id_distribuidor = ? AND estado_producto = true
                ORDER BY nombre_producto';
        $params = array($this->distribuidor);
        return Database::getRows($sql, $params);
    }

    /*
    *   Métodos para generar gráficos.
    */
    public function cantidadProductosCategoria()
    {
        $sql = 'SELECT nombre_categoria, COUNT(id_producto) cantidad
                FROM productos
                INNER JOIN categoria USING(id_categoria)
                GROUP BY nombre_categoria ORDER BY cantidad DESC LIMIT 5';
        return Database::getRows($sql);
    }

    public function porcentajeProductosCategoria()
    {
        $sql = 'SELECT nombre_categoria, ROUND((COUNT(id_producto) * 100.0 / (SELECT COUNT(id_producto) FROM productos)), 2) porcentaje
                FROM productos
                INNER JOIN categoria USING(id_categoria)
                GROUP BY nombre_categoria ORDER BY porcentaje DESC';
        return Database::getRows($sql);
    }

    // Método para gráficar el top 5 de productos más vendidos de una categoría.
    public function readTopProductos()
    {
        $sql = '  SELECT nombre_producto, SUM(dp.cantidad_Producto) total
                FROM detallepedido dp
                INNER JOIN productos USING(id_producto)
                GROUP BY nombre_producto
                ORDER BY total DESC
                LIMIT 5';
        return Database::getRows($sql);
    }

    /*
    *   Métodos para generar reportes.
    */
    public function productosCategoria()
    {
        $sql = 'SELECT nombre_producto, precio_producto, estado_producto
                FROM productos
                INNER JOIN categoria USING(id_categoria)
                WHERE id_categoria = ?
                ORDER BY nombre_producto';
        $params = array($this->categoria);
        return Database::getRows($sql, $params);
    }
}
