<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (!isset($_SESSION['usuarioId'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Usuario no autenticado']);
    exit;
}

include_once '../business/usuarioMensajeBusiness.php';

$usuarioMensajeBusiness = new UsuarioMensajeBusiness();

// Obtener la lista de usuarios para el chat
if (isset($_POST['getUsuariosParaChat'])) {
    $usuarios = $usuarioMensajeBusiness->getUsuariosParaChat($_SESSION['usuarioId']);
    header('Content-Type: application/json');
    echo json_encode($usuarios ?: []);
    exit();
}

// Obtener mensajes mediante long polling
if (isset($_POST['getMensajes'])) {
    $amigoId = $_POST['amigoId'];
    $lastMessageId = $_POST['lastMessageId'] ?? 0;

    // Long polling para esperar nuevos mensajes hasta un tiempo máximo de 20 segundos
    $start = time();
    $timeout = 20;

    while (time() - $start < $timeout) {
        $mensajes = $usuarioMensajeBusiness->getMensajes($_SESSION['usuarioId'], $amigoId, $lastMessageId);

        // Si hay nuevos mensajes, los devolvemos inmediatamente
        if (count($mensajes) > 0) {
            header('Content-Type: application/json');
            echo json_encode($mensajes);
            exit;
        }

        // Espera 1 segundo antes de volver a comprobar para reducir la carga del servidor
        usleep(1000000);
    }

    // Si no hay nuevos mensajes, responde con un array vacío
    header('Content-Type: application/json');
    echo json_encode([]);
    exit;
}

// Enviar mensaje
if (isset($_POST['enviarMensaje'])) {
    $amigoId = $_POST['amigoId'];
    $mensaje = $_POST['mensaje'];

    $resultado = $usuarioMensajeBusiness->enviarMensaje($_SESSION['usuarioId'], $amigoId, $mensaje);

    header('Content-Type: application/json');
    if ($resultado) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'No se pudo enviar el mensaje']);
    }
    exit;
}
?>
