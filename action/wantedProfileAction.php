<?php

include_once "../business/wantedProfileBusiness.php";
include_once "../business/usuarioBusiness.php";
include_once "../action/gestionArchivosIAAction.php";
include_once "../business/valorBusiness.php";
include_once "../business/criterioBusiness.php";

$wantedProfileBusiness = new WantedProfileBusiness();
$usuarioBusiness = new UsuarioBusiness();
$valorBusiness = new ValorBusiness();
$criterioBusiness = new CriterioBusiness();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["search"])) {

    if (empty($_POST['criteriaString']) || empty($_POST['valuesString'])) {
        header("Location: ../view/userWantedProfileView.php?error=emptyFields");
        exit();
    }

    if (isset($_POST['criteriaString']) && !empty($_POST['criteriaString']) &&
        isset($_POST['valuesString']) && !empty($_POST['valuesString'])) {

        // Depuración de los parámetros recibidos
        error_log("Criterios recibidos: " . $_POST['criteriaString']);
        error_log("Valores recibidos: " . $_POST['valuesString']);

        $criterioParam = $_POST['criteriaString'];
        $valorParam = $_POST['valuesString'];
        $usuarioId = $usuarioBusiness->getIdByName($_SESSION['nombreUsuario']);

        if (!$usuarioId) {
            error_log("Usuario no encontrado: " . $_SESSION['nombreUsuario']);
            header("location: ../view/userWantedProfileView.php?error=userNotFound");
            exit();
        }

        $porcentajeParam = calculatePercentage();
        if (!isset($porcentajeParam)) {
            header("location: ../view/userWantedProfileView.php?error=percentageCalc");
            exit();
        }

        $criteriosArray = explode(',', $criterioParam);
        $valoresArray = explode(',', $valorParam);

        foreach ($criteriosArray as $index => $criterioNombre) {
            $valor = trim($valoresArray[$index] ?? '');


            if ($criterioBusiness->existeCriterio($criterioNombre)) {
                if (!$valorBusiness->existeValorEnCriterio($criterioNombre, $valor)) {
                    agregarValorSiNoExiste($criterioNombre, $valor);
                }
            } else {
                $data = obtenerDatosIA($criterioNombre);
                if ($data) {
                    createDataFile($criterioNombre, $data);
                }
                agregarValorSiNoExiste($criterioNombre, $valor);
            }
        }
        if ($wantedProfileBusiness->profileExists($usuarioId)) {
            $result = $wantedProfileBusiness->updateTbPerfilDeseado($criterioParam, $valorParam, $porcentajeParam, $usuarioId);
            if ($result) {
                echo "Perfil actualizado correctamente para el usuario $usuarioId.";
            } else {
                echo "Error al actualizar el perfil para el usuario $usuarioId.";
            }
        } else {
            error_log("El perfil del usuario $usuarioId no existe, se creará.");
            $result = $wantedProfileBusiness->insertTbPerfilDeseado($criterioParam, $valorParam, $porcentajeParam, $usuarioId);
            if ($result) {
                echo "Perfil creado correctamente para el usuario $usuarioId.";
            } else {
                echo "Error al crear el perfil para el usuario $usuarioId.";
            }
        }
        

        $allPerfiles = $wantedProfileBusiness->getAllTbPerfiles();

        if (empty($allPerfiles)) {
            header("location: ../view/userWantedProfileView.php?error=noProfiles");
        } else {
            $perfilesFiltrados = filterProfiles($allPerfiles, $criterioParam, $valorParam, $porcentajeParam, $usuarioId);

            if (!empty($perfilesFiltrados)) {
                $_SESSION['perfilesMatcheados'] = $perfilesFiltrados;
                header("location: ../view/userProfileRecommendationsView.php");
            } else {
                header("location: ../view/userWantedProfileView.php?error=noMatches");
            }
        }
    } else {
        header("Location: ../view/userWantedProfileView.php?error=emptyFields");
        exit();
    }

}
 elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Manejo de solicitudes JSON en POST
    header('Content-Type: application/json');
    $rawInput = file_get_contents('php://input');

    if (!$rawInput) {
        echo json_encode(['status' => 'error', 'message' => 'No se recibieron datos']);
        exit();
    }

    $data = json_decode($rawInput, true);

    // Validar y procesar datos JSON
    if (json_last_error() === JSON_ERROR_NONE && isset($data['updateOrder'])) {
        $updateOrder = $data['updateOrder'];
        $criteria = implode(',', array_column($updateOrder, 'criterion'));
        $values = implode(',', array_column($updateOrder, 'value'));

        // Guardar en sesión
        $_SESSION['criteriaString'] = $criteria;
        $_SESSION['valueString'] = $values;
        $_SESSION['cantCriteria'] = count($updateOrder);

        echo json_encode(['status' => 'success', 'guardado' => [
            'criteriaString' => $_SESSION['criteriaString'],
            'valueString' => $_SESSION['valueString']
        ]]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Datos no válidos o parámetro updateOrder no recibido']);
    }

} else {

    // Manejo de solicitud GET para perfil deseado
    $usuarioId = $usuarioBusiness->getIdByName($_SESSION['nombreUsuario']);

    if (!$usuarioId) {
        header('Content-Type: application/json');
        echo json_encode(["error" => "ID de usuario no encontrado"]);
        exit();
    }

    $perfilDeseado = $wantedProfileBusiness->perfilDeseadoByIdUsuario($usuarioId);

    if ($perfilDeseado) {
        header('Content-Type: application/json');
        echo json_encode(["perfil" => $perfilDeseado]);
    } else {
        header('Content-Type: application/json');
        echo json_encode(["error" => "Perfil no encontrado para el ID de usuario: $usuarioId"]);
    }
    exit();
}

