<?php
session_start();
include 'conexion.php'; // Asegúrate de que la conexión a la base de datos está configurada correctamente

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_profesional = $_POST['id_profesional'];
    $id_disponibilidad = $_POST['id_disponibilidad'];
    $fecha_cita = $_POST['fecha_cita'];
    $hora_inicio = $_POST['hora_inicio'];
    $hora_fin = $_POST['hora_fin'];

    // Insertar la cita en la base de datos
    $stmt = $conn->prepare("INSERT INTO citas (id_paciente, id_profesional, id_disponibilidad, fecha_cita, hora_inicio, hora_fin) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissss", $_SESSION['id'], $id_profesional, $id_disponibilidad, $fecha_cita, $hora_inicio, $hora_fin);
    $stmt->execute();

    // Actualizar el estado de la disponibilidad
    $stmt = $conn->prepare("UPDATE disponibilidad_profesionales SET estado = 'reservado' WHERE id_disponibilidad = ?");
    $stmt->bind_param("i", $id_disponibilidad);
    $stmt->execute();

    // Redirigir a una página de confirmación
    header('Location: panel_paciente.php?mensaje=Reserva exitosa');
    exit();
}
