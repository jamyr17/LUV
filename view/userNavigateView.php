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
    <title>Navegación de Usuarios</title>
    <style>
        #nuevo-boton {
            display: none;
        }
    </style>
    <script>
        $(document).ready(function() {
            fetch('../action/personalProfileAction.php', {
                    method: 'POST',
                    tieneInfoCompleta: 'buscar'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success !== true) {
                        $('#nuevo-boton').css('display', 'block'); 
                        console.log("Tiene la información completa para buscar conexiones");
                    } else {
                        console.log("No tiene la información completa para buscar conexiones");
                    }
                })
                .catch(error => console.error('Error de red:', error));
        });
    </script>
</head>

<body>

    <h3>Bienvenid@</h3>
    <div>
        <?php
        if (isset($_SESSION['nombreUsuario'])) {
            $nombreUsuario = $_SESSION['nombreUsuario'];
            echo "<p>$nombreUsuario</p>";
        }
        ?>
    </div>

    <div>
        <form method="post" action="../action/sessionUserAction.php">
            <button type="submit" class="btn btn-success" name="logout" id="logout">Cerrar sesión</button>
        </form>
    </div>

    <div id="div-redireccionamiento">
        <form id="form-redireccionamiento" method="get" action="">
            <p><button type="submit" formaction="userPersonalProfileCarruselView.php">Modelar tu perfil</button></p>
            <p><button type="submit" formaction="userWantedProfileView.php">Modelar tu búsqueda</button></p>
            <p><button type="submit" formaction="activitiesCalendarView.php">Ver actividades</button></p>
            <p id="nuevo-boton">
                <button type="submit" formaction="conexionesView.php">Buscar conexiones</button>
            </p>
            <p><button type="submit" formaction="usuarioMensajeBandaView.php">Ver chat</button></p>
        </form>
    </div>

</body>

</html>