<!DOCTYPE html>
<html lang="en">
<head>
    <title>Lista de Chats</title>
    <link rel="stylesheet" href="../styles/amigos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="chat-header">
            <div class="user-info">
                <!-- Detalles del usuario en sesión -->
                <img src="" alt="Imagen de perfil" class="profile-image" id="usuarioImagen">
                <div>
                    <h1 id="usuarioNombre">Nombre del Usuario</h1>
                    <span class="status" id="usuarioCondicion">Active now</span>
                </div>
            </div>
            <button class="back-button" onclick="history.back()">Regresar</button>
        </div>

        <!-- Buscador de usuarios -->
        <div class="search-container">
            <input type="text" id="searchInput" placeholder="Buscar" oninput="filtrarUsuarios()">
            <button class="search-icon"><i class="fas fa-search"></i></button>

        </div>

        <div class="chat-list" id="chatList"></div>
    </div>

    <script>
        function filtrarUsuarios() {
            const searchInput = document.getElementById("searchInput").value.toLowerCase();
            const chatItems = document.querySelectorAll(".chat-item");

            chatItems.forEach(item => {
                const userName = item.querySelector(".name").textContent.toLowerCase();
                item.style.display = userName.includes(searchInput) ? "flex" : "none";
            });
        }

        // Obtener detalles del usuario en sesión
        function cargarUsuarioDetalles() {
            fetch('../action/usuarioMensajeAction.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'getUsuarioDetalles=true'
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error('Error:', data.error);
                } else {
                    document.getElementById("usuarioNombre").textContent = data.nombre;
                    document.getElementById("usuarioImagen").src = data.imagen;
                    document.getElementById("usuarioCondicion").textContent = data.condicion;
                }
            })
            .catch(error => console.error('Error fetching user details:', error));
        }

        cargarUsuarioDetalles();

        // Cargar lista de usuarios para chatear
        fetch('../action/usuarioMensajeAction.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: 'getUsuariosParaChat=true'
})
.then(response => response.json())
.then(data => {
    console.log("Respuesta de la API:", data); // Agrega esto para ver la respuesta
    if (Array.isArray(data)) {
        const chatList = document.getElementById("chatList");
        data.forEach(user => {
            let div = document.createElement("div");
            div.className = "chat-item";
            div.innerHTML = `
                <img src="${user.profilePic}" alt="${user.nombre}" class="profile-image">
                <div>
                    <div class="name">${user.nombre}</div>
                    <div class="status">${user.onlineStatus ? 'Active now' : 'Pulsa para chatear'}</div>
                </div>
                <span class="status-dot ${user.onlineStatus ? 'online' : 'offline'}"></span>
            `;
            div.onclick = () => {
                window.location.href = `usuarioMensajeChatView.php?amigoId=${user.id}`;
            };
            chatList.appendChild(div);
        });
    } else {
        console.error("La respuesta no es un array:", data);
    }
})
.catch(error => console.error('Error fetching chat list:', error));
    </script>
</body>
</html>
