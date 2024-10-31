<?php
include_once '../action/sessionUserAction.php'; // Asegura que el usuario esté autenticado y en sesión
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Chat en Tiempo Real</title>
    <link rel="stylesheet" href="../styles/chat.css">
</head>
<body>
    <div class="container">
        <h2>Chat en Tiempo Real</h2>
        <div id="chatBox"></div>
        <div style="display: flex; gap: 10px;">
            <textarea id="mensaje" placeholder="Escribe tu mensaje..."></textarea>
            <button onclick="enviarMensaje()">Enviar</button>
        </div>
    </div>

    <script>
        const amigoId = new URLSearchParams(window.location.search).get("amigoId");
        const usuarioId = <?php echo json_encode($_SESSION['usuarioId']); ?>;
        let lastMessageId = 0;

        async function obtenerMensajes() {
            try {
                const response = await fetch('../action/usuarioMensajeAction.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `getMensajes=true&amigoId=${amigoId}&lastMessageId=${lastMessageId}`
                });

                // Verificar si la respuesta fue exitosa
                if (!response.ok) throw new Error('Error en la solicitud al servidor');

                const mensajes = await response.json();

                // Verificar que no haya un error en la respuesta JSON
                if (mensajes.error) {
                    console.error('Error del servidor:', mensajes.error);
                    return;
                }

                if (mensajes.length > 0) {
                    const chatBox = document.getElementById("chatBox");
                    mensajes.forEach(mensaje => {
    // Evitar duplicados comprobando si el mensaje ya existe
                        if (document.getElementById(`mensaje-${mensaje.tbusuariomensajeid}`)) return;

                        const mensajeDiv = document.createElement("div");
                        mensajeDiv.className = mensaje.tbusuariomensajesalidaid == usuarioId ? "mensaje mensajePropio" : "mensaje mensajeAmigo";
                        mensajeDiv.id = `mensaje-${mensaje.tbusuariomensajeid}`; // Agrega un ID único
                        mensajeDiv.textContent = mensaje.tbusuariomensajedescripcion;
                        chatBox.appendChild(mensajeDiv);
                    });

                    // Actualiza `lastMessageId` después de procesar todos los mensajes
                    lastMessageId = mensajes[mensajes.length - 1].tbusuariomensajeid;
                    chatBox.scrollTop = chatBox.scrollHeight;
                }

                setTimeout(obtenerMensajes, 1000); // Continuar el long polling con una pausa de 1 segundo
            } catch (error) {
                console.error('Error al obtener mensajes:', error);
            }
        }

        async function enviarMensaje() {
            const mensaje = document.getElementById("mensaje").value;
            if (!mensaje.trim()) return;

            try {
                const response = await fetch('../action/usuarioMensajeAction.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `enviarMensaje=true&amigoId=${amigoId}&mensaje=${encodeURIComponent(mensaje)}`
                });

                // Verificar si el mensaje fue enviado correctamente
                const result = await response.json();
                if (result.error) {
                    console.error('Error al enviar mensaje:', result.error);
                }

                document.getElementById("mensaje").value = ''; // Limpiar campo de mensaje
            } catch (error) {
                console.error('Error al enviar mensaje:', error);
            }
        }

        // Iniciar long polling para recibir mensajes
        obtenerMensajes();
    </script>
</body>
</html>
