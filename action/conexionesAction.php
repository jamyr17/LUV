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
    $archivos = obtenerArchivosDesdeRuta($ruta);

    $datosTotales = [];

    foreach ($archivos as $archivo) {
        $resultadoArchivo = leerDatosDesdeArchivo($ruta . '/' . $archivo);
        if (is_array($resultadoArchivo) && !isset($resultadoArchivo['success'])) {
            $datosTotales[count($datosTotales)] = $resultadoArchivo;
        }
    }

    // Verificar si el archivo personal existe
    $archivoPersonalPath = $ruta . '/' . $nombreUsuario . '.dat';
    if (file_exists($archivoPersonalPath)) {
        $arregloArchivoPersonal = leerDatosDesdeArchivo($archivoPersonalPath);
        if (is_array($arregloArchivoPersonal) && !empty($arregloArchivoPersonal) && isset($arregloArchivoPersonal[0])) {
            // Asumiendo que quieres obtener el primer registro del archivo
            $primerRegistro = $arregloArchivoPersonal[0];

            $criterio = $primerRegistro['Criterio'] ?? null;
            $afinidad = $primerRegistro['Afinidad'] ?? null;
        } else {
            echo "No se encontraron registros o hubo un error al leer el archivo.";
        }
    } else {
        // Manejar el caso en que no existe el archivo personal
        $arregloArchivoPersonal = ['error' => 'Archivo personal no encontrado'];
    }

    $_SESSION['perfiles'] = filterAffinityProfiles($datosTotales, $criterio, $afinidad, $usuarioId);

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
function filterAffinityProfiles($allProfiles, $criterioParam, $porcentajeParam, $usuarioId)
{
    // Validar que los parámetros no estén vacíos
    if (empty($criterioParam) || empty($porcentajeParam)) {
        return []; // Retorna un array vacío si no hay criterios
    }

    $criterioDividido = explode(',', $criterioParam);
    $porcentajeDividido = explode(',', $porcentajeParam);

    foreach ($allProfiles as $indicePerfil => $perfil) {
        // Solo procesar perfiles que no sean del mismo usuario
        if ($perfil['UsuarioID'] !== $usuarioId) {
            // Inicializar 'ponderado' y 'coincidencias' si no existen
            if (!isset($allProfiles[$indicePerfil]['ponderado'])) {
                $allProfiles[$indicePerfil]['ponderado'] = 0;
            }
            if (!isset($allProfiles[$indicePerfil]['coincidencias'])) {
                $allProfiles[$indicePerfil]['coincidencias'] = '';
            }

            $criteriosPerfil = explode(',', $perfil['Criterio']); // Usar la clave correcta para criterio

            foreach ($criterioDividido as $indiceCriterioDeseado => $criterioDeseado) {
                foreach ($criteriosPerfil as $criterioPerfil) {
                    // Comparar después de limpiar los valores
                    if (strtolower(trim($criterioDeseado)) === strtolower(trim($criterioPerfil))) {
                        // Asignar el ponderado
                        $allProfiles[$indicePerfil]['ponderado'] += (float)$porcentajeDividido[$indiceCriterioDeseado];
                        $allProfiles[$indicePerfil]['coincidencias'] .= $criterioDeseado . ' [' . $porcentajeDividido[$indiceCriterioDeseado] . '%], ';
                    }
                }
            }
        }
    }

    // Filtrar los perfiles con un ponderado mayor al 30%
    $perfilesFiltrados = array_filter($allProfiles, function ($perfil) {
        return isset($perfil['ponderado']) && $perfil['ponderado'] > 30; // Asegurarse de que 'ponderado' existe
    });

    // Ordenar los perfiles filtrados de mayor a menor ponderado
    usort($perfilesFiltrados, function ($a, $b) {
        return $b['ponderado'] <=> $a['ponderado'];
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

function obtenerArchivosDesdeRuta($ruta)
{
    if (!is_dir($ruta)) {
        return ['success' => false, 'message' => "La ruta $ruta no es válida."];
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
