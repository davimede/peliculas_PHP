<?php 
session_start();

$nombreErr = $contrasenaErr = $confirmarContrasenaErr = "";
$nombre = $contrasena = $confirmarContrasena = "";

if ($_SERVER["REQUEST_METHOD"]=="POST"){

    // Validar nombre
    if (empty($_POST["nombre"])){
        $nombreErr = "El nombre es obligatorio.";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $_POST["nombre"])) {
        $nombreErr = "El nombre solo puede contener letras y espacios.";
    } else {
        $nombre = $_POST["nombre"];
    }

    // Validar contraseña
    if (empty($_POST["password"])) {
        $contrasenaErr = "La contraseña es obligatoria.";
    } elseif (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{6,}$/", $_POST["password"])) {
        $contrasenaErr = "La contraseña debe tener al menos 6 caracteres, una mayúscula, una minúscula, un número y un símbolo.";
    } else {
        $contrasena = $_POST["password"];
    }

    // Validar Confirmación de Contraseña
    if (empty($_POST["confirmPassword"])) {
        $confirmarContrasenaErr = "Por favor confirma tu contraseña.";
    } elseif ($_POST["confirmPassword"] !== $password) { // CComparar con $password
        $confirmarContrasenaErr = "Las contraseñas no coinciden.";
    } else {
        $confirmarContrasena = $_POST["confirmPassword"];
    }
}

?>