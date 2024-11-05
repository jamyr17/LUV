<?php
require __DIR__ . '/../vendor/autoload.php';
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class usuarioMensajeSockets implements MessageComponentInterface {
    protected $clientes; // Almacena todas las conexiones activas
    protected $conexionesUsuarios; // Mapea cada usuario con su conexión

    public function __construct() {
        $this->clientes = new \SplObjectStorage;
        $this->conexionesUsuarios = [];
    }

    public function onOpen(ConnectionInterface $conexion) {
        // Agrega la conexión a la lista de clientes
        $this->clientes->attach($conexion);
        
        // Extrae el ID del usuario desde los parámetros de la URL
        $queryParams = $conexion->httpRequest->getUri()->getQuery();
        parse_str($queryParams, $params);
        $usuarioId = $params['userId'] ?? null;

        if ($usuarioId) {
            $this->conexionesUsuarios[$usuarioId] = $conexion;
            echo "Usuario {$usuarioId} conectado: ({$conexion->resourceId})\n";
        } else {
            echo "Conexión establecida sin ID de usuario: ({$conexion->resourceId})\n";
        }
    }

    public function onMessage(ConnectionInterface $desde, $mensaje) {
        $datos = json_decode($mensaje, true);
        $usuarioDestinoId = $datos['toUserId'] ?? null;
        $contenidoMensaje = $datos['message'] ?? '';

        if ($usuarioDestinoId && isset($this->conexionesUsuarios[$usuarioDestinoId])) {
            // Envía el mensaje al usuario específico
            $this->conexionesUsuarios[$usuarioDestinoId]->send(json_encode([
                'fromUserId' => array_search($desde, $this->conexionesUsuarios),
                'message' => $contenidoMensaje
            ]));
            echo "Mensaje enviado a usuario {$usuarioDestinoId}\n";
        } else {
            // Envía un mensaje a todos los clientes conectados (broadcast)
            foreach ($this->clientes as $cliente) {
                if ($desde !== $cliente) {
                    $cliente->send($mensaje);
                }
            }
            echo "Mensaje broadcast enviado\n";
        }
    }

    public function onClose(ConnectionInterface $conexion) {
        // Elimina la conexión de la lista de clientes y del mapeo de usuarios
        $this->clientes->detach($conexion);
        $usuarioId = array_search($conexion, $this->conexionesUsuarios);
        
        if ($usuarioId !== false) {
            unset($this->conexionesUsuarios[$usuarioId]);
            echo "Conexión cerrada para usuario {$usuarioId}: ({$conexion->resourceId})\n";
        } else {
            echo "Conexión cerrada: ({$conexion->resourceId})\n";
        }
    }

    public function onError(ConnectionInterface $conexion, \Exception $e) {
        echo "Error: {$e->getMessage()}\n";
        $conexion->close();
    }

    // Método para enviar un mensaje específico a un usuario desde otra fuente
    public function enviarMensajeEspecifico($usuarioDestinoId, $mensaje) {
        if (isset($this->conexionesUsuarios[$usuarioDestinoId])) {
            $this->conexionesUsuarios[$usuarioDestinoId]->send(json_encode($mensaje));
            echo "Mensaje enviado a usuario {$usuarioDestinoId} desde el servidor\n";
        }
    }
}

// Código para iniciar el servidor WebSocket
require dirname(__DIR__) . '/vendor/autoload.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new usuarioMensajeSockets()
        )
    ),
    8081 // Puerto donde se ejecutará el servidor
);

echo "Servidor WebSocket iniciado en puerto 8081\n";
$server->run();
