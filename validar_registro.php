<?php 
session_start();

$nombreErr = $emailErr = $contrasenaErr = $confirmarContrasenaErr = "";
$nombre = $email = $contrasena = $confirmarContrasena = "";

if ($_SERVER["REQUEST_METHOD"]=="POST"){
    if (empty($_POST["nombre"])){
        $nombreErr = "Es necesario el nombre";
    } else {
        $nombre = test_input($_POST["nombre"]);

        if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
        $nameErr = "Only letters and white space allowed";
        }
    }
}

?>