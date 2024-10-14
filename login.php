<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Davi WebPage</title>
    <link rel="stylesheet" href="./css/styles.css">
</head>
<body>
    <main>
        <h1>Iniciar Sesión</h1>

        <form action="validar_login.php" method="POST">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
            <span style="color: red;" class="error"><?php echo isset($_SESSION['nombreErr']) ? $_SESSION['nombreErr'] : ''; ?></span>
            <br>

            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required>
            <span style="color: red;" class="error"><?php echo isset($_SESSION['contrasenaErr']) ? $_SESSION['contrasenaErr'] : ''; ?></span>
            <br>

            <button type="submit">Iniciar Sesión</button>
        </form>

        <p>¿No tienes una cuenta?</p>
            <a href="registro.php"><button>Crear una cuenta</button></a>
            
        <?php 
            session_unset();
        ?>
    </main>
</body>
</html>