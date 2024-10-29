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
        <form method="get" action=""><!-- Acá empezaríamos con genero, orientacion sexual, area de conocimiento, 
                                    Todo lo relacionado a la universidad va junto (misma vista):
                                                                                   universidad, 
                                                                                   campus,
                                                                                   colectivos (si pertenece o le gustan varios).
                                    Luego criterio y valor para el cierre.

                                    Con respecto al campus me parece que ya debería de estar asociada su información de
                                    especialización y región... Por eso no estarán presentes en el formulario. Además no son necesarios en BD...
                                     -->
            <p><button type="submit" formaction="userPersonalProfileCarruselView.php">Modelar tu perfil</button></p>
            <p><button type="submit" formaction="userWantedProfileView.php">Modelar tu búsqueda</button></p>
            <p><button type="submit" formaction="activitiesCalendarView.php">Ver actividades</button></p>
            <!--<p><button type="submit" formaction="userAffinityView.php">Analizar imagen</button></p>-->
        </form>
    </div>

</body>
</html>
