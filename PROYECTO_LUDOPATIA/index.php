<!-- index.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Tipo de Usuario</title>
</head>
<body>
    <h2>Registro en "Apuesta por ti"</h2>
    <form action="registro_seleccion.php" method="POST">
        <label>¿Usted es?:</label><br>
        <input type="radio" name="tipo_usuario" value="paciente" required> Paciente<br>
        <input type="radio" name="tipo_usuario" value="familiar" required> Familiar<br>
        <input type="radio" name="tipo_usuario" value="profesional" required> Profesional<br>
        <button type="submit">Continuar</button>
    </form>
</body>
</html>
