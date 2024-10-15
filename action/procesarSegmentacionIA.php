<?php
// Importar las funciones de la IA desde gestionImagenesIAAction.php
require_once 'gestionImagenesIAAction.php';

// Obtener los datos enviados en formato JSON (de la imagen)
$input = json_decode(file_get_contents('php://input'), true);

// Verificar que los datos estén presentes
if (isset($input['region']) && isset($input['duration']) && isset($input['zoomScale'])) {
    $region = $input['region'];
    $duration = $input['duration'];
    $zoomScale = $input['zoomScale'];

    // Leer el archivo actual de segmentación
    $filePath = '../action/segmentacion.txt';
    $existingData = [];

    if (file_exists($filePath)) {
        // Leer el archivo línea por línea y almacenar los datos
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            // Extraer la región, duración y zoomScale de cada línea
            if (preg_match('/Región: (\d+,\d+), Duración: (\d+) ms, ZoomScale: (\d+)/', $line, $matches)) {
                $existingData[$matches[1]] = [
                    'duration' => (int)$matches[2],
                    'zoomScale' => (float)$matches[3],
                ];
            }
        }
    }

    // Actualizar o agregar la duración de la región
    if (isset($existingData[$region])) {
        // Si la región ya existe, sumar la duración
        $existingData[$region]['duration'] += $duration;
    } else {
        // Si la región no existe, agregarla
        $existingData[$region] = [
            'duration' => $duration,
            'zoomScale' => $zoomScale,
        ];
    }

    // Formatear los datos y volver a escribir el archivo
    $newContent = '';
    foreach ($existingData as $regionKey => $data) {
        $newContent .= "Región: $regionKey, Duración: {$data['duration']} ms, ZoomScale: {$data['zoomScale']}" . PHP_EOL;
    }

    // Guardar los datos actualizados en el archivo
    file_put_contents($filePath, $newContent);

    // Llamar a la IA para obtener criterios de la imagen
    $criterios = obtenerCriteriosIA('https://faunacr.com/wp-content/uploads/2022/04/shutterstock_1249664152-1-scaled.jpg');

    // Responder con éxito y enviar los criterios de la IA
    echo json_encode(['status' => 'success', 'message' => 'Datos actualizados correctamente', 'criterios' => $criterios]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
}
