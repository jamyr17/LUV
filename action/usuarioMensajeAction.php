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

// Obtener la lista de usuarios para el chat, con su estado de disponibilidad
if (isset($_POST['getUsuariosParaChat'])) {
    $usuarios = $usuarioMensajeBusiness->getUsuariosParaChat($_SESSION['usuarioId']);
    header('Content-Type: application/json');
    echo json_encode($usuarios ?: []);
    exit;
}

// Obtener mensajes mediante long polling y marcar los mensajes como leídos
if (isset($_POST['getMensajes'])) {
    $amigoId = $_POST['amigoId'];
    $lastMessageId = $_POST['lastMessageId'] ?? 0;

    // Long polling para esperar nuevos mensajes hasta un tiempo máximo de 20 segundos
    $start = time();
    $timeout = 20;

    while (time() - $start < $timeout) {
        $mensajes = $usuarioMensajeBusiness->getMensajes($_SESSION['usuarioId'], $amigoId, $lastMessageId);

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

// Enviar mensaje y registrar la hora de envío
if (isset($_POST['enviarMensaje'])) {
    $amigoId = $_POST['amigoId'];
    $mensaje = $_POST['mensaje'];

    // Asegúrate de que el método `enviarMensaje` está actualizado para aceptar la fecha como cuarto parámetro
    $resultado = $usuarioMensajeBusiness->enviarMensaje($_SESSION['usuarioId'], $amigoId, $mensaje, date("Y-m-d H:i:s"));

    header('Content-Type: application/json');
    if ($resultado) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'No se pudo enviar el mensaje']);
    }
    exit;
}

// Cambiar la disponibilidad del usuario al iniciar o cerrar sesión
if (isset($_POST['actualizarDisponibilidad'])) {
    $estado = $_POST['estado'];
    $resultado = $usuarioMensajeBusiness->actualizarDisponibilidadUsuario($_SESSION['usuarioId'], $estado);

    header('Content-Type: application/json');
    if ($resultado) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'No se pudo actualizar la disponibilidad']);
    }
    exit;
}
