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
    echo "No hay películas añadidas por este usuario.<hr>";
}

// Errores y valores
$nombre_peliculaErr = $isanErr = "";
$nombre_pelicula = $isan = $puntuacion = "";

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Inicializar mensajes de error
    $errors = [];
    
    // Obtener y limpiar entradas
    $isan = isset($_POST['isan']) ? trim($_POST['isan']) : '';
    $nombre_pelicula = isset($_POST['nombre_pelicula']) ? trim($_POST['nombre_pelicula']) : '';
    $puntuacion = isset($_POST['puntuacion']) ? intval($_POST['puntuacion']) : null;

    // Validar entradas
    if (empty($isan) && empty($nombre_pelicula)) {
        $errors[] = "El ISAN y el nombre de la película no pueden estar vacíos.";
    } elseif (!empty($isan) && strlen($isan) != 8) {
        $errors[] = "El ISAN debe tener 8 dígitos.";
    } elseif (!empty($isan) && empty($nombre_pelicula) && $puntuacion !== null) {
        // Eliminar película si el ISAN existe y el nombre está vacío
        $delete_stmt = $conn->prepare("DELETE FROM peliculasUsuario WHERE usuario = ? AND ISAN = ?");
        $delete_stmt->bind_param("ss", $usuario_nombre, $isan);
        
        if ($delete_stmt->execute()) {
            echo "Película eliminada exitosamente.<br>";
        } else {
            echo "Error al eliminar la película: " . $delete_stmt->error . "<br>";
        }
        
        $delete_stmt->close();
    } elseif (!empty($isan) && empty($nombre_pelicula) && $puntuacion === null) {
        $errors[] = "El ISAN existe, pero el nombre de la película está vacío. Para eliminar, ingresa el ISAN y deja el nombre vacío.";
    } else {
        // Consultar si el ISAN ya existe
        $stmt_check = $conn->prepare("SELECT * FROM peliculasUsuario WHERE usuario = ? AND ISAN = ?");
        $stmt_check->bind_param("ss", $usuario_nombre, $isan);
        $stmt_check->execute();
        $check_result = $stmt_check->get_result();

        if ($check_result->num_rows > 0) {
            // Actualizar si el ISAN ya existe
            $update_stmt = $conn->prepare("UPDATE peliculasUsuario SET nombre_pelicula = ?, puntuacion = ? WHERE usuario = ? AND ISAN = ?");
            $update_stmt->bind_param("siss", $nombre_pelicula, $puntuacion, $usuario_nombre, $isan);
            
            if ($update_stmt->execute()) {
                echo "Película actualizada exitosamente.<br>";
            } else {
                echo "Error al actualizar la película: " . $update_stmt->error . "<br>";
            }

            $update_stmt->close();
        } else {
            // Insertar nuevo registro si el ISAN no existe
            if ($puntuacion !== null) {
                $insert_stmt = $conn->prepare("INSERT INTO peliculasUsuario (usuario, ISAN, nombre_pelicula, puntuacion) VALUES (?, ?, ?, ?)");
                $insert_stmt->bind_param("sssi", $usuario_nombre, $isan, $nombre_pelicula, $puntuacion);
                
                if ($insert_stmt->execute()) {
                    echo "¡Película añadida exitosamente!<br>";
                } else {
                    echo "Error al añadir la película: " . $insert_stmt->error . "<br>";
                }

                $insert_stmt->close();
            } else {
                $errors[] = "Todos los campos deben completarse para añadir una nueva película.";
            }
        }

        $stmt_check->close();
    }

    // Mostrar mensajes de error si los hay
    foreach ($errors as $error) {
        echo "<span style='color: red;'>$error</span><br>";
    }

    // Procesar formulario para eliminar
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'delete') {
    $isan = isset($_POST['isan_delete']) ? trim($_POST['isan_delete']) : '';

    if (!empty($isan) && strlen($isan) == 8) {
        $delete_stmt = $conn->prepare("DELETE FROM peliculasUsuario WHERE usuario = ? AND ISAN = ?");
        $delete_stmt->bind_param("ss", $usuario_nombre, $isan);
        
        if ($delete_stmt->execute()) {
            echo "Película eliminada exitosamente.<br>";
        } else {
            echo "Error al eliminar la película: " . $delete_stmt->error . "<br>";
        }

        $delete_stmt->close();
    } else {
        echo "<span style='color: red;'>El ISAN debe tener 8 dígitos para eliminar.</span><br>";
    }
}
}
?>

<form action="" method="POST">
    <h3>Añadir/Actualizar Peliculas</h3>
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

<form action="" method="POST">
    <h3>Eliminar Película</h3>
    <input type="hidden" name="action" value="delete">
    <label for="isan_delete">ISAN:</label>
    <input type="text" id="isan_delete" name="isan_delete" required><br>

    <button type="submit">Eliminar Película</button>
</form>

<?php 
    // Cerrar la conexión
    $stmt->close();
    $conn->close();
?>