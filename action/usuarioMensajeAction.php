<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (!isset($_SESSION['usuarioId'])) {
    // Responde con un mensaje de error si el usuario no está autenticado
    echo json_encode(['error' => 'Usuario no autenticado']);
    exit;
}

include_once '../business/usuarioMensajeBusiness.php';

$usuarioMensajeBusiness = new UsuarioMensajeBusiness();

if (isset($_POST['getMensajes'])) {
    $amigoId = $_POST['amigoId'];
    $lastMessageId = $_POST['lastMessageId'] ?? 0;

    $start = time();
    $timeout = 20;

    while (time() - $start < $timeout) {
        $mensajes = $usuarioMensajeBusiness->getMensajes($_SESSION['usuarioId'], $amigoId, $lastMessageId);

        if (count($mensajes) > 0) {
            echo json_encode($mensajes);
            exit;
        }

        usleep(1000000);
    }

    echo json_encode([]);
    exit;
}

if (isset($_POST['enviarMensaje'])) {
    $amigoId = $_POST['amigoId'];
    $mensaje = $_POST['mensaje'];
    $usuarioMensajeBusiness->enviarMensaje($_SESSION['usuarioId'], $amigoId, $mensaje);
    exit;
}

if (isset($_POST['getUsuariosParaChat'])) {
    $usuarios = $usuarioMensajeBusiness->getUsuariosParaChat();
    header('Content-Type: application/json');
    echo json_encode($usuarios);
    exit;
}
?>