// Función para filtrar y ordenar perfiles
function filterProfiles($allPerfiles, $criterioParam, $valorParam, $porcentajeParam, $usuarioId) {
    $criterioDividido = explode(',', $criterioParam);
    $valorDividido = explode(',', $valorParam);
    $porcentajeDividido = explode(',', $porcentajeParam);

    foreach ($allPerfiles as $indicePerfil => $perfil) {
        if ($perfil['usuarioId'] !== $usuarioId) {
            $allPerfiles[$indicePerfil]['ponderado'] = $allPerfiles[$indicePerfil]['ponderado'] ?? 0;
            $allPerfiles[$indicePerfil]['coincidencias'] = $allPerfiles[$indicePerfil]['coincidencias'] ?? '';

            $criteriosPerfil = explode(',', $perfil['criterio']);
            $valoresPerfil = explode(',', $perfil['valor']);

            foreach ($criterioDividido as $indiceCriterioDeseado => $criterioDeseado) {
                foreach ($criteriosPerfil as $indiceCriterioPerfil => $criterioPerfil) {
                    if (strtolower($criterioDeseado) === strtolower($criterioPerfil)) {
                        $valorCriterio = $valoresPerfil[$indiceCriterioPerfil] ?? '';
                        if (strtolower($valorCriterio) == strtolower($valorDividido[$indiceCriterioDeseado])) {
                            $allPerfiles[$indicePerfil]['ponderado'] += $porcentajeDividido[$indiceCriterioDeseado];
                            $allPerfiles[$indicePerfil]['coincidencias'] .= "$criterioDeseado ($valorCriterio) [{$porcentajeDividido[$indiceCriterioDeseado]}%], ";
                        }
                    }
                }
            }
        }
    }

    $perfilesFiltrados = array_filter($allPerfiles, fn($perfil) => $perfil['ponderado'] > 30);
    usort($perfilesFiltrados, fn($a, $b) => $b['ponderado'] <=> $a['ponderado']);

    return count($perfilesFiltrados) > 20 ? array_slice($perfilesFiltrados, 0, 20) : $perfilesFiltrados;
}

// Función para calcular porcentajes basados en el orden de criterios
function calculatePercentage() {
    if (isset($_SESSION['cantCriteria'])) {
        $n = $_SESSION['cantCriteria'];
        $total = $n * ($n + 1) / 2;
        $percentages = array_map(fn($i) => round(($i / $total) * 100, 2), range($n, 1));
        return implode(',', $percentages);
    }
    return null;
}
?>
