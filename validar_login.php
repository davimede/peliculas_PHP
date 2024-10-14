<?php
session_start();

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nombre = $_POST['nombre'];
    $contrasena = $_POST['contrasena'];

    // Conexión a la base de datos
    $servername = "db";
    $username = "root";
    $dbPassword = "root";
    $dbname = "mydatabase";

    // Crear conexión
    $conn = new mysqli($servername, $username, $dbPassword, $dbname);

    // Verificar conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Consulta SQL para obtener el nombre del usuario
    $sql = "SELECT nombre FROM usuarios WHERE nombre = '$nombre' AND contrasena = '$contrasena'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Se encontró el usuario, obtener los datos
        $filas = $result->fetch_assoc();
        $_SESSION['nombre'] = $filas['nombre']; // Almacenar nombre en sesión
        
        header("Location: dashboard.php");
        exit();
    } else {
        $_SESSION['loginError'] = "No se encontró una cuenta con este correo electrónico o la contraseña es incorrecta.";
        header("Location: login.php");
        exit();
    }

    $conn->close();
}
?>
