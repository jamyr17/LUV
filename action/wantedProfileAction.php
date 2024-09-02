<?php

include_once "../business/wantedProfileBusiness.php";
$wantedProfileBusiness = new WantedProfileBusiness();
include_once "../business/usuarioBusiness.php";
$usuarioBusiness = new UsuarioBusiness();

$dataGlobal = "";

//Nuevo registro de perfil deseado
if (isset($_POST["search"])) {
    if(isset($dataGlobal['updateOrder'])) {
        $usuarioId = $usuarioBusiness->getIdByName($_SESSION['nombreUsuario']);

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
            session_start();
            $_SESSION['perfilesMatcheados'] = $perfilesFiltrados;
            header("location: ../view/userProfileRecommendationsView.php");
        }
    } else {
        header("location: ../view/userWantedProfileView.php?error=formIncomplete");
    }
} else if (isset($_POST['updateOrder'])) {

    // se supone que debe entrar aquí cada vez que se actualice, sin embargo no lo está haciendo...

    // Obtener y registrar el contenido crudo
    $rawInput = file_get_contents('php://input');

    // Decodificar el JSON
    $data = json_decode($rawInput, true);
    $dataGlobal = $data; // estoy intentando guardar esos datos acá, pero no en BD, para luego guardarlos o no cuando se haga search.

    if (isset($data['updateOrder'])) {
        $updateOrder = $data['updateOrder'];

        // Inicializar vectores
        $criteria = [];
        $values = [];

        foreach ($updateOrder as $item) {
            $criteria[] = $item['criterion'];
            $values[] = $item['value'];
        }


        // Aquí puedes realizar la lógica para actualizar el orden en la base de datos
        // Además se tendrá que realizar la lógica para la distribución de porcentajes
        // Ejemplo: $resultado = $wantedProfileBusiness->updateOrder($criteria, $values);
        
        // Responder con éxito o error
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Parámetro updateOrder no recibido']);
    }
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
