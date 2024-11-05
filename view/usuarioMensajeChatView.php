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
        <div class="amigo-info">
            <img id="amigoImagen" class="profile-image" src="../resources/img/profile/no-pfp.png" alt="Imagen de perfil">
            <div class="chat-info">
                <h2 id="amigoNombre">.</h2>
                <span class="status" id="estado">.</span>
            </div>
        </div>
    </div>
    <div id="chatBox"></div>
    <div class="message-input">
        <!-- Agregamos el evento oninput aquí -->
        <textarea id="mensaje" placeholder="Escribe tu mensaje..." oninput="actualizarContador()"></textarea>
        <button onclick="enviarMensaje()">➤</button>
    </div>
    <div id="contador">200 caracteres restantes</div> <!-- Contador debajo del campo de texto -->

</div>

<script>
    const amigoId = new URLSearchParams(window.location.search).get("amigoId");
    const usuarioId = <?php echo json_encode($_SESSION['usuarioId']); ?>;
    let lastMessageId = 0;

    function formatearFecha(fechaString) {
        const fecha = new Date(fechaString);
        const dia = fecha.getDate().toString().padStart(2, '0');
        const meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        const mes = meses[fecha.getMonth()];

        let horas = fecha.getHours();
        const minutos = fecha.getMinutes().toString().padStart(2, '0');
        const amPm = horas >= 12 ? 'PM' : 'AM';
        horas = horas % 12 || 12;

        return `${dia} de ${mes}, ${horas}:${minutos} ${amPm}`;
    }

    async function obtenerMensajes() {
        try {
            const response = await fetch('../action/usuarioMensajeAction.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `getMensajes=true&amigoId=${amigoId}&lastMessageId=${lastMessageId}`
            });

            const mensajes = await response.json();
            const chatBox = document.getElementById("chatBox");

            if (mensajes.length > 0) {
                mensajes.forEach(mensaje => {
                    const mensajeDiv = document.createElement("div");
                    mensajeDiv.className = mensaje.tbusuariomensajeentradaid == usuarioId ? "mensaje mensajePropio" : "mensaje mensajeAmigo";
                    const fechaFormateada = formatearFecha(mensaje.tbusuariomensajefecha);
                    mensajeDiv.innerHTML = `
                        <span>${mensaje.tbusuariomensajedescripcion}</span>
                        <small>${fechaFormateada}</small>
                    `;
                    chatBox.appendChild(mensajeDiv);
                });
                lastMessageId = mensajes[mensajes.length - 1].tbusuariomensajeid;
            }

            setTimeout(obtenerMensajes, 3000);
        } catch (error) {
            console.error('Error al obtener mensajes:', error);
        }
    }

    const MAX_CARACTERES = 200;

    function actualizarContador() {
        const mensaje = document.getElementById("mensaje").value;
        const caracteresRestantes = MAX_CARACTERES - mensaje.length;
        const contador = document.getElementById("contador");
        contador.textContent = `${caracteresRestantes} caracteres restantes`;
        contador.style.color = caracteresRestantes < 0 ? 'red' : 'black';
    }

    async function enviarMensaje() {
        const mensaje = document.getElementById("mensaje").value;
        if (!mensaje.trim()) return;

        if (mensaje.length > MAX_CARACTERES) {
            alert("El mensaje no puede tener más de 200 caracteres.");
            return;
        }

        try {
            await fetch('../action/usuarioMensajeAction.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `enviarMensaje=true&amigoId=${amigoId}&mensaje=${encodeURIComponent(mensaje)}`
            });
            document.getElementById("mensaje").value = ''; // Limpiar el campo de mensaje
            actualizarContador(); // Reiniciar el contador después de enviar
            obtenerMensajes();
        } catch (error) {
            console.error('Error al enviar mensaje:', error);
        }
    }

        const textarea = document.querySelector("textarea");
        const contador = document.getElementById("contador");

        textarea.addEventListener("input", () => {
            const remaining = 200 - textarea.value.length;
            contador.textContent = `${remaining} caracteres restantes`;

            // Cambios de color según el número de caracteres restantes
            if (remaining <= 20) {
                contador.classList.add("low");
                contador.classList.remove("warning");
            } else if (remaining <= 10) {
                contador.classList.add("warning");
                contador.classList.remove("low");
            } else {
                contador.classList.remove("low", "warning");
            }
        });

    async function obtenerAmigoDetalles() {
        try {
            const response = await fetch('../action/usuarioMensajeAction.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `getAmigoDetalles=true&amigoId=${amigoId}`
            });

            const amigoDetalles = await response.json();
            console.log(amigoDetalles);

            if (!amigoDetalles.error) {
                document.getElementById("amigoNombre").textContent = amigoDetalles.nombre;
                const imagenAmigo = amigoDetalles.imagen && amigoDetalles.imagen.trim() !== '' ? amigoDetalles.imagen : '../resources/img/profile/no-pfp.png';
                document.getElementById("amigoImagen").src = imagenAmigo;
                document.getElementById("estado").textContent = amigoDetalles.condicion;
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

    obtenerMensajes();
    obtenerAmigoDetalles();
</script>
</body>
</html>
