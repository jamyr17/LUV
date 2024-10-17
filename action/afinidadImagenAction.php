<?php
require_once 'gestionImagenesIAAction.php';

$segmentacionFile = '../action/segmentacion.txt';
$afinidadesFile = '../action/afinidades.txt';
$logFile = 'debug.log';

// Obtener el método de la solicitud
$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($requestMethod === 'POST') {

    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['region']) && isset($input['duration']) && isset($input['zoomScale'])) {
        $region = $input['region'];
        $duration = $input['duration'];
        $zoomScale = $input['zoomScale'];

        // Leer el archivo actual
        $existingData = [];

        if (file_exists($segmentacionFile)) {
            $lines = file($segmentacionFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (preg_match('/Región: (\d+,\d+), Duración: (\d+) ms, ZoomScale: (\d+)/', $line, $matches)) {
                    $existingData[$matches[1]] = [
                        'duration' => (int) $matches[2],
                        'zoomScale' => (float) $matches[3],
                    ];
                }
            }
        }

        if (isset($existingData[$region])) {
            $existingData[$region]['duration'] += $duration;
        } else {
            $existingData[$region] = [
                'duration' => $duration,
                'zoomScale' => $zoomScale,
            ];
        }

        $newContent = '';
        foreach ($existingData as $regionKey => $data) {
            $newContent .= "Región: $regionKey, Duración: {$data['duration']} ms, ZoomScale: {$data['zoomScale']}" . PHP_EOL;
        }

        file_put_contents($segmentacionFile, $newContent);

        file_put_contents($logFile, "Segmentación guardada para la región $region\n", FILE_APPEND);

        echo json_encode(['status' => 'success', 'message' => 'Datos de segmentación guardados correctamente.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
    }

} elseif ($requestMethod === 'GET') {

    if (!file_exists($segmentacionFile)) {
        echo json_encode(['status' => 'error', 'message' => 'Archivo de segmentación no encontrado']);
        exit();
    }

    $lines = file($segmentacionFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $segmentacionData = [];
    $duracionMaxima = 0;

    foreach ($lines as $line) {
        if (preg_match('/Región: (\d+,\d+), Duración: (\d+) ms, ZoomScale: ([\d.]+)/', $line, $matches)) {
            $region = $matches[1];
            $duracion = (int) $matches[2];
            $zoomScale = (float) $matches[3];

            // Guardar la duración máxima para la normalización
            $duracionMaxima = max($duracionMaxima, $duracion);

            // Guardar los datos para uso posterior
            $segmentacionData[$region] = [
                'duracion' => $duracion,
                'zoomScale' => $zoomScale
            ];
        }
    }

    // Depuración: Guardar segmentación procesada
    file_put_contents($logFile, "Datos de segmentación procesados: " . print_r($segmentacionData, true), FILE_APPEND);

    $urlImagen = 'https://www.travelexcellence.com/wp-content/uploads/2020/09/CANOPY-1.jpg';
    $criteriosIA = procesarImagen($urlImagen);

    // Depuración: Verificar la respuesta de la IA
    file_put_contents($logFile, "Respuesta de la IA: $criteriosIA\n", FILE_APPEND);

    // Inicializar el array de criterios
    $criterios = [];

    // Procesar la respuesta de la IA con expresión regular para extraer criterios
    if (preg_match_all('/\((\d+),\s*(\d+)\),\s*([^,]+)/', $criteriosIA, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $match) {
            $fila = $match[1];
            $columna = $match[2];
            $criterio = trim($match[3]);

            // Normalizar las coordenadas a un formato "fila,columna" como en los datos de segmentación
            $region = "$fila,$columna";
            $criterios[$region] = $criterio;
        }
    } else {
        // Si la respuesta no tiene el formato esperado, agregar al log
        file_put_contents($logFile, "Error: Formato inesperado en la respuesta de la IA.\n", FILE_APPEND);
    }

    // Calcular las afinidades
    $afinidadesData = '';
    foreach ($segmentacionData as $region => $datos) {
        $duracion = $datos['duracion'];
        $zoomScale = $datos['zoomScale'];

        // Calcular afinidad usando la duración y el zoomScale
        $afinidad = ($duracion / $duracionMaxima) * 100 * $zoomScale;
        $afinidad = min(100, round($afinidad, 2)); // Limitar la afinidad al 100%

        // Combinar con el criterio de la IA o usar 'Sin criterio' si no se encuentra
        $criterio = isset($criterios[$region]) ? $criterios[$region] : 'Sin criterio';
        $afinidadesData .= "Criterio: $criterio, Región: $region, Afinidad: $afinidad%\n";
    }

    // Guardar las afinidades en el archivo
    file_put_contents($afinidadesFile, $afinidadesData);

    // Depuración: Guardar afinidades calculadas
    file_put_contents($logFile, "Afinidades calculadas: $afinidadesData\n", FILE_APPEND);

    // Responder con éxito
    echo json_encode(['status' => 'success', 'message' => 'Afinidades calculadas y guardadas correctamente.', 'afinidades' => $afinidadesData]);
}
?>