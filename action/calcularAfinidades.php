<?php
// Requiere la lógica de IA y segmentación
require_once 'gestionImagenesIAAction.php';

// Archivos utilizados
$segmentacionFile = '../action/segmentacion.txt';
$afinidadesFile = '../action/afinidades.txt';

// Verificar si el archivo de segmentación existe
if (!file_exists($segmentacionFile)) {
    echo json_encode(['status' => 'error', 'message' => 'Archivo de segmentación no encontrado']);
    exit();
}

// Leer los datos de segmentación y encontrar la duración máxima
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

// Depuración: Verificar los datos de segmentación cargados
file_put_contents('debug.log', "Datos de Segmentación:\n" . print_r($segmentacionData, true), FILE_APPEND);

// Obtener criterios desde la IA
$urlImagen = 'https://www.ongvoluntariado.org/wp-content/uploads/2018/05/fauna-toucan_costa_rica.jpg';
$criteriosIA = procesarImagen($urlImagen);

// Depuración: Verificar la respuesta de la IA
file_put_contents('debug.log', "Respuesta de la IA:\n" . $criteriosIA . "\n", FILE_APPEND);

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
    // Si el formato es inesperado, registrar un error en el log
    file_put_contents('debug.log', "Error: Formato inesperado de la IA\n", FILE_APPEND);
}

// Depuración: Verificar los criterios procesados
file_put_contents('debug.log', "Criterios Procesados:\n" . print_r($criterios, true), FILE_APPEND);

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

// Depuración: Verificar el resultado final de afinidades
file_put_contents('debug.log', "Afinidades Calculadas:\n" . $afinidadesData, FILE_APPEND);

// Guardar las afinidades en el archivo
file_put_contents($afinidadesFile, $afinidadesData);

// Responder con éxito
echo json_encode(['status' => 'success', 'message' => 'Afinidades calculadas y guardadas correctamente.', 'afinidades' => $afinidadesData]);
?>
