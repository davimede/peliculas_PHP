<?php
// Configuración BD
$servername = "db";
$username = "root";
$dbPassword = "root";

// Crear conexión
$conn = new mysqli($servername, $username, $dbPassword);

// Verificar conexión
if ($conn -> connect_error) {
    die("Conexión fallida: " .$conn->connect_error);
} echo "Connexión exitosa.<br>";

// Crear la base de datos
$sql = "CREATE DATABASE IF NOT EXISTS mydatabase";
if ($conn->query($sql) === TRUE) {
    echo "Base de datos creada.<br>";
} else {
    echo "Error al crear la base de datos: " .$conn->error . "<br>";
}

// Seleccionar la base de datos
$conn->select_db('mydatabase');

// Crear tabla usuarios
$sql = "CREATE TABLE IF NOT EXISTS usuarios (
    nombre VARCHAR(50) PRIMARY KEY,
    contrasena VARCHAR(255) NOT NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "Tabla 'usuarios' creada correctamente.<br>";
} else {
    echo "Error al crear la tabla 'usuarios': " . $conn->error . "<br>";
}

// Crear tabla peliculasUsuario
$sql = "CREATE TABLE IF NOT EXISTS peliculasUsuario (
    usuario VARCHAR(50),
    ISAN VARCHAR(20) NOT NULL,
    nombre_pelicula VARCHAR(100) NOT NULL,
    puntuacion INT CHECK (puntuacion >= 1 AND puntuacion <= 10),
    anyo INT CHECK (anyo >= 1888 AND anyo <= YEAR(CURDATE())),
    PRIMARY KEY (usuario, ISAN),
    FOREIGN KEY (usuario) REFERENCES usuarios(nombre)
)";

if ($conn->query($sql) === TRUE) {
    echo "Tabla 'peliculasUsuario' creada correctamente.<br>";
} else {
    echo "Error al crear la tabla 'peliculasUsuario': " . $conn->error . "<br>";
}

// Cerrar la conexión
$conn->close();
?>
