<?php
require_once '../data/logicaArchivosData.php';

$logicaArchivos = new logicaArchivosData();

try {
    // Obtener todos los criterios
    $criterios = $logicaArchivos->obtenerCriterios();
    
    // Verificar si hay criterios antes de continuar
    if (empty($criterios)) {
        echo json_encode(['error' => 'No se encontraron criterios.']);
        exit();
    }

    $result = [];

    // Iterar sobre los criterios y obtener valores asociados
    foreach ($criterios as $criterio) {
        $valores = $logicaArchivos->obtenerValoresDeCriterio($criterio);
        
        // Si se encuentran valores, agregarlos al resultado, de lo contrario, asignar un array vacío
        $result[$criterio] = ($valores !== null) ? $valores : [];
    }

    // Devolver el resultado como JSON
    header('Content-Type: application/json');
    echo json_encode($result);

} catch (Exception $e) {
    // Manejo de errores en la obtención de criterios y valores
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Error al obtener criterios y valores: ' . $e->getMessage()]);
    exit();
}
