<?php
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

// Verificar que los datos existan y estén en el formato esperado
if (isset($input['region']) && isset($input['duration']) && isset($input['zoomScale'])) {
    $region = $input['region'];
    $duration = $input['duration'];
    $zoomScale = $input['zoomScale'];
    $usuarioId = 1; // Asume que se obtiene el ID del usuario autenticado
    $imagenNombre = 'nombre_imagen'; // Puedes obtener el nombre de la imagen de alguna variable

    // Conectar a la base de datos
    $conn = new mysqli('localhost', 'usuario', 'contraseña', 'base_datos');

    // Verificar si ya existe una entrada para la misma región
    $query = "SELECT * FROM tbzoomregiones WHERE usuario_id = ? AND imagen_nombre = ? AND region = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iss', $usuarioId, $imagenNombre, $region);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Si existe, actualizar la duración sumando
        $queryUpdate = "UPDATE tbzoomregiones SET duration = duration + ?, zoom_scale = ? WHERE usuario_id = ? AND imagen_nombre = ? AND region = ?";
        $stmtUpdate = $conn->prepare($queryUpdate);
        $stmtUpdate->bind_param('disiss', $duration, $zoomScale, $usuarioId, $imagenNombre, $region);
        $stmtUpdate->execute();
    } else {
        // Si no existe, insertar un nuevo registro
        $queryInsert = "INSERT INTO tbzoomregiones (usuario_id, imagen_nombre, region, duration, zoom_scale) VALUES (?, ?, ?, ?, ?)";
        $stmtInsert = $conn->prepare($queryInsert);
        $stmtInsert->bind_param('issid', $usuarioId, $imagenNombre, $region, $duration, $zoomScale);
        $stmtInsert->execute();
    }

    // Cerrar conexiones
    $stmt->close();
    $conn->close();

    // Responder con éxito
    echo json_encode(['status' => 'success', 'region' => $region, 'duration' => $duration, 'zoomScale' => $zoomScale]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Datos incompletos.']);
}
