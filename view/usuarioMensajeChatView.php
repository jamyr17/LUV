<?php
include_once '../action/sessionUserAction.php'; // Asegura que el usuario esté autenticado y en sesión
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Chat de LUV</title>
    <link rel="stylesheet" href="../styles/chat.css">
</head>
<body>
    <div class="container">
        <!-- Encabezado con nombre y estado -->
        <div class="header">
            <button onclick="volver()">⬅ Volver</button>
            <h2 id="amigoNombre">Nombre del Amigo</h2>
            <span id="estado">Disponible</span>
        </div>

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

                if (!response.ok) throw new Error('Error en la solicitud al servidor');

                const mensajes = await response.json();

                if (mensajes.error) {
                    console.error('Error del servidor:', mensajes.error);
                    return;
                }

                if (mensajes.length > 0) {
                    const chatBox = document.getElementById("chatBox");

                    // Actualiza solo los mensajes nuevos
                    mensajes.forEach(mensaje => {
                        if (document.getElementById(`mensaje-${mensaje.tbusuariomensajeid}`)) return;

                        const mensajeDiv = document.createElement("div");
                        // Cambia de color según el usuario que envía el mensaje
                        mensajeDiv.className = mensaje.tbusuariomensajeentradaid == usuarioId ? "mensaje mensajePropio" : "mensaje mensajeAmigo";
                        mensajeDiv.id = `mensaje-${mensaje.tbusuariomensajeid}`;
                        mensajeDiv.innerHTML = `
                            <span>${mensaje.tbusuariomensajedescripcion}</span><br>
                            <small>${mensaje.tbusuariomensajefecha}</small>
                        `;
                        chatBox.appendChild(mensajeDiv);
                    });

                    lastMessageId = mensajes[mensajes.length - 1].tbusuariomensajeid;
                    chatBox.scrollTop = chatBox.scrollHeight;
                }

                setTimeout(obtenerMensajes, 1000);
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

                const result = await response.json();
                if (result.error) {
                    console.error('Error al enviar mensaje:', result.error);
                }

                document.getElementById("mensaje").value = '';
                obtenerMensajes(); // Llama a obtenerMensajes() inmediatamente después de enviar el mensaje
            } catch (error) {
                console.error('Error al enviar mensaje:', error);
            }
        }

        function volver() {
            window.history.back();
        }

        obtenerMensajes();
    </script>
</body>
</html>
