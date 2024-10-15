<form action="registro.php" method="POST">
    <label for="tipo">Seleccione su tipo de usuario:</label>
    <select name="tipo" id="tipo">
        <option value="paciente">Paciente</option>
        <option value="familiar">Familiar</option>
        <option value="profesional">Profesional</option>
    </select>

    <!-- Campos comunes -->
    <input type="text" name="nombre_completo" placeholder="Nombre completo" required>
    <input type="text" name="cedula" placeholder="Cédula" required>
    <input type="email" name="correo" placeholder="Correo" required>
    <input type="text" name="celular" placeholder="Celular">
    <input type="text" name="pais" placeholder="País">
    <input type="text" name="ciudad" placeholder="Ciudad">
    <input type="text" name="direccion" placeholder="Dirección">
    <input type="password" name="contrasena" placeholder="Contraseña" required>

    <!-- Campos específicos para paciente -->
    <div id="paciente" style="display: none;">
        <textarea name="antecedentes" placeholder="Antecedentes"></textarea>
        <input type="text" name="nombre_acompanante" placeholder="Nombre del acompañante">
        <input type="text" name="telefono_acompanante" placeholder="Teléfono del acompañante">
    </div>

    <!-- Campos específicos para familiar -->
    <div id="familiar" style="display: none;">
        <input type="text" name="id_paciente" placeholder="Cédula del paciente">
    </div>

    <!-- Campos específicos para profesional -->
    <div id="profesional" style="display: none;">
        <input type="text" name="numero_tarjeta" placeholder="Número de tarjeta profesional">
    </div>

    <button type="submit">Registrarse</button>
</form>

<script>
    // Mostrar campos adicionales dependiendo del tipo de usuario
    document.getElementById('tipo').addEventListener('change', function () {
        document.getElementById('paciente').style.display = (this.value === 'paciente') ? 'block' : 'none';
        document.getElementById('familiar').style.display = (this.value === 'familiar') ? 'block' : 'none';
        document.getElementById('profesional').style.display = (this.value === 'profesional') ? 'block' : 'none';
    });
</script>
