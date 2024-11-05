<?php
include_once "../business/conexionesBusiness.php";
$conexionesBusiness = new ConexionesBusiness();
require_once '../business/usuarioBusiness.php';
$usuarioBusiness = new UsuarioBusiness();
require_once '../business/wantedProfileBusiness.php';
$wantedProfileBusiness = new WantedProfileBusiness();
require_once '../business/personalProfileBusiness.php';
$personalProfileBusiness = new PersonalProfileBusiness();


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

// METODO NUEVO!!!!!!
if (isset($data['cargarPerfiles'])) {
    //1) Filtrar:
    //- Leer la afinidad por genero y orientación que tiene el usuario que está en sesión desde la tbafinidad.
    $nombreUsuario = $_SESSION['nombreUsuario'];
    $usuarioId = $usuarioBusiness->getIdByName($nombreUsuario);

    $generos = $usuarioBusiness->getTbAfinidadUsuarioGenero($usuarioId);
    $orientacionesSexuales = $usuarioBusiness->getTbAfinidadUsuarioOrientacionSexual($usuarioId);

    // Seleccionar todos los nombres de usuario con las afinidades de género y orientación
    $nombresUsuarioFiltrados = $usuarioBusiness->getUsernamesByGenderAndOrientation($generos, $orientacionesSexuales);

    // Obtener los perfiles personales de los usuarios por nombre
    $perfilesDeseadosPorNombreUsuario = $personalProfileBusiness->getPerfilesPersonalesPorNombres($nombresUsuarioFiltrados);

    // Obtener los datos del perfil deseado del usuario
    $perfilPersonal = $wantedProfileBusiness->perfilDeseadoByIdUsuario($usuarioId);

    // Proceder a filtrar de las tablas personal y deseado para obtener únicamente los que cumplen con almenos un 30%
    $criterios = $perfilPersonal[0]['criterio'];
    $porcentajes = $perfilPersonal[0]['porcentaje'];

    $perfilesFiltradosBD = filterAffinityProfilesFromDB($perfilesDeseadosPorNombreUsuario, $criterios, $porcentajes, $usuarioId);



    //2) Leer .dat:
    $ruta = '../resources/afinidadesUsuarios';
    $nombreArchivo = 'dataAfinidad.dat';
    $carpetasTotal = obtenerNombresDeCarpetasDesdeRuta($ruta);
    $carpetas = [];

    $setCarpetas = array_flip($carpetasTotal); // Esto convierte $carpetasTotal en un array asociativo donde las claves son los nombres de las carpetas

    foreach ($perfilesFiltradosBD as $perfil) {
        if (isset($setCarpetas[$perfil['tbusuarionombre']])) {
            $carpetas[] = $perfil['tbusuarionombre'];
        }
    }

    $archivoPersonal = [];
    $datosTotales = [];

    //- Leer cada .dat de los demás usuarios filtrados, hacer los mismo de recuperar todos los criterios y sus tiempos de duración.
    // Iterar a través de las carpetas y procesar los archivos
    foreach ($carpetas as $carpeta) {
        $resultadoArchivo = leerDatosDesdeArchivo($ruta . '/' . $carpeta . '/' . $nombreArchivo);
        if (is_array($resultadoArchivo) && !isset($resultadoArchivo['success'])) {
            // Procesar cada registro para calcular los porcentajes de duración
            foreach ($resultadoArchivo as $indice => $registro) {
                if (isset($registro['Duracion'])) {
                    // Obtener el campo de Duracion (cadena separada por comas)
                    $duraciones = $registro['Duracion'];
                    //- Darle un porcentaje de afinidad a cada criterio de los demás usuarios.
                    // Calcular los porcentajes de duración
                    $porcentajesDuracion = calcularPorcentajesDeDuracion($duraciones);

                    // Almacenar los porcentajes calculados en el registro
                    $resultadoArchivo[$indice]['PorcentajesDuracion'] = $porcentajesDuracion;
                }
            }

            // Agregar el resultado procesado a los datos totales
            $datosTotales[] = $resultadoArchivo;  // Se utiliza [] en lugar de count() para agregar
        }
    }
    //- Leer el .dat del usuario en sesión, recuperar todos los criterios y sus tiempos de duración.
    // Verificar si el archivo personal existe
    $archivoPersonalPath = $ruta . '/' . $nombreUsuario . '/' . $nombreArchivo;
    if (file_exists($archivoPersonalPath)) {
        // Leer los datos desde el archivo
        $arregloArchivoPersonal = leerDatosDesdeArchivo($archivoPersonalPath);

        // Verificar si los datos se han leído correctamente
        if (is_array($arregloArchivoPersonal) && !empty($arregloArchivoPersonal)) {

            // Iterar a través de todas las líneas del archivo personal
            foreach ($arregloArchivoPersonal as $index => $archivoPersonal) {
                // Verificar si el campo 'Duracion' existe en cada registro
                if (isset($archivoPersonal['Duracion'])) {
                    // Calcular los porcentajes de duración para cada registro
                    //- Darle un porcentaje de afinidad a cada criterio usando el arreglo de duraciones y arreglo de criterios.
                    $porcentajesDuracion = calcularPorcentajesDeDuracion($archivoPersonal['Duracion']);
                    // Agregar los porcentajes calculados al registro
                    $arregloArchivoPersonal[$index]['PorcentajesDuracion'] = $porcentajesDuracion;
                } else {
                    echo "No se encontró el campo 'Duracion' en el registro $index.";
                    exit();
                }
            }
        } else {
            echo "No se encontraron registros o hubo un error al leer el archivo.";
            exit();
        }
    } else {
        // Si el archivo no existe, mostrar un mensaje de error
        echo "Archivo personal no encontrado.";
        exit();
    }

    // Ahora vamos a iterar sobre todos los registros de $arregloArchivoPersonal
    $perfilesFiltrados = [];

    // Para cada fila en $arregloArchivoPersonal, se debe realizar un filtrado
    foreach ($arregloArchivoPersonal as $archivoPersonal) {
        // Obtener el criterio y los porcentajes de duración del archivo personal
        $criterio = explode(',', $archivoPersonal['Criterio']) ?? null;
        $porcentajesDuracion = $archivoPersonal['PorcentajesDuracion'] ?? null;

        // Iterar sobre todas las filas de $datosTotales y comparar
        foreach ($datosTotales as $resultadoArchivo) {
            //- Ordenar la lista según las coincidencias de las afinidades.
            $perfilesFiltrados[] = filterAffinityProfiles($resultadoArchivo, $criterio, $porcentajesDuracion, $usuarioId);
        }
    }

    // Extraer los IDs de los perfiles filtrados
    $ids = [];
    foreach ($perfilesFiltrados as $subArray) {
        foreach ($subArray as $item) {
            if (isset($item['UsuarioID'])) {
                $ids[] = $item['UsuarioID'];
            }
        }
    }

    // Obtener los perfiles completos de la base de datos
    $perfilesUsuariosComplemento = $conexionesBusiness->getAllTbPerfilesPorID($ids);

    // Unir los perfiles complementarios con los filtrados
    $_SESSION['perfiles'] = unirElementosEnArregloUnico($perfilesUsuariosComplemento, $perfilesFiltrados);
    var_dump($_SESSION['perfiles']);
    exit;
    // Verificar si hay perfiles en la sesión
    if (empty($_SESSION['perfiles'])) {
        echo json_encode(['success' => false, 'message' => 'No hay perfiles que coincidan con los criterios']);
        exit();
    }

    // Respuesta final
    echo json_encode([
        'success' => true
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Parámetro no válido']);
}

function calcularPorcentajesDeDuracion($duraciones)
{
    // Convertir las duraciones en un array de números
    $duracionesArray = explode(',', $duraciones);
    $duracionesArray = array_map('floatval', $duracionesArray); // Asegurarse de que todos los valores sean números

    // Calcular el total de las duraciones
    $totalDuracion = array_sum($duracionesArray);

    // Si el total es 0, no se puede dividir, retornar un array vacío
    if ($totalDuracion == 0) {
        return [];
    }

    // Calcular el porcentaje de cada duración
    $porcentajes = [];
    foreach ($duracionesArray as $duracion) {
        $porcentaje = ($duracion / $totalDuracion) * 100;
        $porcentajes[] = round($porcentaje, 2); // Redondear a dos decimales
    }

    return $porcentajes;
}


function filterAffinityProfiles($allProfiles, $criterioParam, $porcentajeDuracionParam, $usuarioId)
{
    // Validar que los parámetros no estén vacíos
    if (empty($criterioParam) || empty($porcentajeDuracionParam)) {
        return []; // Retorna un array vacío si no hay criterios o afinidades
    }

    // Separar los criterios y porcentajes
    $criterioDividido = $criterioParam;  // Separar los criterios
    $porcentajeDividido = $porcentajeDuracionParam;  // Los porcentajes ya vienen como un array de floats

    // Array para los perfiles filtrados
    $perfilesFiltrados = [];

    // Recorrer todos los perfiles
    foreach ($allProfiles as $indicePerfil => $perfil) {
        // Solo procesar perfiles que no sean del mismo usuario
        if ($perfil['UsuarioID'] !== $usuarioId) {
            // Inicializar 'ponderado' y 'coincidencias' si no existen
            if (!isset($perfil['ponderado'])) {
                $perfil['ponderado'] = 0;
            }
            if (!isset($perfil['coincidencias'])) {
                $perfil['coincidencias'] = '';
            }

            // Obtener los criterios del perfil y dividirlos
            $criteriosPerfil = explode(',', $perfil['Criterio']);  // 'Criterio' contiene los criterios del perfil

            // Recorrer los criterios deseados
            foreach ($criterioDividido as $indiceCriterioDeseado => $criterioDeseado) {
                foreach ($criteriosPerfil as $criterioPerfil) {
                    // Comparar los criterios
                    if (strtolower(trim($criterioDeseado)) === strtolower(trim($criterioPerfil))) {
                        // Sumar el ponderado basado en el porcentaje
                        $perfil['ponderado'] += (float)$porcentajeDividido[$indiceCriterioDeseado];

                        // Registrar la coincidencia
                        $perfil['coincidencias'] .= $criterioDeseado . ' [' . $porcentajeDividido[$indiceCriterioDeseado] . '%], ';
                    }
                }
            }

            // Almacenar el perfil procesado en el array de perfiles filtrados
            $perfilesFiltrados[] = $perfil;
        }
    }

    // Filtrar perfiles con un ponderado mayor al 30%
    $perfilesFiltrados = array_filter($perfilesFiltrados, function ($perfil) {
        return isset($perfil['ponderado']) && $perfil['ponderado'] > 30;
    });

    // Ordenar los perfiles por ponderado de mayor a menor
    usort($perfilesFiltrados, function ($a, $b) {
        return $b['ponderado'] <=> $a['ponderado'];
    });

    // Limitar los resultados a los primeros 20 perfiles con mayor ponderado
    return array_slice($perfilesFiltrados, 0, 20);
}


function filterAffinityProfilesFromDB($allProfiles, $criterioParam, $porcentajeParam, $usuarioId)
{
    // Validar que los parámetros no estén vacíos
    if (empty($criterioParam) || empty($porcentajeParam)) {
        return []; // Retorna un array vacío si no hay criterios
    }

    $criterioDividido = $criterioParam;
    $porcentajeDividido = $porcentajeParam;

    // Encontramos el tamaño mínimo entre ambos arrays para evitar desbordamientos
    $longitudMinima = min(count($criterioDividido), count($porcentajeDividido));

    foreach ($allProfiles as $indicePerfil => $perfil) {
        // Solo procesar perfiles que no sean del mismo usuario
        if ($perfil['tbusuarioid'] !== $usuarioId) {
            // Inicializar 'ponderado' y 'coincidencias' si no existen
            if (!isset($allProfiles[$indicePerfil]['ponderado'])) {
                $allProfiles[$indicePerfil]['ponderado'] = 0;
            }
            if (!isset($allProfiles[$indicePerfil]['coincidencias'])) {
                $allProfiles[$indicePerfil]['coincidencias'] = '';
            }

            // Accedemos al array 'criterio' dentro de la variable perfil y también al array 'valor'
            $criteriosPerfil = $perfil['criterio'];  // 'criterio' es un array


            for ($indiceCriterioDeseado = 0; $indiceCriterioDeseado < $longitudMinima; $indiceCriterioDeseado++) {
                $criterioDeseado = $criterioDividido[$indiceCriterioDeseado];
                $porcentaje = $porcentajeDividido[$indiceCriterioDeseado];

                // Recorrer los criterios del perfil
                foreach ($criteriosPerfil as $indiceCriterioPerfil => $criterioPerfil) {
                    // Comparar después de limpiar los valores
                    if (strtolower(trim($criterioDeseado)) === strtolower(trim($criterioPerfil))) {
                        // Asignar el ponderado
                        $allProfiles[$indicePerfil]['ponderado'] += (float)$porcentaje;

                        // Agregar la coincidencia al campo 'coincidencias'
                        $allProfiles[$indicePerfil]['coincidencias'] .= $criterioDeseado . ' [' . $porcentaje . '%], ';
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
        $linea = trim($linea); // Eliminar posibles saltos de línea y espacios

        // Dividir la línea por el delimitador '|'
        $pares = explode('|', $linea);
        $datosDeArchivo = []; // Inicializar array para cada registro

        // Procesar cada par clave: valor
        foreach ($pares as $par) {
            // Si el par contiene ': ' (indicando un campo clave:valor)
            if (strpos($par, ': ') !== false) {
                list($clave, $valor) = explode(': ', $par, 2); // Dividir en clave y valor
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
