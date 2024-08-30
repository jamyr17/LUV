<?php
  include "../action/sessionUserAction.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LUV Recomendaciones</title>
</head>

<body>
    <button onclick="window.location.href='../view/userWantedProfileView.php';">Volver</button>

    <?php
    // Verificar si hay perfiles filtrados en la sesión
    if (isset($_SESSION['perfilesMatcheados']) && !empty(isset($_SESSION['perfilesMatcheados']))) {
        $perfilesFiltrados = $_SESSION['perfilesMatcheados'];

        echo "<h3>Estas personas cumplen con lo que estás buscando</h3>";
        echo "<ul>";
        foreach ($perfilesFiltrados as $perfil) {
            echo "<li>";
            echo "Nombre: " . htmlspecialchars($perfil['primerNombre']) . "<br>";
            echo "Apellido: " . htmlspecialchars($perfil['primerApellido']) . "<br>";
            echo "Nombre de usuario: " . htmlspecialchars($perfil['nombreUsuario']) . "<br>";
            echo "Coincidencias: " . htmlspecialchars($perfil['coincidencias']) . "<br>";
            echo "Afinidad general = " . htmlspecialchars($perfil['ponderado']) . "%<br>";

            if (isset($perfil['pfp']) && !empty($perfil['pfp'])) {
                if (file_exists($perfil['pfp'])) {
                    echo '<img src="' . htmlspecialchars($perfil['pfp']) . '" alt="Perfil" style="max-width: 150px; max-height: 150px;">';
                } 
            }

            echo "</li>";
        }
        echo "</ul>";
    } else {
        // Mensaje divertido si no hay perfiles
        echo "<h3>Ups!</h3>";
        echo "<p>No se encontraron perfiles que coincidan con lo que buscas... ¡Pero no te preocupes, seguro que el amor está a la vuelta de la esquina!</p>";
    }
    ?>

</body>
</html>
