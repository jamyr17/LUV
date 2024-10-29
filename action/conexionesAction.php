<?php
include_once "../business/conexionesBusiness.php";
$conexionesBusiness = new ConexionesBusiness();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json'); 

$data = json_decode(file_get_contents('php://input'), true); 

if (isset($data['cargarPerfiles'])) {
    // Llamar a userAffinityAction.php para obtener los datos
    $response = file_get_contents('../action/userAffinityAction.php');
    
    // Verificar si la respuesta fue exitosa
    if ($response === false) {
        echo json_encode(['success' => false, 'message' => 'Error al llamar a userAffinityAction.php']);
        exit();
    }

    // Convertir la respuesta JSON a un array asociativo
    $archivos = json_decode($response, true);
    $archivoPersonal = $archivos['archivoPersonal'];
    if ($archivoPersonal == null) {
        echo json_encode(['error' => 'datosNulos', 'message' => 'El usuario no tiene información necesaria para la consulta']);
        exit();
    }
    // Comprobar si la llamada fue exitosa
    if (isset($archivos['status']) && $archivos['status'] === 'success') {
        // Filtrar los perfiles de acuerdo a la lógica definida en filterAffinityProfiles
        $_SESSION['perfiles'] = filterAffinityProfiles($archivos['archivos'], $archivoPersonal['Criterio'], $porcentajeParam, $usuarioId);

        if (empty($_SESSION['perfiles'])) {
            echo json_encode(['success' => false, 'message' => 'No hay perfiles que coincidan con los criterios']);
            exit();
        }

        // Combinar los datos de perfiles
        echo json_encode([
            'success' => true
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al obtener afinidades']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Parámetro no válido']);
}


function filterAffinityProfiles($allProfiles, $criterioParam, $porcentajeParam, $usuarioId) {
    $criterioDividido = explode(',', $criterioParam);
    $porcentajeDividido = explode(',', $porcentajeParam);

    foreach ($allProfiles as $indicePerfil => $perfil) {
        // Solo procesar perfiles que no sean del mismo usuario que el perfil deseado
        if ($perfil['usuarioID'] !== $usuarioId) {
            if (!isset($allProfiles[$indicePerfil]['ponderado']) && !isset($allProfiles[$indicePerfil]['coincidencias'])) {
                $allProfiles[$indicePerfil]['ponderado'] = 0;
                $allProfiles[$indicePerfil]['coincidencias'] = '';
            }

            $criteriosPerfil = explode(',', $perfil['Criterio']); // Usar la clave correcta para criterio

            foreach ($criterioDividido as $indiceCriterioDeseado => $criterioDeseado) {
                foreach ($criteriosPerfil as $indiceCriterioPerfil => $criterioPerfil) {
                    if (strtolower($criterioDeseado) === strtolower($criterioPerfil)) {
                        // Asignar el ponderado sin comparación de valor
                        $allProfiles[$indicePerfil]['ponderado'] += (float)$porcentajeDividido[$indiceCriterioDeseado];
                        $allProfiles[$indicePerfil]['coincidencias'] .= $criterioDeseado . ' [' . $porcentajeDividido[$indiceCriterioDeseado] . '%], ';
                    }
                }
            }
        }
    }

    // Filtrar los perfiles con un ponderado mayor al 30%
    $perfilesFiltrados = array_filter($allProfiles, function ($perfil) {
        return $perfil['ponderado'] > 30;
    });

    // Ordenar los perfiles filtrados de mayor a menor ponderado
    usort($perfilesFiltrados, function ($a, $b) {
        return $b['ponderado'] <=> $a['ponderado'];
    });

    // Limitar a los 20 perfiles con mayor ponderado si hay más de 20
    if (count($perfilesFiltrados) > 20) {
        $perfilesFiltrados = array_slice($perfilesFiltrados, 0, 20);
    }

    return $perfilesFiltrados;
}

?>
