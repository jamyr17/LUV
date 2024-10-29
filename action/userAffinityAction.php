<?php
require_once 'gestionImagenesIAAction.php';
require_once '../data/userAffinityData.php';
$userAffinityData = new UserAffinityData();
require_once '../business/usuarioBusiness.php';
$usuarioBusiness = new UsuarioBusiness();

$segmentacionFile = 'segmentacion.txt';
$afinidadesFile = 'afinidades.txt';

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

        $dataSegmentosToDB = rtrim($dataSegmentosToDB, ';'); // Elimina el último ';'

        $urlImagen = 'https://www.travelexcellence.com/wp-content/uploads/2020/09/CANOPY-1.jpg';
        $criteriosIA = procesarImagen($urlImagen);

        $criterios = obtenerCriterios($criteriosIA);

        $afinidadesData = '';
        $dataCriteriosToDB = '';
        $dataImagenUrlToDB = $urlImagen;
        $dataAfinidadToDB = '';
        $usuarioId = $usuarioBusiness->getIdByName($_SESSION['nombreUsuario']);

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

            // Combinar con el criterio extraído de la IA o usar 'Sin criterio' si no se encuentra
            $criterio = isset($criterios[$region]) ? $criterios[$region] : 'Sin criterio';
            $afinidadesData .= "Criterio: $criterio, Región: $region, Afinidad: $afinidad%\n";

            $dataAfinidadToDB .= "$afinidad,";  // Concatenar las afinidades
            $dataCriteriosToDB .= "$criterio,"; // Concatenar los criterios
            $dataDuracionToDB .= "$duracion,";  // Concatenar las duraciones
            $dataZoomToDB .= "$zoomScale,";     // Concatenar los zoom scales
        }

        $dataCriteriosToDB = rtrim($dataCriteriosToDB, ',');

        file_put_contents($afinidadesFile, $afinidadesData);

        $jsonResponse = ['status' => '', 'message' => '', 'afinidades' => $afinidadesData];

        if ($userAffinityData->checkIfExists($dataImagenUrlToDB, $usuarioId)) {
            if ($userAffinityData->updateSegmentacionsingeneroorientacion($dataImagenUrlToDB, $dataDuracionToDB, $dataZoomToDB, $dataCriteriosToDB, $dataAfinidadToDB, $usuarioId)) {
                $jsonResponse['status'] = 'success';
                $jsonResponse['message'] = 'Afinidad actualizada de manera correcta.';
            } else {
                $jsonResponse['status'] = 'error';
                $jsonResponse['message'] = 'Error al actualizar afinidad.';
            }
        } else {
            // Guardar los datos en la base de datos
            if ($userAffinityData->insertSegmentacionwithoutGeneroOrientacion($dataImagenUrlToDB, $dataSegmentosToDB, $dataDuracionToDB, $dataZoomToDB, $dataCriteriosToDB, $dataAfinidadToDB, $usuarioId)) {
                $jsonResponse['status'] = 'success';
                $jsonResponse['message'] = 'Afinidad registrada de manera correcta.';
            } else {
                $jsonResponse['status'] = 'error';
                $jsonResponse['message'] = 'Error al registrar afinidad.';
            }
        }

        // Enviar una única respuesta JSON
        echo json_encode($jsonResponse);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}