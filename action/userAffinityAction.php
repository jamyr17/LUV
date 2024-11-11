<?php
require_once 'gestionImagenesIAAction.php';
require_once '../data/userAffinityData.php';
$userAffinityData = new UserAffinityData();
require_once '../business/usuarioBusiness.php';
$usuarioBusiness = new UsuarioBusiness();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$requestMethod = $_SERVER['REQUEST_METHOD'];


if ($requestMethod === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!empty($input['imageURL']) && isset($input['region'], $input['duration'], $input['zoomScale'])) {
        $imageURL = $input['imageURL'];

        if (strpos($imageURL, 'http://localhost/LUV') !== false) {
            $imageURL = str_replace('http://localhost/LUV', '', $imageURL);
            $imageURL = '..' . $imageURL;
        }

        $region = $input['region'];
        $duration = $input['duration'];
        $zoomScale = $input['zoomScale'];
        $nombreUsuario = $_SESSION['nombreUsuario'];
        $directorioUsuario = "../resources/afinidadesUsuarios/$nombreUsuario";

        if (!is_dir($directorioUsuario)) {
            mkdir($directorioUsuario, 0777, true);
        }

        $segmentacionFile = "$directorioUsuario/dataSegmentacion.dat";
        $fileData = [];
        if (file_exists($segmentacionFile)) {
            $lines = file($segmentacionFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos($line, "ImagenURL: $imageURL") === 0) {
                    $fileData[$imageURL] = $line;
                } else {
                    $fileData[] = $line;
                }
            }
        }

        $fileData[$imageURL] = actualizarDatosDeImagen($fileData[$imageURL] ?? '', $imageURL, $region, $duration, $zoomScale);
        file_put_contents($segmentacionFile, implode(PHP_EOL, $fileData) . PHP_EOL, LOCK_EX);

        echo json_encode(['status' => 'success', 'message' => 'Datos de segmentación guardados correctamente.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Datos incompletos o URL no válida']);
    }
    exit();
} elseif ($requestMethod === 'GET') {
    try {
        if (!isset($_GET['imageURL'])) {
            echo json_encode(['status' => 'error', 'message' => 'URL de la imagen no especificada']);
            exit();
        }

        $urlImagen = $_GET['imageURL'];
        $nombreUsuario = $_SESSION['nombreUsuario'];
        $usuarioId = $usuarioBusiness->getIdByName($nombreUsuario);

        // Ruta de los archivos
        $segmentacionFile = "../resources/afinidadesUsuarios/$nombreUsuario/dataSegmentacion.dat";
        $criteriosFile = '../resources/img/criteriosImagenes.dat';

        // Verificar si los archivos existen
        if (!file_exists($segmentacionFile) || !file_exists($criteriosFile)) {
            echo json_encode(['status' => 'error', 'message' => 'Archivos de segmentación o criterios no encontrados']);
            exit();
        }

        // Leer segmentación de dataSegmentacion.dat
        $segmentacionData = [];
        $lineas = file($segmentacionFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lineas as $linea) {
            if (strpos($linea, "ImagenURL: $urlImagen") === 0) {
                preg_match('/Region: (.*?) \| Duracion: (.*?) \| ZoomScale: (.*)/', $linea, $matches);
                $regiones = explode(';', $matches[1]);
                $duraciones = explode(',', $matches[2]);
                $zoomScales = explode(',', $matches[3]);

                // Crear array de datos de segmentación
                foreach ($regiones as $index => $region) {
                    $segmentacionData[$region] = [
                        'duracion' => (float)$duraciones[$index],
                        'zoomScale' => (float)$zoomScales[$index]
                    ];
                }
                break;
            }
        }

        if (empty($segmentacionData)) {
            echo json_encode(['status' => 'error', 'message' => 'URL no encontrada en dataSegmentacion.dat']);
            exit();
        }

        // Duración máxima
        $duracionMaxima = max(array_column($segmentacionData, 'duracion'));

        // Obtener criterios desde criteriosImagenes.dat
        $criterios = [];
        $lineas = file($criteriosFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lineas as $linea) {
            if (strpos($linea, "ImagenURL: $urlImagen") === 0) {
                preg_match('/Region: (.*?) \| Criterio: (.*)/', $linea, $matches);
                $regionesCriterios = explode(';', $matches[1]);
                $criteriosLista = explode(',', $matches[2]);

                foreach ($regionesCriterios as $index => $region) {
                    $criterios[$region] = $criteriosLista[$index] ?? 'Sin criterio';
                }
                break;
            }
        }

        // Calcular afinidad y crear datos para guardar en .dat
        $afinidadesData = '';
        $dataCriteriosToDB = '';
        $dataDuracionToDB = '';
        $dataZoomToDB = '';
        $dataAfinidadToDB = '';
        foreach ($segmentacionData as $region => $datos) {
            $duracion = $datos['duracion'];
            $zoomScale = $datos['zoomScale'];

            if ($duracionMaxima > 0) {
                $afinidad = ($duracion / $duracionMaxima) * 100;
                $afinidad *= (0.5 + 0.5 * $zoomScale);
                $afinidad = min(100, round($afinidad, 2));
            } else {
                $afinidad = 0;
            }

            $criterio = $criterios[$region] ?? 'Sin criterio';
            $afinidadesData .= "Criterio: $criterio, Región: $region, Afinidad: $afinidad%\n";
            $dataCriteriosToDB .= "$criterio,";
            $dataDuracionToDB .= "$duracion,";
            $dataZoomToDB .= "$zoomScale,";
            $dataAfinidadToDB .= "$afinidad,";
        }

        // Formatear datos
        $dataCriteriosToDB = rtrim($dataCriteriosToDB, ',');
        $dataDuracionToDB = rtrim($dataDuracionToDB, ',');
        $dataZoomToDB = rtrim($dataZoomToDB, ',');
        $dataAfinidadToDB = rtrim($dataAfinidadToDB, ',');

        $dataImagenUrlToDB = $urlImagen;
        $dataSegmentosToDB = implode(';', array_keys($segmentacionData));

        // Detalles adicionales
        $genero = "Masculino";
        $orientacionSexual = "Heterosexual";
        $estado = 1;
        $idRegistro = 1;

        // Línea para el archivo .dat
        $lineaDat = "ID: $idRegistro|UsuarioID: $usuarioId|ImagenURL: $dataImagenUrlToDB|Region: $dataSegmentosToDB|Duracion: $dataDuracionToDB|ZoomScale: $dataZoomToDB|Criterio: $dataCriteriosToDB|Afinidad: $dataAfinidadToDB|Genero: $genero|OrientacionSexual: $orientacionSexual|Estado: $estado" . PHP_EOL;

        // Guardar en el archivo .dat
        $directorioUsuario = "../resources/afinidadesUsuarios/$nombreUsuario";
        if (!is_dir($directorioUsuario)) {
            mkdir($directorioUsuario, 0777, true);
        }
        $rutaArchivoDat = "$directorioUsuario/dataAfinidad.dat";
        file_put_contents($rutaArchivoDat, $lineaDat, FILE_APPEND | LOCK_EX);

        // Respuesta JSON
        echo json_encode(['status' => 'success', 'message' => 'Datos de afinidad guardados en archivo .dat.', 'afinidades' => $afinidadesData]);

    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else if ($_POST['leerDAT']) {
    /*
    La ESTRUCTURA del ARCHIVO DAT será la siguiente

    ID: 1|UsuarioID: 12|ImagenURL: https://www.travelexcellence.com/wp-content/upload...|Region: 1,3;1,2;1,1;2,1;2,2;2,3;3,3;3,2;3,1|Duracion: 6368|ZoomScale: 1,1,1,1,1,1,1,1,1,|Criterio: Sin criterio,Sin criterio,Sin criterio,Sin criterio...|Afinidad: 17.41|Genero: Femenino|OrientacionSexual: Heterosexual|Estado: 1    
    */
    
    $usuarioId = $usuarioBusiness->getIdByName($_SESSION['nombreUsuario']);
    $ruta = '../resources/afinidadesUsuarios';
    $archivos = obtenerArchivosDesdeRuta($ruta);
    $datosTotales = []; // Array para almacenar todos los datos leídos

    // Leer todos los archivos .dat
    foreach ($archivos as $archivo) {
        $datosTotales['archivo' . count($datosTotales)] = leerDatosDesdeArchivo($ruta . '/' . $archivo);
    }

    // Verificar si el archivo personal existe
    $archivoPersonalPath = $ruta . '/' . $usuarioId . '.dat';
    if (file_exists($archivoPersonalPath)) {
        $archivoPersonal = leerDatosDesdeArchivo($archivoPersonalPath);
    } else {
        // Manejar el caso en que no existe el archivo personal
        $archivoPersonal = ['error' => 'Archivo personal no encontrado'];
    }
    
    // Construir la respuesta JSON
    $jsonResponse = [
        'status' => 'success',
        'message' => 'Datos cargados correctamente',
        'archivos' => $datosTotales,
        'archivoPersonal' => $archivoPersonal
    ];

    // Enviar la respuesta JSON
    header('Content-Type: application/json');
    echo json_encode($jsonResponse);
    exit; // Terminar el script después de enviar la respuesta
}

function leerDatosDesdeArchivo($nombreArchivo) {
    if (!file_exists($nombreArchivo)) {
        die(json_encode(['success' => false, 'message' => "El archivo $nombreArchivo no existe."])); // Respuesta JSON en caso de error
    }

    $file = fopen($nombreArchivo, 'r');
    $resultados = []; // Array para almacenar todos los registros

    while (($linea = fgets($file)) !== false) {
        $linea = trim($linea);
        $pares = explode('|', $linea);

        // Inicializar variables para cada registro
        $datosDeArchivo = [];

        foreach ($pares as $par) {
            if (strpos($par, ': ') !== false) { // Verificar que el formato sea correcto
                list($clave, $valor) = explode(': ', $par, 2);
                $clave = trim($clave);
                $valor = trim($valor);
                $datosDeArchivo[$clave] = $valor; // Almacenar en un array asociativo
            }
        }

        // Agregar el registro al array de resultados
        $resultados[] = $datosDeArchivo;
    }

    fclose($file);
    return $resultados; // Retornar todos los registros como un array
}

function obtenerArchivosDesdeRuta($ruta) {
    if (!is_dir($ruta)) {
        die(json_encode(['success' => false, 'message' => "La ruta $ruta no es válida."])); // Respuesta JSON en caso de error
    }

    $archivos = scandir($ruta);
    $archivosDat = [];

    foreach ($archivos as $archivo) {
        if (pathinfo($archivo, PATHINFO_EXTENSION) === 'dat') {
            $archivosDat[] = $archivo;
        }
    }

    return $archivosDat;
}
function actualizarDatosDeImagen($lineaExistente, $imageURL, $region, $duration, $zoomScale) {
    $regionsOrder = ['1,1', '1,2', '1,3', '2,1', '2,2', '2,3', '3,1', '3,2', '3,3'];
    $durations = array_fill(0, count($regionsOrder), 0); 
    $zoomScales = array_fill(0, count($regionsOrder), 1); 
    $criterios = '';

    if ($lineaExistente) {
        preg_match('/Region: (.*?) \| Duracion: (.*?) \| ZoomScale: (.*?)($|\| Criterio: (.*))/', $lineaExistente, $matches);
        $existingRegions = explode(';', $matches[1]);
        $existingDurations = explode(',', $matches[2]);
        $existingZoomScales = explode(',', $matches[3]);
        $criterios = $matches[5] ?? ''; 

        foreach ($existingRegions as $index => $existingRegion) {
            $key = array_search($existingRegion, $regionsOrder);
            if ($key !== false) {
                $durations[$key] = $existingDurations[$index] ?? 0;
                $zoomScales[$key] = $existingZoomScales[$index] ?? 1;
            }
        }
    }

    $key = array_search($region, $regionsOrder);
    if ($key !== false) {
        $durations[$key] = $duration;
        $zoomScales[$key] = $zoomScale;
    }

    if (empty($criterios)) {
        $criteriosFile = '../resources/img/criteriosImagenes.dat';
        if (file_exists($criteriosFile)) {
            $lineasCriterios = file($criteriosFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lineasCriterios as $linea) {
                if (strpos($linea, "ImagenURL: $imageURL") === 0) {
                    preg_match('/Criterio: (.*)/', $linea, $matchesCriterio);
                    $criterios = $matchesCriterio[1] ?? 'Sin criterio';
                    break;
                }
            }
        }
    }

    return "ImagenURL: $imageURL | Region: " . implode(';', $regionsOrder) .
           " | Duracion: " . implode(',', $durations) .
           " | ZoomScale: " . implode(',', $zoomScales) .
           ($criterios ? " | Criterio: $criterios" : "");
}

