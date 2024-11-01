<?php

include_once "../business/personalProfileBusiness.php";
include_once "../business/usuarioBusiness.php";
include_once '../business/criterioBusiness.php';
include_once '../business/valorBusiness.php';
include_once '../util/postRequest.php';

require_once '../data/userAffinityData.php';
$userAffinityData = new UserAffinityData();


$criterioBusiness = new CriterioBusiness();
$valorBusiness = new ValorBusiness();
$personalProfileBusiness = new PersonalProfileBusiness();
$usuarioBusiness = new UsuarioBusiness();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST["registrar"])) {
    // Inicia el buffer de salida para capturar cualquier salida extra
    ob_start();

    // Verificar que las variables de sesión existen y no están vacías
    if (
        isset($_SESSION['criteriaString']) && !empty($_SESSION['criteriaString']) &&
        isset($_SESSION['valueString']) && !empty($_SESSION['valueString'])
    ) {

        $usuarioId = $usuarioBusiness->getIdByName($_SESSION['nombreUsuario']);
        $criterioParam = $_SESSION['criteriaString'];
        $valorParam = $_SESSION['valueString'];

        $genero = $_POST['genero'] ?? null;
        $orientacionSexual = $_POST['orientacionSexual'] ?? null;
        $areaConocimiento = $_POST['areaConocimiento'] ?? null;
        $universidad = $_POST['universidad'] ?? null;
        $campus = $_POST['campus'] ?? null;
        $colectivos = isset($_POST['colectivos']) ? json_decode($_POST['colectivos'], true) : [];
        
        $colectivosString = is_array($colectivos) ? implode(',', $colectivos) : '';

        $criteriosArray = explode(',', $_SESSION['criteriaString']);
        $valoresArray = explode(',', $_SESSION['valueString']);

        // Procesamiento de criterios y valores
        foreach ($criteriosArray as $index => $criterioNombre) {
            $valor = trim($valoresArray[$index]);

                if($criterioBusiness->existeCriterio($criterioNombre)){

                    if(!$valorBusiness->existeValorEnCriterio($criterioNombre, $valor)){
                        agregarValorSiNoExiste($criterioNombre, $valor);
                    }

                }else{

                    $data = obtenerDatosIA($nombre);
 
                    if ($data) {
                        createDataFile($nombre, $data);  // Guardar los datos en un archivo .dat.
                    }

                    if(!$valorBusiness->existeValorEnCriterio($criterioNombre, $valor)){
                        agregarValorSiNoExiste($criterioNombre, $valor);
                    }

                }

            }

        // Actualizar o insertar el perfil personal
        $response = [];
        if ($personalProfileBusiness->profileExists($usuarioId)) {
            $personalProfileBusiness->updateTbPerfilPersonal($criterioParam, $valorParam, $areaConocimiento, $genero, $orientacionSexual, $universidad, $campus, $colectivosString, $usuarioId);
            $response = ['success' => 'updated'];
        } else {
            $personalProfileBusiness->insertTbPerfilPersonal($criterioParam, $valorParam, $areaConocimiento, $genero, $orientacionSexual, $universidad, $campus, $colectivosString, $usuarioId);
            $response = ['success' => 'inserted'];
        }

        // Limpia cualquier salida y devuelve solo el JSON
        ob_end_clean();
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    } else {
        ob_end_clean();
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'formIncomplete']);
        exit;
    }
} else if (isset($_POST["tieneInfoCompleta"])) { // tiene registros de modelado personal, deseado y afinidades.
    
    $usuarioId = $usuarioBusiness->getIdByName($_SESSION['nombreUsuario']);

    if ($personalProfileBusiness->puedeBuscarConexiones($usuarioId)) { // validamos con un método del back si posee los tres requisitos
        echo json_encode(['success' => true]);
        exit();
    }
    echo json_encode(['success' => false]);
    exit();
}else {
    // Manejo de solicitud GET para perfil deseado
    $usuarioId = $usuarioBusiness->getIdByName($_SESSION['nombreUsuario']);

    if (!$usuarioId) {
        header('Content-Type: application/json');
        echo json_encode(["error" => "ID de usuario no encontrado"]);
        exit();
    }

    $perfilPersonal = $personalProfileBusiness->perfilPersonalByIdUsuario($usuarioId);

    if ($perfilPersonal) {
        header('Content-Type: application/json');
        echo json_encode(["perfil" => $perfilPersonal]);
    } else {
        header('Content-Type: application/json');
        echo json_encode([
            "error" => "Perfil no encontrado para el ID de usuario",
            "usuarioId" => $usuarioId,
            "perfilPersonalResponse" => $perfilPersonal
        ]);
    }
    exit();
}