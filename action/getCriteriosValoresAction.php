<?php
require_once '../data/logicaArchivosData.php';

$logicaArchivos = new logicaArchivosData();

try {
    // Obtener todos los criterios
    $criterios = $logicaArchivos->obtenerCriterios();
    
    if (empty($criterios)) {
        echo json_encode(['error' => 'No se encontraron criterios.']);
        exit();
    }

    $result = [];
    // Obtener valores para cada criterio encontrado
    foreach ($criterios as $criterio) {
        $valores = $logicaArchivos->obtenerValoresDeCriterio($criterio);
        if ($valores !== null) {
            $result[$criterio] = $valores;
        } else {
            $result[$criterio] = []; // Devolver un array vacío si no se encuentran valores
        }
    }

    // Devolver el resultado en formato JSON
    echo json_encode($result);

} catch (Exception $e) {
    echo json_encode(['error' => 'Error al obtener criterios y valores: ' . $e->getMessage()]);
}
