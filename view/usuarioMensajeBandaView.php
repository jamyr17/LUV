<?php
include_once '../action/sessionUserAction.php'; // Verifica la sesión antes de cargar la vista
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Lista de Chats</title>
    <link rel="stylesheet" href="../styles/chat.css">
</head>
<body>
    <div class="container">
        <h1>Lista de Chats</h1>
        <ul id="chatList"></ul>
    </div>
    <script>
        fetch('../action/usuarioMensajeAction.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'getUsuariosParaChat=true'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                console.error('Error:', data.error);
                return;
            }
            console.log(data);
            data.forEach(user => {
                let li = document.createElement("li");
                li.innerHTML = `<a href="usuarioMensajeChatView.php?amigoId=${user.id}">${user.nombre}</a>`;
                document.getElementById("chatList").appendChild(li);
            });
        })
        .catch(error => {
            console.error('Error fetching chat list:', error);
        });
    </script>
</body>
</html>
