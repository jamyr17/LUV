<?php
require_once 'gestionImagenesIAAction.php';

$segmentacionFile = 'segmentacion.txt';
$logFile = 'afinidades.txt';
$factorAfinidad = 0.1; // Factor para ajustar la afinidad calculada

// Verificar si el archivo de segmentación existe
if (!file_exists($segmentacionFile)) {
    echo json_encode(['status' => 'error', 'message' => 'Archivo de segmentación no encontrado']);
    exit();
}

// Leer los datos de segmentación
$lines = file($segmentacionFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$afinidades = [];
foreach ($lines as $line) {
    // Extraer datos de la línea (asumiendo el formato: Región, Duración y ZoomScale)
    if (preg_match('/Región: (\d+,\d+), Duración: (\d+) ms, ZoomScale: ([\d.]+)/', $line, $matches)) {
        $region = $matches[1];
        $duration = (int) $matches[2];
        $zoomScale = (float) $matches[3];

        // Calcular afinidad basada en la duración y el zoom
        $afinidad = min(100, $duration * $zoomScale * $factorAfinidad);
        $afinidades[$region] = $afinidad;
    }
}

// Obtener criterios desde la IA (puede ser un servicio que tu desarrolles)
$criterios = obtenerCriteriosIA('https://faunacr.com/wp-content/uploads/2022/04/shutterstock_1249664152-1-scaled.jpg');

// Combinar criterios con afinidades y guardar en un archivo
$logData = '';
foreach ($criterios as $region => $criterio) {
    $afinidad = isset($afinidades[$region]) ? $afinidades[$region] : 0;
    $logData .= "Criterio: $criterio, Región: $region, Afinidad: $afinidad%\n";
}

// Guardar los resultados en el archivo de texto
file_put_contents($logFile, $logData);

// Responder con éxito
echo json_encode(['status' => 'success', 'message' => 'Afinidades calculadas y guardadas correctamente.']);
?>
