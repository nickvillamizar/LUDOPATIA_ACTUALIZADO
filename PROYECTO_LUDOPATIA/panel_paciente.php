<?php
session_start();
include 'conexion.php'; // Conexión a la base de datos

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['nombre_completo']) || !isset($_SESSION['id'])) {
    header("Location: login.php"); // Redirigir a login si no está autenticado
    exit();
}

$nombre_completo = $_SESSION['nombre_completo'];
$paciente_id = $_SESSION['id'];
$mensaje = ""; // Inicializar mensaje

// Guardar la entrada del diario si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['entrada_diario'])) {
    $contenido = $_POST['entrada_diario'];
    $fecha = date('Y-m-d'); // Fecha actual

    // Insertar la entrada del diario en la base de datos
    $stmt = $conn->prepare("INSERT INTO diarios (paciente_id, fecha, contenido) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $paciente_id, $fecha, $contenido);

    if ($stmt->execute()) {
        // Redirigir para evitar reenvío del formulario
        header("Location: panel_paciente.php"); 
        exit();
    } else {
        $mensaje = "Error al guardar la entrada del diario.";
    }

    $stmt->close();
}

// Obtener la disponibilidad de profesionales
$profesionales = [];
$stmt = $conn->query("SELECT p.id, p.nombre_completo, d.id_disponibilidad, d.fecha, d.hora_inicio, d.hora_fin FROM profesionales p
                      JOIN disponibilidad_profesionales d ON p.id = d.id_profesional
                      WHERE d.estado = 'disponible'");
while ($row = $stmt->fetch_assoc()) {
    $profesionales[] = $row;
}

// Guardar cita si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_profesional'])) {
    $id_profesional = $_POST['id_profesional'];
    $id_disponibilidad = $_POST['id_disponibilidad'];
    $fecha_cita = $_POST['fecha_cita'];
    $hora_inicio_cita = $_POST['hora_inicio'];
    $hora_fin_cita = $_POST['hora_fin'];

    // Insertar la cita en la base de datos
    $stmt = $conn->prepare("INSERT INTO citas (id_profesional, id_paciente, id_disponibilidad, fecha, hora_inicio, hora_fin) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiisss", $id_profesional, $paciente_id, $id_disponibilidad, $fecha_cita, $hora_inicio_cita, $hora_fin_cita);

    if ($stmt->execute()) {
        // Actualizar estado de disponibilidad
        $stmt = $conn->prepare("UPDATE disponibilidad_profesionales SET estado = 'reservado' WHERE id_disponibilidad = ?");
        $stmt->bind_param("i", $id_disponibilidad);
        $stmt->execute();
        $stmt->close(); // Cerrar el statement

        // Mensaje de confirmación
        $mensaje = "Cita reservada exitosamente.";
    } else {
        $mensaje = "Error al guardar la cita.";
    }
}

// Mostrar entradas de diario
$stmt = $conn->prepare("SELECT fecha, contenido FROM diarios WHERE paciente_id = ? ORDER BY fecha DESC");
$stmt->bind_param("i", $paciente_id);
$stmt->execute();
$result = $stmt->get_result();
$entradas_diario = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Obtener citas reservadas por el paciente (filtrando por paciente_id)
$citas_reservadas = [];
$stmt = $conn->prepare("SELECT c.fecha, c.hora_inicio, c.hora_fin, p.nombre_completo FROM citas c
                        JOIN profesionales p ON c.id_profesional = p.id
                        WHERE c.id_paciente = ?");
$stmt->bind_param("i", $paciente_id);
$stmt->execute();
$result = $stmt->get_result();
$citas_reservadas = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del Paciente</title>
    <link rel="stylesheet" type="text/css" href="panel_paciente.css">
</head>
<body>
    <video id="background-video" autoplay loop muted>
        <source src="videos/pasto.mp4" type="video/mp4">
        Tu navegador no soporta video.
    </video>

    <h1>Bienvenido, <?php echo htmlspecialchars($nombre_completo); ?>!</h1>
    <p>Este es su panel de apoyo para la ludopatía. Aquí encontrará recursos y herramientas para ayudarle en su proceso.</p>

    <h2>Diario Personal</h2>
    <p>Escriba lo que está sintiendo hoy o cualquier cosa que desee registrar en su diario.</p>

    <form action="panel_paciente.php" method="POST">
        <textarea name="entrada_diario" rows="5" cols="50" placeholder="Escriba su entrada del diario aquí..." required></textarea><br><br>
        <input type="submit" value="Guardar Entrada">
    </form>

    <?php
    // Mostrar mensaje si existe
    if (!empty($mensaje)) {
        echo "<p style='color:white;'>$mensaje</p>";
    }
    ?>

    <h3>Entradas de Diario Anteriores</h3>
    <ul>
        <?php
        // Mostrar las entradas de diario del paciente
        foreach ($entradas_diario as $entrada) {
            echo "<li><strong>" . htmlspecialchars($entrada['fecha']) . ":</strong> " . htmlspecialchars($entrada['contenido']) . "</li>";
        }
        ?>
    </ul>

    <h2>Reservar Cita</h2>
    <form method="POST" action="">
        <label for="profesional_id">Seleccionar Profesional:</label>
        <select name="id_profesional" id="profesional_id" required>
            <option value="">Seleccione un profesional</option>
            <?php foreach ($profesionales as $profesional): ?>
                <option value="<?php echo $profesional['id']; ?>" data-id="<?php echo $profesional['id_disponibilidad']; ?>" data-fecha="<?php echo $profesional['fecha']; ?>" data-hora-inicio="<?php echo $profesional['hora_inicio']; ?>" data-hora-fin="<?php echo $profesional['hora_fin']; ?>">
                    <?php echo htmlspecialchars($profesional['nombre_completo']) . " - " . $profesional['fecha'] . " " . $profesional['hora_inicio'] . " - " . $profesional['hora_fin']; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="hidden" name="id_disponibilidad" value="">
        <input type="hidden" name="fecha_cita" value="">
        <input type="hidden" name="hora_inicio" value="">
        <input type="hidden" name="hora_fin" value="">
        <input type="submit" value="Reservar Cita">
    </form>

    <h2>Citas Reservadas</h2>
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Hora de Inicio</th>
                <th>Hora de Fin</th>
                <th>Profesional</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Mostrar las citas reservadas por el paciente
            if (!empty($citas_reservadas)) {
                foreach ($citas_reservadas as $cita) {
                    echo "<tr>
                            <td>" . htmlspecialchars($cita['fecha']) . "</td>
                            <td>" . htmlspecialchars($cita['hora_inicio']) . "</td>
                            <td>" . htmlspecialchars($cita['hora_fin']) . "</td>
                            <td>" . htmlspecialchars($cita['nombre_completo']) . "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No hay citas reservadas.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <a href="logout.php">Cerrar sesión</a>

    <script>
        // Script para actualizar campos ocultos cuando se selecciona un profesional
        document.getElementById('profesional_id').addEventListener('change', function() {
            var selectedOption = this.options[this.selectedIndex];
            document.querySelector('input[name="id_disponibilidad"]').value = selectedOption.getAttribute('data-id');
            document.querySelector('input[name="fecha_cita"]').value = selectedOption.getAttribute('data-fecha');
            document.querySelector('input[name="hora_inicio"]').value = selectedOption.getAttribute('data-hora-inicio');
            document.querySelector('input[name="hora_fin"]').value = selectedOption.getAttribute('data-hora-fin');
        });
    </script>
</body>
</html>
