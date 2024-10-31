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
        let lastMessageId = 0;

        async function obtenerMensajes() {
            const response = await fetch('../view/usuarioMensajeAction.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `getMensajes=true&amigoId=${amigoId}&lastMessageId=${lastMessageId}`
            });
            const mensajes = await response.json();

            if (mensajes.length > 0) {
                const chatBox = document.getElementById("chatBox");
                mensajes.forEach(mensaje => {
                    const mensajeDiv = document.createElement("div");
                    mensajeDiv.className = mensaje.tbusuariomensajesalidaid == amigoId ? "mensaje mensajeAmigo" : "mensaje mensajePropio";
                    mensajeDiv.textContent = mensaje.tbusuariomensajedescripcion;
                    chatBox.appendChild(mensajeDiv);
                    lastMessageId = mensaje.tbusuariomensajeid;
                });
                chatBox.scrollTop = chatBox.scrollHeight;
            }

            obtenerMensajes(); // Continuar el long polling
        }

        async function enviarMensaje() {
            const mensaje = document.getElementById("mensaje").value;
            await fetch('../view/usuarioMensajeAction.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `enviarMensaje=true&amigoId=${amigoId}&mensaje=${mensaje}`
            });
            document.getElementById("mensaje").value = '';
        }

        obtenerMensajes();
    </script>
</body>
</html>
