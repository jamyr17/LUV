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
        <div class="chat-header">
            <button onclick="volver()">⬅</button>
            <div class="chat-info">
                <img id="amigoImagen" src="../resources/img/profile/no-pfp.png" alt="Imagen de perfil">
                <h2 id="amigoNombre">Nombre del Amigo</h2>
                <span class="status" id="estado">Active now</span>
            </div>
        </div>
        <div id="chatBox"></div>
        <div class="message-input">
            <textarea id="mensaje" placeholder="Escribe tu mensaje..."></textarea>
            <button onclick="enviarMensaje()">➤</button>
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

                const mensajes = await response.json();
                if (mensajes.length > 0) {
                    const chatBox = document.getElementById("chatBox");
                    mensajes.forEach(mensaje => {
                        const mensajeDiv = document.createElement("div");
                        mensajeDiv.className = mensaje.tbusuariomensajeentradaid == usuarioId ? "mensaje mensajePropio" : "mensaje mensajeAmigo";
                        mensajeDiv.innerHTML = `
                            <span>${mensaje.tbusuariomensajedescripcion}</span>
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
                await fetch('../action/usuarioMensajeAction.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `enviarMensaje=true&amigoId=${amigoId}&mensaje=${encodeURIComponent(mensaje)}`
                });
                document.getElementById("mensaje").value = '';
                obtenerMensajes();
            } catch (error) {
                console.error('Error al enviar mensaje:', error);
            }
        }

        async function obtenerAmigoDetalles() {
    try {
        const response = await fetch('../action/usuarioMensajeAction.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `getAmigoDetalles=true&amigoId=${amigoId}`
        });

        const amigoDetalles = await response.json();

        if (!amigoDetalles.error) {
            document.getElementById("amigoNombre").textContent = amigoDetalles.nombre;
            document.getElementById("amigoImagen").src = amigoDetalles.imagen || '../resources/img/profile/no-pfp.png';
        } else {
            console.error(amigoDetalles.error);
        }
        } catch (error) {
            console.error('Error al obtener detalles del amigo:', error);
        }
        }


        function volver() {
            window.history.back();
        }

        // Llama a las funciones para cargar los mensajes y los detalles del amigo
        obtenerMensajes();
        obtenerAmigoDetalles();
    </script>
</body>
</html>
