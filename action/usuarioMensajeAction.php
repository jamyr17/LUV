<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['usuarioId'])) {
    echo json_encode(['error' => 'Usuario no autenticado']);
    exit;
}

include_once '../business/usuarioMensajeBusiness.php';

$usuarioMensajeBusiness = new UsuarioMensajeBusiness();

function registrarMensajeEnArchivo($usuarioId, $archivo, $mensaje, $usuarioMensajeBusiness) {
    asegurarCarpetaUsuario($usuarioId, $usuarioMensajeBusiness);
    $nombreUsuario = $usuarioMensajeBusiness->getNombreUsuarioPorId($usuarioId);
    $rutaArchivo = "../resources/mensajes/" . strtolower(str_replace(' ', '-', $nombreUsuario)) . "/{$archivo}.dat";

    error_log("Intentando escribir en: " . $rutaArchivo); // Agrega esta línea para depuración

    // Abre el archivo en modo de adición
    $file = fopen($rutaArchivo, "a");
    if ($file) {
        fwrite($file, $mensaje . PHP_EOL); // Escribe el mensaje y agrega una nueva línea
        fclose($file);
        error_log("Escritura exitosa en: " . $rutaArchivo); // Agrega esta línea para confirmar
    } else {
        error_log("Error al abrir el archivo: " . $rutaArchivo); // Agrega esta línea para depurar error
    }
}

if (isset($_POST['getAmigoDetalles'])) {
    $amigoId = $_POST['amigoId'] ?? null;
    if (!$amigoId) {
        echo json_encode(['error' => 'ID de amigo no proporcionado']);
        exit;
    }
    
    $amigoDetalles = $usuarioMensajeBusiness->getUsuarioDetalles($amigoId);
    
    if ($amigoDetalles) {
        // No necesitas modificar el valor; simplemente lo envías tal cual
        echo json_encode($amigoDetalles);
    } else {
        echo json_encode(['error' => 'Amigo no encontrado']);
    }
    exit;
}

if (isset($_POST['getUsuariosParaChat'])) {
    $usuarios = $usuarioMensajeBusiness->getUsuariosParaChat($_SESSION['usuarioId']);
    
    // Asegúrate de incluir el campo de la imagen en la respuesta
    foreach ($usuarios as &$usuario) {
        if (empty($usuario['profilePic']) || !file_exists($_SERVER['DOCUMENT_ROOT'] . $usuario['profilePic'])) {
            $usuario['profilePic'] = $usuario['profilePic'];
             // Ruta a tu imagen por defecto
        } else {
            $usuario['profilePic'] = '../resources/img/profile/no-pfp.png';
             // Ajustar la ruta para que sea accesible desde el navegador
        }
    }

    header('Content-Type: application/json');
    echo json_encode($usuarios ?: []);
    exit;
}

if (isset($_POST['getMensajes'])) {
    $amigoId = $_POST['amigoId'] ?? null;
    $lastMessageId = $_POST['lastMessageId'] ?? 0;

    if (!$amigoId) {
        echo json_encode(['error' => 'ID de amigo no proporcionado']);
        exit;
    }

    $mensajes = $usuarioMensajeBusiness->getMensajes($_SESSION['usuarioId'], $amigoId, $lastMessageId);

    echo json_encode($mensajes ?: []);
    exit;
}

// Modificación en la sección de enviarMensaje para guardar en .dat
if (isset($_POST['enviarMensaje'])) {
    $amigoId = $_POST['amigoId'] ?? null;
    $mensaje = $_POST['mensaje'] ?? '';

    if (!$amigoId || empty(trim($mensaje))) {
        echo json_encode(['error' => 'ID de amigo o mensaje no proporcionado']);
        exit;
    }

    // Guardar el mensaje en la base de datos
    $resultado = $usuarioMensajeBusiness->enviarMensaje($_SESSION['usuarioId'], $amigoId, $mensaje, date("Y-m-d H:i:s"));

    if ($resultado) {
        // Mensaje de registro para el archivo .dat del usuario que envía y el usuario que recibe
        $mensajeEnviado = "Para {$amigoId}: {$mensaje}";
        $mensajeRecibido = "De {$_SESSION['usuarioId']}: {$mensaje}";

        // Registrar el mensaje en el archivo de enviados del usuario que envía
        registrarMensajeEnArchivo($_SESSION['usuarioId'], "enviados", $mensajeEnviado, $usuarioMensajeBusiness);

        // Registrar el mensaje en el archivo de recibidos del usuario que recibe
        registrarMensajeEnArchivo($amigoId, "recibidos", $mensajeRecibido, $usuarioMensajeBusiness);
    }

    echo json_encode(['success' => $resultado ? true : false, 'error' => $resultado ? null : 'No se pudo enviar el mensaje']);
    exit;
}

if (isset($_POST['getUsuarioDetalles'])) {
    $usuarioId = $_SESSION['usuarioId'];
    $usuarioDetalles = $usuarioMensajeBusiness->getUsuarioDetalles($usuarioId);

    echo json_encode($usuarioDetalles ?: ['error' => 'Usuario no encontrado']);
    exit;
}

function asegurarCarpetaUsuario($usuarioId, $usuarioMensajeBusiness) {
    // Obtener el nombre de usuario de la base de datos
    $nombreUsuario = $usuarioMensajeBusiness->getNombreUsuarioPorId($usuarioId);

    if ($nombreUsuario) {
        $carpetaUsuario = "../resources/mensajes/" . strtolower(str_replace(' ', '-', $nombreUsuario));

        if (!file_exists($carpetaUsuario)) {
            mkdir($carpetaUsuario, 0777, true); // Crea la carpeta si no existe
        }
    }
}


