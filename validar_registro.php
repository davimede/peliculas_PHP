<?php 
session_start();

$nombreErr = $emailErr = $contrasenaErr = $confirmarContrasenaErr = "";
$nombre = $email = $contrasena = $confirmarContrasena = "";

if ($_SERVER["REQUEST_METHOD"]=="POST"){

    if (empty($_POST["nombre"])){
        $nombreErr = "El nombre es obligatorio.";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $_POST["nombre"])) {
        $nombreErr = "El nombre solo puede contener letras y espacios.";
    } else {
        $nombre = $_POST["nombre"];
    }

    if (empty($_POST["email"])){
        $emailErr = "El email es obligatorio.";
    } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Ingresa un correo electrónico válido.";
    } else {
        $email = $_POST["email"];
    }
}

?>