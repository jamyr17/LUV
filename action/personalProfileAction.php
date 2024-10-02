<?php

include_once "../business/personalProfileBusiness.php";
include_once "../business/usuarioBusiness.php";

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
        $colectivos = isset($_POST['colectivos']) ? json_decode($_POST['colectivos'], true) : [];
        $criterioParam = $_SESSION['criteriaString'];
        $valorParam = $_SESSION['valueString'];

        $genero = isset($_POST['genero']) ? $_POST['genero'] : null;
        $orientacionSexual = isset($_POST['orientacionSexual']) ? $_POST['orientacionSexual'] : null;
        $areaConocimiento = isset($_POST['areaConocimiento']) ? $_POST['areaConocimiento'] : null;
        $universidad = isset($_POST['universidad']) ? $_POST['universidad'] : null;
        $campus = isset($_POST['campus']) ? $_POST['campus'] : null;
// ----- Tomar los valores de los campos "otro / otra" --------------------------------------------------
        $genero = isset($_POST['request-generoNombre']) ? $_POST['request-generoNombre'] : $genero;
        $orientacionSexual = isset($_POST['request-orientacionSexualNombre']) ? $_POST['request-orientacionSexualNombre'] : $orientacionSexual;
        $areaConocimiento = isset($_POST['request-areaConocimientoNombre']) ? $_POST['request-areaConocimientoNombre'] : $areaConocimiento;
        $universidad = isset($_POST['request-universidadNombre']) ? $_POST['request-universidadNombre'] : $universidad;
        $campus = isset($_POST['request-campusNombre']) ? $_POST['request-campusNombre'] : $campus;

        $colectivosString = "";

        if (is_array($colectivos)) {
            $colectivosString = implode(',', $colectivos); // Salida: Volleyball,Basketball,etc...
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
