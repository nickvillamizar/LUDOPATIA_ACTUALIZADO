<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" type="text/css" href="registro.css">
</head>
<body>
    <video autoplay muted loop id="background-video">
        <source src="videos/pasto.mp4" type="video/mp4">
        Su navegador no soporta videos.
    </video>

    <div class="content">
        <h2>Registro</h2>
        <form action="" method="post">
            <label for="tipo_usuario">¿Usted es:</label><br>
            <select name="tipo_usuario" required>
                <option value="">Seleccione...</option>
                <option value="paciente">Paciente</option>
                <option value="familiar">Familiar</option>
                <option value="profesional">Profesional</option>
            </select><br><br>

            <input type="submit" value="Continuar">
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $tipo_usuario = $_POST['tipo_usuario'];
            
            // Redirigir según el tipo de usuario
            if ($tipo_usuario == "paciente") {
                header("Location: registro_paciente.php");
                exit();
            } elseif ($tipo_usuario == "familiar") {
                header("Location: registro_familiar.php");
                exit();
            } elseif ($tipo_usuario == "profesional") {
                header("Location: registro_profesional.php");
                exit();
            }
        }
        ?>
    </div>
</body>
</html>
