<?php
session_start(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LUV Recomendaciones</title>
</head>

<body>
    
    <?php
    // Verificar si hay perfiles filtrados en la sesión
    if (isset($_SESSION['perfilesMatcheados'])) {
        $perfilesFiltrados = $_SESSION['perfilesMatcheados'];

        echo "<h3>Estas personas cumplen con lo que estás buscando</h3>";
        echo "<ul>";
        foreach ($perfilesFiltrados as $perfil) {
            echo "<li>";
            echo "Criterios: " . htmlspecialchars($perfil['criterio']) . "<br>";
            echo "Valores: " . htmlspecialchars($perfil['valor']) . "<br>";
            echo "Ponderado: " . htmlspecialchars($perfil['ponderado']) . "%<br>";
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
