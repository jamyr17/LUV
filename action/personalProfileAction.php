<?php

include_once "../business/personalProfileBusiness.php";
include_once "../business/usuarioBusiness.php";

$personalProfileBusiness = new PersonalProfileBusiness();
$usuarioBusiness = new UsuarioBusiness();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$response = ['success' => true];


if (isset($_POST["registrar"])) {
    // Verificar que las variables de sesión existen y no están vacías
    if (
        isset($_SESSION['criteriaString']) && !empty($_SESSION['criteriaString'])
        && isset($_SESSION['valueString']) && !empty($_SESSION['valueString'])
    ) {

        $usuarioId = $usuarioBusiness->getIdByName($_SESSION['nombreUsuario']);
        $genero = isset($_POST['genero']) ? $_POST['genero'] : null;
        $orientacionSexual = isset($_POST['orientacionSexual']) ? $_POST['orientacionSexual'] : null;
        $areaConocimiento = isset($_POST['areaConocimiento']) ? $_POST['areaConocimiento'] : null;
        $universidad = isset($_POST['universidad']) ? $_POST['universidad'] : null;
        $campus = isset($_POST['campus']) ? $_POST['campus'] : null;
        $colectivos = isset($_POST['colectivos']) ? json_decode($_POST['colectivos'], true) : [];
        $criterioParam = $_SESSION['criteriaString'];
        $valorParam = $_SESSION['valueString'];
        
        $colectivosString = implode(',', $colectivos); // Volleyball,Basketball,etc...

        // Imprimir los valores para depuración
        echo "<h2>Valores Extraídos para Depuración</h2>";
        echo "<strong>ID de Usuario:</strong> " . htmlspecialchars($usuarioId) . "<br>";
        echo "<strong>Género:</strong> " . htmlspecialchars($genero) . "<br>";
        echo "<strong>Orientación Sexual:</strong> " . htmlspecialchars($orientacionSexual) . "<br>";
        echo "<strong>Área de Conocimiento:</strong> " . htmlspecialchars($areaConocimiento) . "<br>";
        echo "<strong>Universidad:</strong> " . htmlspecialchars($universidad) . "<br>";
        echo "<strong>Campus:</strong> " . htmlspecialchars($campus) . "<br>";
        echo "<strong>Colectivos:</strong> " . htmlspecialchars($colectivosString) . "<br>";
        echo "<strong>Criterios:</strong> " . htmlspecialchars($criterioParam) . "<br>";
        echo "<strong>Valores:</strong> " . htmlspecialchars($valorParam) . "<br>";

        // Finaliza la ejecución aquí para depuración
        exit;

        // Queda pendiente el guardar los datos y la corrección de la BD

        // Actualizar o insertar el perfil personal
        if ($personalProfileBusiness->profileExists($usuarioId)) {
            $personalProfileBusiness->updateTbPerfilPersonal($criterioParam, $valorParam, $usuarioId);
            header("location: ../view/userPersonalProfileView.php?success=updated");
        } else {
            $personalProfileBusiness->insertTbPerfilPersonal($criterioParam, $valorParam, $usuarioId);
            header("location: ../view/userPersonalProfileView.php?success=inserted");
        }
    } else {
        // Redirigir si el formulario está incompleto
        header("location: ../view/userPersonalProfileView.php?error=formIncomplete");
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
