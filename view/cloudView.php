<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Imagen a Cloudinary</title>
</head>
<body>
    <h1>Subir Imagen a Cloudinary</h1>

    <form action="" method="POST" enctype="multipart/form-data">
        <label for="imagen">Selecciona una imagen:</label>
        <input type="file" name="imagen" id="imagen" accept="image/*" required>
        <button type="submit">Subir Imagen</button>
    </form>

    <div>
        <?php
        // Incluir el archivo con las funciones de Cloudinary
        require_once '../action/cloud.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['imagen'])) {
            $imagen = $_FILES['imagen']['tmp_name'];
            $nombreOriginal = pathinfo($_FILES['imagen']['name'], PATHINFO_FILENAME);

            // Subir la imagen
            $resultado = subirImagenACloudinary($imagen, $nombreOriginal);

            // Mostrar el resultado
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
