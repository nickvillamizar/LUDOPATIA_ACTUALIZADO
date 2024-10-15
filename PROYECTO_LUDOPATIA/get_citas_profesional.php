<?php
session_start();
include 'conexion.php'; // Asegúrate de que la conexión a la base de datos está configurada correctamente

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['nombre_completo']) || !isset($_SESSION['id_profesional'])) {
    echo json_encode(['error' => 'No autenticado']);
    exit();
}

// Obtener las citas reservadas para el profesional
$citas = [];
$id_profesional = $_SESSION['id_profesional'];
$stmt = $conn->prepare("SELECT c.id, p.nombre_completo, c.fecha_cita, c.hora_inicio, c.hora_fin 
                         FROM citas c 
                         JOIN pacientes p ON c.id_paciente = p.id 
                         WHERE c.id_profesional = ?");
$stmt->bind_param("i", $id_profesional);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $citas[] = $row;
}

echo json_encode($citas);
