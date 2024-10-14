<?php
header('Content-Type: application/json');

// Obtener el contenido de la solicitud
$input = json_decode(file_get_contents('php://input'), true);
error_log('Input data: ' . print_r($input, true));

// Verificar que los datos existan y estén en el formato esperado
if (isset($input['region']) && isset($input['duration']) && isset($input['zoomScale'])) {
    $region = $input['region'];
    $duration = $input['duration'];
    $zoomScale = $input['zoomScale'];

    // Guardar los datos en un archivo de texto
    $logData = "Region: $region, Duration: $duration ms, ZoomScale: $zoomScale" . PHP_EOL;
    file_put_contents('log.txt', $logData, FILE_APPEND);
    error_log('Data saved to log.txt');

    // Responder con los datos recibidos
    $response = [
        'status' => 'success',
        'region' => $region,
        'duration' => $duration,
        'zoomScale' => $zoomScale
    ];

    echo json_encode($response);
} else {
    error_log('Incomplete data received');
    echo json_encode(['status' => 'error', 'message' => 'Datos incompletos.']);
}
?>
