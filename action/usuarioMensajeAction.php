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

if (isset($_POST['getAmigoDetalles'])) {
    $amigoId = $_POST['amigoId'] ?? null;
    if (!$amigoId) {
        echo json_encode(['error' => 'ID de amigo no proporcionado']);
        exit;
    }
    
    $amigoDetalles = $usuarioMensajeBusiness->getUsuarioDetalles($amigoId);
    echo json_encode($amigoDetalles ?: ['error' => 'Amigo no encontrado']);
    exit;
}

// Obtener la lista de usuarios para el chat
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


// Obtener mensajes con long polling
if (isset($_POST['getMensajes'])) {
    $amigoId = $_POST['amigoId'] ?? null;
    $lastMessageId = $_POST['lastMessageId'] ?? 0;

    if (!$amigoId) {
        echo json_encode(['error' => 'ID de amigo no proporcionado']);
        exit;
    }

    // Long polling para esperar nuevos mensajes hasta un tiempo máximo de 20 segundos
    $start = time();
    $timeout = 20;

    while (time() - $start < $timeout) {
        $mensajes = $usuarioMensajeBusiness->getMensajes($_SESSION['usuarioId'], $amigoId, $lastMessageId);

        if (count($mensajes) > 0) {
            echo json_encode($mensajes);
            exit;
        }

        // Espera 1 segundo antes de volver a comprobar para reducir la carga del servidor
        //usleep(100);
    }

    // Si no hay nuevos mensajes, responde con un array vacío
    echo json_encode([]);
    exit;
}

// Enviar mensaje
if (isset($_POST['enviarMensaje'])) {
    $amigoId = $_POST['amigoId'] ?? null;
    $mensaje = $_POST['mensaje'] ?? '';

    if (!$amigoId || empty(trim($mensaje))) {
        echo json_encode(['error' => 'ID de amigo o mensaje no proporcionado']);
        exit;
    }

    $resultado = $usuarioMensajeBusiness->enviarMensaje($_SESSION['usuarioId'], $amigoId, $mensaje, date("Y-m-d H:i:s"));

    echo json_encode(['success' => $resultado ? true : false, 'error' => $resultado ? null : 'No se pudo enviar el mensaje']);
    exit;
}

// Obtener detalles del usuario actual
if (isset($_POST['getUsuarioDetalles'])) {
    $usuarioId = $_SESSION['usuarioId'];
    $usuarioDetalles = $usuarioMensajeBusiness->getUsuarioDetalles($usuarioId);

    echo json_encode($usuarioDetalles ?: ['error' => 'Usuario no encontrado']);
    exit;
}

// Cambiar la disponibilidad del usuario
if (isset($_POST['actualizarDisponibilidad'])) {
    $estado = $_POST['estado'] ?? '';

    if (empty($estado)) {
        echo json_encode(['error' => 'Estado no proporcionado']);
        exit;
    }

    $resultado = $usuarioMensajeBusiness->actualizarDisponibilidadUsuario($_SESSION['usuarioId'], $estado);

    echo json_encode(['success' => $resultado ? true : false, 'error' => $resultado ? null : 'No se pudo actualizar la disponibilidad']);
    exit;
}

