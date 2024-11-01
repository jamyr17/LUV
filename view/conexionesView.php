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
            if (isset($_SESSION['perfiles']) && !empty($_SESSION['perfiles'])) {
                $perfiles = $_SESSION['perfiles'];

                foreach ($perfiles as $perfil) {
                    $datosPerfil = $perfil[0];

                    echo '<div class="container">';
                    echo '    <div class="header">';
                    echo '        <img src="' . htmlspecialchars($datosPerfil['pfp']) . '" alt="Foto de usuario" class="profile-picture">';
                    echo '        <span class="username">' . htmlspecialchars($datosPerfil['nombreUsuario']) . '</span>';
                    echo '    </div>';
                    echo '    <div class="image-container">';
                    echo '        <img src="' . htmlspecialchars($datosPerfil['ImagenURL']) . '" ';
                    echo '             alt="Imagen de publicación" class="post-image" ';
                    echo '             data-image-url="' . htmlspecialchars($datosPerfil['ImagenURL']) . '">'; // Añadir data-image-url
                    echo '    </div>';
                    echo '</div>';
                }
            }
        ?>

    </div>


    <script>
        $(document).ready(function() {
            let startTime = null;
            let activeRegion = null;

            // Evento para detectar el movimiento dentro de la imagen y calcular la región
            $('.post-image').on('mousemove', function(event) {
                const imageWidth = $(this).width();
                const imageHeight = $(this).height();
                const mouseX = event.offsetX;
                const mouseY = event.offsetY;

                // División 3x3 lógica de la imagen, con límite entre 1 y 3 para evitar valores fuera de rango
                const regionX = Math.min(3, Math.max(1, Math.floor(mouseX / (imageWidth / 3)) + 1));
                const regionY = Math.min(3, Math.max(1, Math.floor(mouseY / (imageHeight / 3)) + 1));
                const currentRegion = `${regionY},${regionX}`;

                // Detecta si el mouse ha cambiado de región
                if (activeRegion !== currentRegion) {
                    if (startTime && activeRegion) {
                        const duration = Date.now() - startTime; // Tiempo en ms dentro de la región anterior
                        const imageURL = $(this).data('image-url'); // URL de la imagen
                        console.log(`Left region ${activeRegion} after ${duration}ms`);
                        guardarSegmentacion(activeRegion, duration, 1, imageURL); // Envía datos
                    }
                    activeRegion = currentRegion;
                    startTime = Date.now(); // Inicia el tiempo para la nueva región
                    console.log(`Entering region ${activeRegion}`);
                }
            });

            // Evento para registrar la salida de la imagen y enviar los datos
            $('.post-image').on('mouseleave', function() {
                if (startTime && activeRegion) {
                    const duration = Date.now() - startTime;
                    const imageURL = $(this).data('image-url');
                    console.log(`Left region ${activeRegion} after ${duration}ms`);
                    guardarSegmentacion(activeRegion, duration, 1, imageURL);
                    startTime = null;
                    activeRegion = null;
                }
            });
        });

        // Función para enviar los datos al backend
        function guardarSegmentacion(region, duration, zoomScale, imageURL) {
            console.log("Datos enviados:", { region, duration, zoomScale, imageURL });
            if (!imageURL) {
                console.warn("La URL de la imagen está vacía, no se enviarán datos.");
                return;
            }

            fetch('../action/userAffinityAction.php', {
                method: 'POST',
                body: JSON.stringify({ region, duration, zoomScale, imageURL }),
                headers: { 'Content-Type': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    console.log("Datos guardados con éxito:", data.message);
                } else {
                    console.error("Error al guardar los datos:", data.message);
                }
            })
            .catch(error => {
                console.error('Error de red al enviar los datos:', error);
                alert("Ha ocurrido un error inesperado.");
            });
        }
    </script>


</body>

</html>