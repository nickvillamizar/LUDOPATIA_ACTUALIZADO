<?php
session_start();
include 'conexion.php'; // Asegúrate de que la conexión a la base de datos está configurada correctamente

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['nombre_completo']) || !isset($_SESSION['id'])) {
    echo json_encode(['error' => 'No autenticado']);
    exit();
}

// Obtener la disponibilidad de profesionales
$profesionales = [];
$stmt = $conn->query("SELECT p.id, p.nombre_completo, d.id_disponibilidad, d.fecha, d.hora_inicio, d.hora_fin FROM profesionales p
                      JOIN disponibilidad_profesionales d ON p.id = d.id_profesional
                      WHERE d.estado = 'disponible'");
while ($row = $stmt->fetch_assoc()) {
    $profesionales[] = $row;
}

echo json_encode($profesionales);
