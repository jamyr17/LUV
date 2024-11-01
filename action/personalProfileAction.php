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
    // Verificar que las variables de sesión existen y no están vacías
    if (
        isset($_SESSION['criteriaString']) && !empty($_SESSION['criteriaString'])
        && isset($_SESSION['valueString']) && !empty($_SESSION['valueString'])
    ) {

        $usuarioId = $usuarioBusiness->getIdByName($_SESSION['nombreUsuario']);
        $criterioParam = $_SESSION['criteriaString'];
        $valorParam = $_SESSION['valueString'];

        $genero = isset($_POST['genero']) ? $_POST['genero'] : null;
        $orientacionSexual = isset($_POST['orientacionSexual']) ? $_POST['orientacionSexual'] : null;
        $areaConocimiento = isset($_POST['areaConocimiento']) ? $_POST['areaConocimiento'] : null;
        $universidad = isset($_POST['universidad']) ? $_POST['universidad'] : null;
        $campus = isset($_POST['campus']) ? $_POST['campus'] : null;
        $colectivos = isset($_POST['colectivos']) ? json_decode($_POST['colectivos'], true) : [];
        $criterioParam = $_SESSION['criteriaString'];
        $valorParam = $_SESSION['valueString'];
        
        $colectivosString = is_array($colectivos) ? implode(',', $colectivos) : '';

        // Dividir criterios y valores en arrays
        $criteriosArray = explode(',', $_SESSION['criteriaString']);
        $valoresArray = explode(',', $_SESSION['valueString']);

        // Hacer solicitud al algoritmo de perfilación según género y orientación sexual del usuario:
        $datos = [
            'genero' => $genero,
            'orientacion' => $orientacionSexual
        ];
        $respuestaAfinidad = postRequest('http://localhost/LUV/algorithm/profilingAlgorithm.php', $datos);
        $data = json_decode($respuestaAfinidad, true);

        $generos = [];
        $orientaciones = [];

        foreach ($data as $afinidad) {
            $generos[] = $afinidad['Genero'];
            $orientaciones[] = $afinidad['Orientacion'];
        }

        // Se unan los géneros y orientaciones en un string separado por comas
        $generosStr = implode(',', array_unique($generos)); 
        $orientacionesStr = implode(',', array_unique($orientaciones));

        $usuarioId = $usuarioBusiness->getIdByName($_SESSION['nombreUsuario']);

        $usuarioId = $usuarioBusiness->getIdByName($_SESSION['nombreUsuario']);
        if ($userAffinityData->insertAfinidadGeneroOrientacion($generosStr, $orientacionesStr, $usuarioId)) {
            $jsonResponse['status'] = 'success';
            $jsonResponse['message'] = 'Afinidad de género y orientación sexual registrada correctamente.';
        } else {
            $jsonResponse['status'] = 'error';
            $jsonResponse['message'] = 'Error al registrar afinidad de género y orientación sexual.';
        }

        
            // Asegurarse de que los criterios y valores existan
            foreach ($criteriosArray as $index => $criterioNombre) {
                $valor = trim($valoresArray[$index]);

                if($criterioBusiness->existeCriterio($criterioNombre)){

                    if(!$valorBusiness->existeValorEnCriterio($criterioNombre, $valor)){
                        agregarValorSiNoExiste($criterioNombre, $valor);
                    }

                }else{

                    $data = obtenerDatosIA($criterioNombre);
 
                    if ($data) {
                        createDataFile($criterioNombre, $data);  // Guardar los datos en un archivo .dat.
                    }

                    if(!$valorBusiness->existeValorEnCriterio($criterioNombre, $valor)){
                        agregarValorSiNoExiste($criterioNombre, $valor);
                    }

                }

            }
        
        // Actualizar o insertar el perfil personal
        if ($personalProfileBusiness->profileExists($usuarioId)) {
            $personalProfileBusiness->updateTbPerfilPersonal($criterioParam, $valorParam, $areaConocimiento, $genero, $orientacionSexual, $universidad, $campus, $colectivosString, $usuarioId);
            echo json_encode(['success' => 'updated']);
        } else {
            $personalProfileBusiness->insertTbPerfilPersonal($criterioParam, $valorParam,  $areaConocimiento, $genero, $orientacionSexual, $universidad, $campus, $colectivosString, $usuarioId);
            echo json_encode(['success' => 'inserted']);
        }
    } else {
        // Redirigir si el formulario está incompleto
        echo json_encode(['success' => false, 'error' => 'formIncomplete']);
    }
} else if (isset($_POST["tieneInfoCompleta"])) { // tiene registros de modelado personal, deseado y afinidades.
    
    $usuarioId = $usuarioBusiness->getIdByName($_SESSION['nombreUsuario']);

    if ($personalProfileBusiness->puedeBuscarConexiones($usuarioId)) { // validamos con un método del back si posee los tres requisitos
        echo json_encode(['success' => true]);
        exit();
    }
    echo json_encode(['success' => false]);
    exit();
} else {
    $usuarioId = $usuarioBusiness->getIdByName($_SESSION['nombreUsuario']);

    if (!$usuarioId) {
        // Enviar error si no se encuentra el ID de usuario
        header('Content-Type: application/json');
        echo json_encode(["error" => "ID de usuario no encontrado"]);
        exit();
    }

    $perfilPersonal = $personalProfileBusiness->perfilPersonalByIdUsuario($usuarioId);

    if ($perfilPersonal) {
        // Enviar el perfil personal si se encuentra
        header('Content-Type: application/json');
        echo json_encode(["perfil" => $perfilPersonal]);
    } else {
        // Enviar error si no se encuentra el perfil
        header('Content-Type: application/json');
        echo json_encode(["error" => "Perfil no encontrado"]);
    }
    exit();  // Asegura que no se ejecute más código después de enviar la respuesta
}