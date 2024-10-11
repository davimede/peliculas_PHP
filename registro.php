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
        <h1>Formulario de registro</h1>

        <form action="validar_registro.php" method="POST">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
            <span class="error">* <?php echo $nombreErr;?></span>
            <br>

            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required>
            <span class="error">* <?php echo $contrasenaErr;?></span>
            <br>

            <label for="confirmarContrasena">Confirmar Contraseña:</label>
            <input type="password" id="confirmarContrasena" name="contrasena" required>
            <span class="error">* <?php echo $confirmarContrasenaErr;?></span>
            <br>

            <button type="submit">Registrarse</button>

            <p>Hacer login</p>
            <a href="login.php"><button type="submit">Loguearse</button></a>

            <?php 
            session_unset();
            ?>
        </form>
    </main>
</body>
</html>