<?php
// Encabezado para permitir solicitudes de cualquier origen.
header('Access-Control-Allow-Origin: *');

// Se establece la zona horaria local para la fecha y hora del servidor.
date_default_timezone_set('America/El_Salvador');

// Constantes para establecer las credenciales de conexión con el servidor de bases de datos.
define('SERVER', 'localhost'); // Dirección del servidor de base de datos
define('DATABASE', 'streetweardrop_db'); // Nombre de la base de datos
define('USERNAME', 'root'); // Nombre de usuario de la base de datos
define('PASSWORD', ''); // Contraseña de la base de datos
?>
