<?php
  include "../action/sessionUserAction.php";
?>

<!DOCTYPE html>

<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navegación de Usuarios</title>
</head>
<body>

    <h3>Bienvenid@</h3>
    <div>  
        <?php
            if(isset($_SESSION['nombreUsuario'])){
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
    
    <div>
        <form method="get" action="">
            <p><button type="submit" formaction="userPersonalProfileView.php">Modelar tu perfil</button></p>
            <p><button type="submit" formaction="userWantedProfileView.php">Modelar tu búsqueda</button></p>
        </form>
    </div>

</body>
</html>
