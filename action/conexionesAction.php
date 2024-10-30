<?php
include_once "../business/conexionesBusiness.php";
$conexionesBusiness = new ConexionesBusiness();
require_once '../business/usuarioBusiness.php';
$usuarioBusiness = new UsuarioBusiness();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['cargarPerfiles'])) {

    $nombreUsuario = $_SESSION['nombreUsuario'];
    $usuarioId = $usuarioBusiness->getIdByName($nombreUsuario);
    $ruta = '../resources/afinidadesUsuarios';
    $nombreArchivo = 'dataAfinidad.dat';
    $carpetas = obtenerNombresDeCarpetasDesdeRuta($ruta);
    $archivoPersonal = [];
    $datosTotales = [];

    foreach ($carpetas as $carpeta) {
        $resultadoArchivo = leerDatosDesdeArchivo($ruta . '/' . $carpeta . '/' . $nombreArchivo);
        if (is_array($resultadoArchivo) && !isset($resultadoArchivo['success'])) {
            $datosTotales[count($datosTotales)] = $resultadoArchivo;
        }
    }

    // Verificar si el archivo personal existe
    $archivoPersonalPath = $ruta . '/' . $nombreUsuario . '/' .  $nombreArchivo;
    if (file_exists($archivoPersonalPath)) {
        $arregloArchivoPersonal = leerDatosDesdeArchivo($archivoPersonalPath);
        if (is_array($arregloArchivoPersonal) && !empty($arregloArchivoPersonal) && isset($arregloArchivoPersonal[0])) {
            $archivoPersonal = $arregloArchivoPersonal[0];
        } else {
            echo "No se encontraron registros o hubo un error al leer el archivo.";
            exit();
        }
    } else {
        $arregloArchivoPersonal = ['error' => 'Archivo personal no encontrado'];
        exit();
    }

    $criterio = $archivoPersonal['Criterio'] ?? null;
    $afinidad = $archivoPersonal['Afinidad'] ?? null;
    $genero = $archivoPersonal['Genero'] ?? null;
    $orientacionSexual = $archivoPersonal['OrientacionSexual'] ?? null;

    $profilingGenders = solicitudProfilingAlgorithm($genero, $orientacionSexual); // aquí obtengo los resultados de la solicitud del método que se encuentra en otro archivo

    $perfilesFiltrados = filterAffinityProfiles($datosTotales, $criterio, $afinidad, $genero, $orientacionSexual, $profilingGenders, $usuarioId);

    foreach ($perfilesFiltrados as $subArray) {
        foreach ($subArray as $item) {
            if (isset($item['UsuarioID'])) {
                $ids[] = $item['UsuarioID'];
            }
        }
    }

    $perfilesUsuariosComplemento = $conexionesBusiness->getAllTbPerfilesPorID($ids);

    $_SESSION['perfiles'] = unirElementosEnArregloUnico($perfilesUsuariosComplemento, $perfilesFiltrados);

    if (empty($_SESSION['perfiles'])) {
        echo json_encode(['success' => false, 'message' => 'No hay perfiles que coincidan con los criterios']);
        exit();
    }

    // Combinar los datos de perfiles
    echo json_encode([
        'success' => true
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Parámetro no válido']);
}

// Se debe corregir este método
function filterAffinityProfiles($allProfiles, $criterioParam, $porcentajeAfinidadParam, $generoParam, $orientacionSexualParam, $profilingGenders, $usuarioId)
{
    // Validar que los parámetros no estén vacíos
    if (empty($criterioParam) || empty($porcentajeAfinidadParam)) {
        return []; // Retorna un array vacío si no hay criterios
    }

    $criterioDividido = explode(',', $criterioParam);
    $porcentajeDividido = explode(',', $porcentajeAfinidadParam);

    foreach ($allProfiles as $indicePerfil => $subarreglo) {
        foreach ($subarreglo as $perfil) {
            // Solo procesar perfiles que no sean del mismo usuario
            if ($perfil['UsuarioID'] !== $usuarioId) {

                if ($perfil['Genero'] === $profilingGenders[0]['Genero'] && $perfil['OrientacionSexual'] === $profilingGenders[0]['Orientacion']) {
                    // Inicializar 'ponderado' y 'coincidencias' si no existen
                    if (!isset($allProfiles[$indicePerfil][0]['ponderado'])) {
                        $allProfiles[$indicePerfil][0]['ponderado'] = 0;
                    }
                    if (!isset($allProfiles[$indicePerfil][0]['coincidencias'])) {
                        $allProfiles[$indicePerfil][0]['coincidencias'] = '';
                    }

                    $criteriosPerfil = explode(',', $perfil['Criterio']); // Usar la clave correcta para criterio

                    foreach ($criterioDividido as $indiceCriterioDeseado => $criterioDeseado) {
                        foreach ($criteriosPerfil as $criterioPerfil) {
                            // Comparar después de limpiar los valores
                            if (strtolower(trim($criterioDeseado)) === strtolower(trim($criterioPerfil))) {
                                // Asignar el ponderado
                                $allProfiles[$indicePerfil][0]['ponderado'] += (float)$porcentajeDividido[$indiceCriterioDeseado];
                                $allProfiles[$indicePerfil][0]['coincidencias'] .= $criterioDeseado . ' [' . $porcentajeDividido[$indiceCriterioDeseado] . '%], ';
                            }
                        }
                    }
                }
            }
        }
    }

    // Filtrar los perfiles con un ponderado mayor al 30%
    $perfilesFiltrados = array_filter($allProfiles, function ($perfil) {
        return isset($perfil[0]['ponderado']) && $perfil[0]['ponderado'] > 30;
    });

    // Ordenar los perfiles filtrados de mayor a menor ponderado
    usort($perfilesFiltrados, function ($a, $b) {
        return $b[0]['ponderado'] <=> $a[0]['ponderado'];
    });

    // Limitar a los 20 perfiles con mayor ponderado
    return array_slice($perfilesFiltrados, 0, 20);
}


function leerDatosDesdeArchivo($nombreArchivo)
{
    if (!file_exists($nombreArchivo)) {
        return ['success' => false, 'message' => "El archivo $nombreArchivo no existe."];
    }

    $file = fopen($nombreArchivo, 'r');
    $resultados = []; // Array para almacenar todos los registros

    while (($linea = fgets($file)) !== false) {
        $linea = trim($linea);
        $pares = explode('|', $linea);
        $datosDeArchivo = []; // Inicializar array para cada registro

        foreach ($pares as $par) {
            if (strpos($par, ': ') !== false) {
                list($clave, $valor) = explode(': ', $par, 2);
                $datosDeArchivo[trim($clave)] = trim($valor); // Almacenar en un array asociativo
            }
        }

        // Agregar el registro al array de resultados
        $resultados[] = $datosDeArchivo;
    }

    fclose($file);
    return $resultados; // Retornar todos los registros como un array
}

function obtenerNombresDeCarpetasDesdeRuta($directorio)
{
    $elementos = scandir($directorio);

    $carpetas = [];
    foreach ($elementos as $elemento) {
        if ($elemento !== '.' && $elemento !== '..') {
            if (is_dir($directorio . '/' . $elemento)) {
                $carpetas[] = $elemento;
            }
        }
    }
    return $carpetas;
}

function solicitudProfilingAlgorithm($genero, $orientacion)
{
    // Preparar los datos en formato JSON
    $postData = json_encode([
        'genero' => $genero,
        'orientacion' => $orientacion,
    ]);

    $ch = curl_init();
    $url = 'http://' . $_SERVER['HTTP_HOST'] . '/LUV/algorithm' . '/profilingAlgorithm.php';

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($postData)
    ]);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Error en la solicitud: ' . curl_error($ch);
    } else {
        $result = json_decode($response, true);
        return $result;
    }
    curl_close($ch);
}

function unirElementosEnArregloUnico($array1, $array2)
{
    // Añadir campos a $array2 donde UsuarioID coincida con usuarioId en $array1
    foreach ($array1 as $item1) {
        foreach ($array2 as &$subArray) { // Usar referencia para modificar directamente
            foreach ($subArray as &$item2) {
                if ($item1['usuarioId'] == $item2['UsuarioID']) {
                    // Añadir los nuevos campos
                    $item2['criterio'] = $item1['criterio'];
                    $item2['valor'] = $item1['valor'];
                    $item2['nombreUsuario'] = $item1['nombreUsuario'];
                    $item2['porcentaje'] = $item1['porcentaje'];
                    $item2['pfp'] = $item1['pfp'];
                    $item2['estado'] = $item1['estado'];
                }
            }
        }
    }
    return $array2;
}
