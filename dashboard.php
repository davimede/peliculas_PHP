<?php 
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['nombre'])) {
    header("Location: login.php"); // Redirigir al login si no está logueado
    exit();
}

// Conexión Base de Datos
$servername = "db";
$username = "root";
$dbPassword = "root";
$dbname = "mydatabase";

$conn = new mysqli($servername, $username, $dbPassword, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$usuario_nombre = $_SESSION['nombre'];

$stmt = $conn->prepare("SELECT ISAN, nombre_pelicula, puntuacion, anyo FROM peliculasUsuario WHERE usuario = ?");
$stmt->bind_param("s", $usuario_nombre);
$stmt->execute();
$result = $stmt->get_result();

echo "<h1>Hola, $usuario_nombre</h1><br>";
echo "<h2>Las películas del usuario:</h2><br>";

if ($result->num_rows > 0) {
    // Mostrar cada película
    while ($row = $result->fetch_assoc()) {
        echo "- " . $row['nombre_pelicula'] . " (ISAN: " . $row['ISAN'] . ", Puntuación: " . $row['puntuacion'] . ", Año: " . $row['anyo'] . ")<br>";
    }
} else {
    echo "No hay películas añadidas por este usuario.";
}

echo "<hr><h2>Añadir Películas</h2>";

$stmt->close();
$conn->close();
?>

<form action="" method="POST">
    <label for="isan">ISAN:</label>
    <input type="text" id="isan" name="isan" required><br>

    <label for="nombre_pelicula">Nombre de la Película:</label>
    <input type="text" id="nombre_pelicula" name="nombre_pelicula" required><br>

    <label for="puntuacion">Puntuación (0-5):</label>
    <input type="number" id="puntuacion" name="puntuacion" min="0" max="5" required><br>

    <label for="anyo">Año:</label>
    <input type="number" id="anyo" name="anyo" min="1888" max="<?php echo date("Y"); ?>" required><br>

    <button type="submit">Añadir Película</button>
</form>

<?php 
    // Cerrar la conexión
    $stmt->close();
    $conn->close();
?>