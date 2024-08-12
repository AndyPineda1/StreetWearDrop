DROP DATABASE IF EXISTS streetweardrop_db;
CREATE DATABASE streetweardrop_db;
USE streetweardrop_db;

CREATE TABLE administrador (
    id_administrador int(10) UNSIGNED NOT NULL,
    nombre_administrador varchar(50) NOT NULL,
    apellido_administrador varchar(50) NOT NULL,
    correo_administrador varchar(100) NOT NULL,
    alias_administrador varchar(25) NOT NULL,
    clave_administrador varchar(100) NOT NULL,
    fecha_registro datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

   CREATE TABLE orders (
    id_order INT PRIMARY KEY AUTO_INCREMENT,
    ordercode VARCHAR(10) NOT NULL,
    order_product VARCHAR(100) NOT NULL,
    nombre_order VARCHAR(20) NOT NULL,
    amount INT NOT NULL,
    image_order VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE Genero (
    id_Genero INT PRIMARY KEY,
    nombre_genero VARCHAR(20)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO Genero (id_Genero, nombre_genero) VALUES
(1, 'Masculino'),
(2, 'Femenino'),
(3, 'Otro');

CREATE TABLE Clientes (
    id_Cliente INT AUTO_INCREMENT PRIMARY KEY,
    nombre_Cliente VARCHAR(30),
    apellido_Cliente VARCHAR(50),
    numero_Cliente VARCHAR(20),
    correo_Cliente VARCHAR(100),
    direccion_Cliente VARCHAR(200),
    img_Cliente VARCHAR(200),
    estado_Cliente INT,
    clave_Cliente VARCHAR(200),
    id_Genero INT,
    FOREIGN KEY (id_Genero) REFERENCES Genero(id_Genero)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


INSERT INTO Clientes (
    nombre_Cliente, 
    apellido_Cliente, 
    numero_Cliente, 
    correo_Cliente, 
    direccion_Cliente, 
    img_Cliente, 
    estado_Cliente, 
    clave_Cliente, 
    id_Genero
) VALUES (
    'Juan', 
    'Pérez', 
    '1234567890', 
    'juan.perez@example.com', 
    'Calle Falsa 123, Ciudad, País', 
    'img/juan_perez.jpg', 
    1, 
    '$2y$10$EvtUxJ1LQK/GP3iWLxBZJu2qgJnGSn0aMlO8IdmoliEZCQrCz5vJ.', 
    1
);


INSERT INTO Clientes (nombre_cliente, apellido_cliente, numero_cliente, correo_cliente, direccion_cliente, id_Genero, Clave_cliente) VALUES
('John', 'Doe', '1234567890', 'john@example.com', '123 Street, City, Country', 1, '$2y$10$EvtUxJ1LQK/GP3iWLxBZJu2qgJnGSn0aMlO8IdmoliEZCQrCz5vJ.'),
('Jane', 'Doe', '0987654321', 'jane@example.com', '456 Street, City, Country', 2, '$2y$10$EvtUxJ1LQK/GP3iWLxBZJu2qgJnGSn0aMlO8IdmoliEZCQrCz5vJ.');

SELECT*FROM Clientes;
	
CREATE TABLE Distribuidores (
    id_Distribuidor INT AUTO_INCREMENT PRIMARY KEY,
    nombre_Distribuidor VARCHAR(50),
    telefono_Distribuidor VARCHAR(20)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO Distribuidores (id_Distribuidor, nombre_Distribuidor, telefono_Distribuidor) VALUES
(1, 'Distribuidor A', '123456789'),
(2, 'Distribuidor B', '987654321');

CREATE TABLE Categoria (
    id_categoria INT PRIMARY KEY AUTO_INCREMENT,
    nombre_categoria VARCHAR(30),
    descripcion_categoria VARCHAR(200),
    imagen_categoria VARCHAR(30)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



CREATE TABLE Secciones (
idSeccion INT PRIMARY KEY AUTO_INCREMENT,
nombreSeccion VARCHAR(30),
descripcionSeccion VARCHAR(200),
imagenSeccion VARCHAR(30)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=UTF8_UNICODE_CI;

INSERT INTO Secciones (idSeccion, nombreSeccion) VALUES
(1, 'Hombres'),
(2, 'Mujeres'),
(3, 'Ninos');

CREATE TABLE TipoProducto (
    id_TipoProducto INT AUTO_INCREMENT PRIMARY KEY,
    nombre_TipoProducto VARCHAR(30)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO TipoProducto (id_TipoProducto, nombre_TipoProducto) VALUES
(1, 'Camiseta'),
(2, 'Zapatos'),
(3, 'Gorra'),
(4, 'Lentes de sol');

CREATE TABLE Productos (
    id_producto INT PRIMARY KEY AUTO_INCREMENT,
    nombre_producto VARCHAR(50),
    cantidad_producto INT,
    descripcion_producto VARCHAR(200),
    precio_producto FLOAT,
    imagen_producto VARCHAR(30),
    talla_producto VARCHAR(10),
    color_producto VARCHAR(30),
    estado_producto tinyint(1) NOT NULL,
    id_categoria INT,
    id_TipoProducto INT,
    id_Distribuidor INT,
    FOREIGN KEY (id_categoria) REFERENCES Categoria(id_categoria),
    FOREIGN KEY (id_TipoProducto) REFERENCES TipoProducto(id_TipoProducto),
    FOREIGN KEY (id_Distribuidor) REFERENCES Distribuidores(id_Distribuidor)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=UTF8_UNICODE_CI;



-- Trigger funcionalidad filtrado de producto
CREATE TABLE log_productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    producto_id INT,
    accion VARCHAR(50),
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (producto_id) REFERENCES Productos(id_producto)
);

DELIMITER //
CREATE TRIGGER after_insert_product
AFTER INSERT ON Productos
FOR EACH ROW
BEGIN
    INSERT INTO log_productos (producto_id, accion, fecha) VALUES (NEW.id_producto, 'insertado', NOW());
END;
DELIMITER ;


CREATE TABLE Pedidos (
    id_Pedido INT PRIMARY KEY AUTO_INCREMENT,
    estado_Pedido VARCHAR(50),
    fecha_Registro DATE,
    id_Cliente INT,
    direccion_Pedido VARCHAR(200),
    FOREIGN KEY (id_Cliente) REFERENCES Clientes(id_Cliente)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO Pedidos (estado_Pedido, fecha_Registro, id_Cliente, direccion_Pedido) VALUES
('En proceso', '2024-03-17', 1, '123 Street, City, Country'),
('Entregado', '2024-03-16', 2, '456 Street, City, Country'),
('Carrito', NULL, 1, NULL),
('Carrito', NULL, 2, NULL);

CREATE TABLE DetallePedido (
    id_Pedido_Detalle INT PRIMARY KEY AUTO_INCREMENT,
    cantidad_Producto INT,
    id_Pedido INT,
    id_Producto INT,
    FOREIGN KEY (id_Pedido) REFERENCES Pedidos(id_Pedido),
    FOREIGN KEY (id_Producto) REFERENCES Productos(id_Producto)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE Comentarios (
    id_Comentario INT PRIMARY KEY AUTO_INCREMENT,
    contenido VARCHAR(200),
    id_Pedido INT,
    FOREIGN KEY (id_Pedido) REFERENCES Pedidos(id_Pedido)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=UTF8_UNICODE_CI;

CREATE TABLE valoraciones (
    id_valoraciones INT PRIMARY KEY AUTO_INCREMENT,
    id_producto INT NOT NULL,
    id_Usuario INT DEFAULT NULL,
    valoracion INT NOT NULL,
    fecha_valoracion DATETIME NOT NULL,
    FOREIGN KEY (id_producto) REFERENCES productos(id_producto)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=UTF8_UNICODE_CI;


CREATE TABLE Direcciones (
    id_Direccion INT PRIMARY KEY,
    direccion VARCHAR(200),
    id_Cliente INT,
    FOREIGN KEY (id_Cliente) REFERENCES Clientes(id_Cliente)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE TipoUsuario (
    id_TipoUsuario INT PRIMARY KEY,
    nombre_TipoUsuario VARCHAR(100)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO TipoUsuario (id_TipoUsuario, nombre_TipoUsuario) VALUES
(1, 'Administrador'),
(2, 'Trabajador'),
(3, 'Usuario regular');

CREATE TABLE Usuario (
    id_Usuario INT PRIMARY KEY,
    username VARCHAR(30),
    nombre_Usuario VARCHAR(60),
    correo_Usuario VARCHAR(40),
    clave_Usuario VARCHAR(20),
    numero_Usuario VARCHAR(20),
    direccion_Usuario VARCHAR(200),
    id_TipoUsuario INT,
    imagen_Usuario VARCHAR(30),
    FOREIGN KEY (id_TipoUsuario) REFERENCES TipoUsuario(id_TipoUsuario)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE Clientes
ADD CONSTRAINT fk_Clientes_Genero FOREIGN KEY (id_Genero) REFERENCES Genero(id_Genero);

ALTER TABLE Direcciones
ADD CONSTRAINT fk_Direcciones_Clientes FOREIGN KEY (id_Cliente) REFERENCES Clientes(id_Cliente);

ALTER TABLE Comentarios
ADD CONSTRAINT fk_Comentarios_Pedidos FOREIGN KEY (id_Pedido) REFERENCES Pedidos(id_Pedido);

ALTER TABLE Usuario
ADD CONSTRAINT fk_Usuario_TipoUsuario FOREIGN KEY (id_TipoUsuario) REFERENCES TipoUsuario(id_TipoUsuario);

DELIMITER //

CREATE TRIGGER trg_after_insert_detallepedido
AFTER INSERT ON DetallePedido
FOR EACH ROW
BEGIN
    DECLARE cantidad_actual INT;

    -- Obtener la cantidad actual del producto
    SELECT cantidad_producto INTO cantidad_actual
    FROM Productos
    WHERE id_producto = NEW.id_Producto;

    -- Verificar si hay suficiente stock
    IF cantidad_actual < NEW.cantidad_Producto THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'No se puede realizar la operación: Stock insuficiente.';
    ELSE
        UPDATE Productos
        SET cantidad_producto = cantidad_producto - NEW.cantidad_Producto
        WHERE id_producto = NEW.id_Producto;
    END IF;
END;
//

DELIMITER ;


DELIMITER //
CREATE TRIGGER trg_after_delete_detallepedido
AFTER DELETE ON DetallePedido
FOR EACH ROW
BEGIN
    UPDATE Productos
    SET cantidad_producto = cantidad_producto + OLD.cantidad_Producto
    WHERE id_producto = OLD.id_Producto;
END;
//
DELIMITER ;

DELIMITER //

CREATE TRIGGER trg_after_update_detallepedido
AFTER UPDATE ON DetallePedido
FOR EACH ROW
BEGIN
    DECLARE diff INT;
    DECLARE cantidad_actual INT;

    SET diff = OLD.cantidad_Producto - NEW.cantidad_Producto;

    -- Obtener la cantidad actual del producto
    SELECT cantidad_producto INTO cantidad_actual
    FROM Productos
    WHERE id_producto = NEW.id_Producto;

    -- Verificar si hay suficiente stock en caso de que se necesite disminuir la cantidad
    IF diff < 0 AND cantidad_actual < ABS(diff) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'No se puede realizar la operación: Stock insuficiente.';
    ELSE
        UPDATE Productos
        SET cantidad_producto = cantidad_producto + diff
        WHERE id_producto = NEW.id_Producto;
    END IF;
END;
//

DELIMITER ;


DELIMITER //
CREATE TRIGGER revisar_cliente_insert
AFTER INSERT ON Clientes
FOR EACH ROW
BEGIN
    INSERT INTO Comentarios (contenido, id_Pedido) VALUES ('Nuevo cliente registrado', NULL);
END;
//
DELIMITER ;

DELIMITER //
CREATE TRIGGER revisar_pedido_update
AFTER UPDATE ON Pedidos
FOR EACH ROW
BEGIN
    INSERT INTO Comentarios (contenido, id_Pedido) VALUES ('El estado del pedido ha sido actualizado', NEW.id_Pedido);
END;
//
DELIMITER ;
