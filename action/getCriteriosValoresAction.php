<?php
include_once '../data/logicaArchivosData.php';

$logicaArchivos = new LogicaArchivosData();

if (isset($_GET['type'])) {
    $type = $_GET['type'];
    error_log("Tipo de solicitud: " . $type);  // Depuración
    $data = [];

    if ($type == 'criterios') {
        // Obtener criterios desde los archivos .dat
        $data = $logicaArchivos->obtenerCriterios();
    } elseif ($type == 'valores' && isset($_GET['criterio'])) {
        $criterio = $_GET['criterio'];
        error_log("Criterio recibido: " . $criterio);  // Depuración

        // Obtener valores para el criterio específico
        $data = $logicaArchivos->obtenerValoresDeCriterio($criterio);
        error_log("Valores obtenidos para el criterio $criterio: " . print_r($data, true));  // Depuración
    }

    // Devolver los datos como JSON
    header('Content-Type: application/json');
    echo json_encode($data);
}
?>
