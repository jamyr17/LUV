<?php
include "../action/sessionUserAction.php";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <title>LUV</title>
    <script>
        $(document).ready(function() {
            fetch('../action/conexionesAction.php', {
                    method: 'POST',
                    body: JSON.stringify({
                        cargarPerfiles: 'perfiles'
                    }),
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success === true) {
                        console.log("Perfiles cargados con exito");
                    } else {
                        console.log("No se pudieron cargar los perfiles");
                    }
                })
                .catch(error => {
                    console.error('Error de red:', error);
                    console.log(response.error);
                    alert("Ha ocurrido un error inesperado, será redirigido a la página de inicio");
                    window.location.href = '../view/userNavigateView.php'; // Cambia esta línea según sea necesario
                });
        });
    </script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
            overflow-y: scroll;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 20px;
            /* Espacio entre publicaciones */
        }

        .header {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #e0e0e0;
        }

        .profile-picture {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .username {
            font-weight: bold;
        }

        .image-container {
            display: flex;
            justify-content: center;
            padding: 20px;
        }

        .post-image {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }
    </style>
</head>

<body>
    <section id="alerts">

        <?php

        if (isset($_GET['error'])) {
            $mensaje = "Ocurrió un error debido a ";
            $mensaje .= match (true) {
                $_GET['error'] == "datosNulos" => "El usuario no tiene información necesaria para la busqueda.",
                default => "un problema inesperado.",
            };
        } else if (isset($_GET['success'])) {
            $mensaje = match (true) {
                $_GET['success'] == "inserted" => "Se ha guardado el modelo de persona que buscas.",
                default => "Transacción realizada.",
            };
        }

        if (isset($mensaje)) {
            echo "<script>alert('$mensaje')</script>";
        }
        ?>

    </section>
    <div id="posts">
        <?php

        if (isset($_SESSION['perfiles']) && !empty(isset($_SESSION['perfiles']))) {
            $perfiles = $_SESSION['perfiles'];

            foreach ($perfiles as $perfil) {
                echo '<div class="container">';
                echo '    <div class="header">';
                echo '        <img src="' . htmlspecialchars($perfil['UsuarioID']) . '" alt="Foto de usuario" class="profile-picture">';
                echo '        <span class="username">' . htmlspecialchars($perfil['Genero']) . '</span>';
                echo '    </div>';
                echo '    <div class="image-container">';
                echo '        <img src="' . htmlspecialchars($perfil['OrientacionSexual']) . '" alt="Imagen de publicación" class="post-image">';
                echo '    </div>';
                echo '</div>';
            }
        }
        ?>
    </div>

</body>

</html>