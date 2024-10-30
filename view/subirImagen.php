<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Imagen a Sirv con Depuración</title>
</head>
<body>
    <h1>Subir Imagen a Sirv</h1>

    <!-- Formulario para subir la imagen -->
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="imagen">Selecciona una imagen:</label>
        <input type="file" name="imagen" id="imagen" accept="image/*" required>
        <button type="submit">Subir Imagen</button>
    </form>

    <!-- Resultado -->
    <div>
        <?php
        // Incluir el archivo con las funciones
        require_once '../action/gestionAPIImagenesAction.php';

        // Verificar si el formulario ha sido enviado y procesar la imagen
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['imagen'])) {
            $imagen = $_FILES['imagen']['tmp_name'];
            $nombreOriginal = $_FILES['imagen']['name'];
            $resultado = subirImagenASirv($imagen, $nombreOriginal);

            // Mostrar resultado en pantalla
            if (filter_var($resultado, FILTER_VALIDATE_URL)) {
                echo "<p style='color: green;'>Imagen subida exitosamente. URL de la imagen: <a href='$resultado'>$resultado</a></p>";
            } else {
                echo "<p style='color: red;'>Error: $resultado</p>";
            }
        }

        ?>
    </div>
</body>
</html>
