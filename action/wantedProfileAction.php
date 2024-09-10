<?php

include_once "../business/wantedProfileBusiness.php";
$wantedProfileBusiness = new WantedProfileBusiness();
include_once "../business/usuarioBusiness.php";
$usuarioBusiness = new UsuarioBusiness();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
   
    //Nuevo registro de perfil deseado
    if (isset($_POST["search"])) {
        if(isset($_SESSION['criteriaString']) && !empty([$_SESSION['criteriaString']]) && isset($_SESSION['valueString']) && !empty([$_SESSION['valueString']])){
            
            $usuarioId = $usuarioBusiness->getIdByName($_SESSION['nombreUsuario']);
            $criterioParam = $_SESSION['criteriaString'];
            $valorParam = $_SESSION['valueString'];
            $porcentajeParam = calculatePercentage(); // calcula y si hay algun error devuelve null

            if(isset($porcentajeParam)){

                if ($wantedProfileBusiness->profileExists($usuarioId)) {
                    $wantedProfileBusiness->updateTbPerfilDeseado($criterioParam, $valorParam, $porcentajeParam, $usuarioId);
                } else {
                    $wantedProfileBusiness->insertTbPerfilDeseado($criterioParam, $valorParam, $porcentajeParam, $usuarioId);
                }
    
                // filtrar perfiles segun lo que desea el usuario: 
                $allPerfiles = $wantedProfileBusiness->getAllTbPerfiles();
    
                // si no hay perfiles registrados
                if (empty($allPerfiles)) {
                    header("location: ../view/userWantedProfileView.php?error=noProfiles");
                } else {
                    $perfilesFiltrados = filterProfiles($allPerfiles, $criterioParam, $valorParam, $porcentajeParam, $usuarioId);
    
                    // guardar los perfiles filtrados en sesión
                    $_SESSION['perfilesMatcheados'] = $perfilesFiltrados;
                    header("location: ../view/userProfileRecommendationsView.php");
                }
            }else{
                header("location: ../view/userWantedProfileView.php?error=percentageCalc");
            }
            
        } else {
            header("location: ../view/userWantedProfileView.php?error=formIncomplete");
        }
    }else{
        header('Content-Type: application/json');

        // Obtener y registrar el contenido crudo
        $rawInput = file_get_contents('php://input');
        if (!$rawInput) {
            echo json_encode(['status' => 'error', 'message' => 'No se recibieron datos']);
            exit;
        }

        // Decodificar el JSON
        $data = json_decode($rawInput, true);

        // Verificar si la decodificación fue exitosa
        if (json_last_error() === JSON_ERROR_NONE && isset($data['updateOrder'])) {
            $updateOrder = $data['updateOrder'];


        // Inicializar strings
        $criteria = '';
        $values = '';

        foreach ($updateOrder as $item) {
            $criteria .= $item['criterion'] . ',';
            $values .= $item['value'] . ',';
        }

        // Guardar en la sesión
        $_SESSION['criteriaString'] = rtrim($criteria, ',');
        $_SESSION['valueString'] = rtrim($values, ',');
        $_SESSION['cantCriteria'] = count($updateOrder);

        // Responder con éxito
        echo json_encode([
        'status' => 'success',
        'guardado' => [
        'criteriaString' => $_SESSION['criteriaString'],
        'valueString' => $_SESSION['valueString']
            ]
        ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Datos no válidos o parámetro updateOrder no recibido']);
        }
    }

}else{
    $usuarioId = $usuarioBusiness->getIdByName($_SESSION['nombreUsuario']);

    if (!$usuarioId) {
        // Enviar error si no se encuentra el ID de usuario
        header('Content-Type: application/json');
        echo json_encode(["error" => "ID de usuario no encontrado"]);
        exit();
    }

    $perfilDeseado = $wantedProfileBusiness->perfilDeseadoByIdUsuario($usuarioId);

    if ($perfilDeseado) {
        // Enviar el perfil personal si se encuentra
        header('Content-Type: application/json');
        echo json_encode(["perfil" => $perfilDeseado]);
    } else {
        // Enviar error si no se encuentra el perfil
        header('Content-Type: application/json');
        echo json_encode(["error" => "Perfil no encontrado"]);
    }
    exit();  // Asegura que no se ejecute más código después de enviar la respuesta
}

// Definir función para filtrar y ordenar perfiles
function filterProfiles($allPerfiles, $criterioParam, $valorParam, $porcentajeParam, $usuarioId)
{
    $criterioDividido = explode(',', $criterioParam);
    $valorDividido = explode(',', $valorParam);
    $porcentajeDividido = explode(',', $porcentajeParam);

    foreach ($allPerfiles as $indicePerfil => $perfil) {
        // Inicializa 'ponderado' si no existe
        if ($perfil['usuarioId'] !== $usuarioId) {

            if (!isset($allPerfiles[$indicePerfil]['ponderado']) && !isset($allPerfiles[$indicePerfil]['coincidencias'])) {
                $allPerfiles[$indicePerfil]['ponderado'] = 0;
                $allPerfiles[$indicePerfil]['coincidencias'] = '';
            }

            $criteriosPerfil = explode(',', $perfil['criterio']);

            foreach ($criterioDividido as $indiceCriterioDeseado => $criterioDeseado) {
                foreach ($criteriosPerfil as $indiceCriterioPerfil => $criterioPerfil) {
                    // Comparar los criterios
                    if ($criterioDeseado === $criterioPerfil) {
                        $valorCriterio = explode(',', $perfil['valor'])[$indiceCriterioPerfil];

                        if ($valorCriterio == $valorDividido[$indiceCriterioDeseado]) {
                            $allPerfiles[$indicePerfil]['ponderado'] += $porcentajeDividido[$indiceCriterioDeseado];
                            $allPerfiles[$indicePerfil]['coincidencias'] .= $criterioDeseado . ' (' . $valorCriterio  . ')  [' . $porcentajeDividido[$indiceCriterioDeseado] . '%], ';
                        }
                    }
                }
            }
        }
    }

    // Filtrar los perfiles con un ponderado mayor al 30%
    $perfilesFiltrados = array_filter($allPerfiles, function ($perfil) {
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

// funcion para generar un calculo logico de porcentajes segun el orden escogido por el usuario
function calculatePercentage(){
    if(isset($_SESSION['cantCriteria'])){
        $n = $_SESSION['cantCriteria'];
        $total = $n * ($n + 1) / 2;
    
        $percentages = [];
        for ($i = $n; $i >= 1; $i--) {
            $percentage = ($i / $total) * 100;
            $percentages[] = round($percentage, 2); 
        }
        
        return implode(',', $percentages);
    }else{
        return null;
    }

}
