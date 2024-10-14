<?php 
session_start();
ini_set('display_errors', 1);

$nombreErr = $contrasenaErr = $confirmarContrasenaErr = "";
$nombre = $contrasena = $confirmarContrasena = "";

if ($_SERVER["REQUEST_METHOD"]=="POST"){

    if (empty($_POST["nombre"])){
        $nombreErr = "El nombre es obligatorio.";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $_POST["nombre"])) {
        $nombreErr = "El nombre solo puede contener letras y espacios.";
    } else {
        $nombre = $_POST["nombre"];
    }

    if (empty($_POST["contrasena"])) {
        $contrasenaErr = "La contraseña es obligatoria.";
    } elseif (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{6,}$/", $_POST["contrasena"])) {
        $contrasenaErr = "La contraseña debe tener al menos 6 caracteres, una mayúscula, una minúscula, un número y un símbolo.";
    } else {
        $contrasena = $_POST["contrasena"];
    }

    if (empty($_POST["confirmarContrasena"])) {
        $confirmarContrasenaErr = "Por favor confirma tu contraseña.";
    } elseif ($_POST["confirmarContrasena"] !== $contrasena) { // CComparar con $contrasena
        $confirmarContrasenaErr = "Las contraseñas no coinciden.";
    } else {
        $confirmarContrasena = $_POST["confirmarContrasena"];
    }

    // Si no hay errores, procesar el registro
    if (empty($nombreErr) && empty($contrasenaErr) && empty($confirmarContrasenaErr)) {
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

        // Preparar y vincular
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, contrasena) VALUES (?, ?)");
        $stmt->bind_param("ss", $nombre, $contrasena);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Redirigir de nuevo al formulario
                header("Location: registro.php");
                exit();
        } else {
            echo "Error al registrar: " . $stmt->error;
        }

        // Cerrar la conexión
        $stmt->close();
        $conn->close();
    } else {
        // Almacenar los errores en la sesión
        $_SESSION['nombreErr'] = $nombreErr;
        $_SESSION['contrasenaErr'] = $contrasenaErr;
        $_SESSION['confirmarContrasenaErr'] = $confirmarContrasenaErr;
    
        header("Location: registro.php");
        exit();
    }
}
?>