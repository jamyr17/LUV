<?php
require_once 'gestionImagenesIAAction.php';

require_once '../data/userAffinityData.php';
$userAffinityData = new UserAffinityData();

require_once '../business/usuarioBusiness.php';
$usuarioBusiness = new UsuarioBusiness();

$segmentacionFile = 'segmentacion.txt';
$afinidadesFile = 'afinidades.txt';
$databaseFile = 'database.txt';
$logFile = 'debug.log';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($requestMethod === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['region']) && isset($input['duration']) && isset($input['zoomScale'])) {
        $region = $input['region'];
        $duration = $input['duration'];
        $zoomScale = $input['zoomScale'];

        if (!file_exists($segmentacionFile)) {
            file_put_contents($segmentacionFile, "");
        }

        $existingData = [];

        $lines = file($segmentacionFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (preg_match('/Región: (\d+,\d+), Duración: (\d+) ms, ZoomScale: (\d+)/', $line, $matches)) {
                $existingData[$matches[1]] = [
                    'duration' => (int) $matches[2],
                    'zoomScale' => (float) $matches[3],
                ];
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
    try {
        if (!file_exists($segmentacionFile)) {
            echo json_encode(['status' => 'error', 'message' => 'Archivo de segmentación no encontrado']);
            exit();
        }
        
        $lines = file($segmentacionFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $segmentacionData = [];
        $duracionMaxima = 0;
        
        // Variables que guardarán todas las regiones, duraciones y zoom scales concatenadas
        $dataSegmentosToDB = '';
        $dataDuracionToDB = '';
        $dataZoomToDB = '';
        
        foreach ($lines as $line) {
            if (preg_match('/Región: (\d+,\d+), Duración: (\d+) ms, ZoomScale: ([\d.]+)/', $line, $matches)) {
                $region = $matches[1];
                $duracion = $matches[2];
                $zoomScale = $matches[3];
        
                $segmentacionData[$region] = [
                    'duracion' => $duracion,
                    'zoomScale' => $zoomScale
                ];
                    
                $duracionMaxima = max($duracionMaxima, $duracion);
        
                $dataSegmentosToDB .= $region . ';';
            }
        }
        
        $dataSegmentosToDB = rtrim($dataSegmentosToDB, ';');  // Elimina el último ';'
        $dataDuracionToDB = rtrim($dataDuracionToDB, ','); 
        $dataZoomToDB = rtrim($dataZoomToDB, ','); 
        
        // Guardar los datos en el archivo para depuración
        file_put_contents($databaseFile, "Para la database dataSegmentosToDB: $dataSegmentosToDB\n", FILE_APPEND);
        file_put_contents($databaseFile, "Para la database dataDuracionToDB: $dataDuracionToDB\n", FILE_APPEND);
        file_put_contents($databaseFile, "Para la database dataZoomToDB: $dataZoomToDB\n", FILE_APPEND);
        
        
        file_put_contents($logFile, "Datos de segmentación procesados: " . print_r($segmentacionData, true), FILE_APPEND);
        
        // Llamar a la función procesarImagen para obtener los datos de la IA
        $urlImagen = 'https://www.travelexcellence.com/wp-content/uploads/2020/09/CANOPY-1.jpg';
        $criteriosIA = procesarImagen($urlImagen);
        
        // Depuración: Verificar la respuesta de la IA
        file_put_contents($logFile, "Respuesta de la IA: $criteriosIA\n", FILE_APPEND);
        
        // AQUI INTEGRA LA FUNCIÓN obtenerCriterios:
        $criterios = obtenerCriterios($criteriosIA);
        
        // Calcular afinidades y combinar con los criterios de la IA
        $afinidadesData = '';
        
        $dataCriteriosToDB = '';
        $dataImagenUrlToDB = $urlImagen;
        $dataAfinidadToDB = '';
        $usuarioId = $usuarioBusiness->getIdByName($_SESSION['nombreUsuario']);
        
        // Iterar sobre los datos de segmentación y calcular afinidades (esto permanece igual)
        foreach ($segmentacionData as $region => $datos) {
            $duracion = $datos['duracion'];
            $zoomScale = $datos['zoomScale'];
        
            if ($duracionMaxima > 0) {
                $afinidad = ($duracion / $duracionMaxima) * 100;
                $afinidad = $afinidad * (0.5 + 0.5 * $zoomScale); // Limita el impacto del zoom
                $afinidad = min(100, round($afinidad, 2)); // Limitar la afinidad al 100%
            } else {
                $afinidad = 0; // Si duracionMaxima es 0, la afinidad debe ser 0
            }
            
            $afinidad = $afinidad * (0.5 + 0.5 * $zoomScale); // Limita el impacto del zoom
            $afinidad = min(100, round($afinidad, 2)); // Limitar la afinidad al 100%
        
            // Combinar con el criterio extraído de la IA o usar 'Sin criterio' si no se encuentra
            $criterio = isset($criterios[$region]) ? $criterios[$region] : 'Sin criterio';
            $afinidadesData .= "Criterio: $criterio, Región: $region, Afinidad: $afinidad%\n";

            $dataAfinidadToDB .= "$afinidad,";  // Concatenar los criterios
        
            $dataCriteriosToDB .= "$criterio,";  // Concatenar los criterios

            $dataDuracionToDB .= "$duracion,";  // Concatenar las duraciones
            
            $dataZoomToDB .= "$zoomScale,";  // Concatenar los zoom scales
        }
        
        // Limpiar la cadena de criterios para quitar el último separador ','
        $dataCriteriosToDB = rtrim($dataCriteriosToDB, ',');
        
        file_put_contents($databaseFile, "Para la database dataCriteriosToDB: $dataCriteriosToDB\n", FILE_APPEND);
        file_put_contents($databaseFile, "Para la database dataImagenUrlToDB: $dataImagenUrlToDB\n", FILE_APPEND);
        file_put_contents($databaseFile, "Para la database dataAfinidadToDB: $dataAfinidadToDB\n", FILE_APPEND);
        file_put_contents($databaseFile, "Para la database usuarioId: $usuarioId\n", FILE_APPEND);
        
        // Guardar las afinidades en el archivo (esto permanece igual)
        file_put_contents($afinidadesFile, $afinidadesData);
        
        // Depuración: Guardar las afinidades calculadas
        file_put_contents($logFile, "Afinidades calculadas: $afinidadesData\n", FILE_APPEND);
        
        // Guardar los datos en la base de datos
        if ($userAffinityData->insertSegmentacion($dataImagenUrlToDB, $dataSegmentosToDB, $dataDuracionToDB, $dataZoomToDB, $dataCriteriosToDB, $dataAfinidadToDB,$usuarioId)) {
            echo json_encode(['status' => 'success', 'message' => 'Afinidad registrada de manera correcta.', 'afinidades' => $afinidadesData]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al registrar afinidad']);
        }
        

        // Responder con éxito
        echo json_encode(['status' => 'success', 'message' => 'Afinidades calculadas correctamente', 'afinidades' => $afinidadesData]);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

?>