<?php
session_start();

if ($_SESSION["tipoUsuario"] == "Usuario" || empty($_SESSION["tipoUsuario"])) {
    header("location: ./login.php?error=accessDenied");
}

include '../bussiness/imagenBusiness.php';

$imagenBusiness = new ImagenBusiness();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LUV</title>
    <script>
        function actionConfirmation(mensaje) {
            return confirm(mensaje);
        }

        function showMessage(mensaje) {
            alert(mensaje);
        }

        async function updateOptions() {
            const type = document.getElementById("idOptions").value;
            const select = document.getElementById("dynamic-select");

            select.innerHTML = '';

            if (!type) return;

            try {
                const response = await fetch(`../data/getData.php?type=${encodeURIComponent(type)}`);
                if (!response.ok) throw new Error('Network response was not ok');

                const data = await response.json();

                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.text = item.name;
                    select.add(option);
                });
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        }

        function updateFileName() {
            const select = document.getElementById("dynamic-select");
            const hiddenNameField = document.getElementById("dynamic-select-name");
            hiddenNameField.value = select.options[select.selectedIndex].text;
        }

        function updateHiddenIdOptions() {
            const hiddenIdOptionsField = document.getElementById("idOptionsHidden");
            hiddenIdOptionsField.value = document.getElementById("idOptions").value;
        }
    </script>
</head>

<body>
    <header>
        <nav class="navbar bg-body-tertiary"></nav>
    </header>

    <div class="container mt-3">

        <section id="alerts">
            <?php
            if (isset($_GET['error'])) {
                $mensaje = "Ocurrió un error debido a ";
                $mensaje .= match ($_GET['error']) {
                    "emptyField" => "campo(s) vacío(s).",
                    "numberFormat" => "ingreso de valores númericos.",
                    "dbError" => "un problema al procesar la transacción.",
                    "exist" => "que dicha imagen ya existe.",
                    default => "un problema inesperado.",
                };
            } else if (isset($_GET['success'])) {
                $mensaje = match ($_GET['success']) {
                    "inserted" => "Imagen creada correctamente.",
                    "updated" => "Imagen actualizada correctamente.",
                    "deleted" => "Imagen eliminada correctamente.",
                    default => "Transacción realizada.",
                };
            }

            if (isset($mensaje)) {
                echo "<script>showMessage('$mensaje')</script>";
            }
            ?>
        </section>



        <section id="form">
            <div class="container">
                <button onclick="window.location.href='../indexView.php';">Volver</button>
                <div class="text-center mb-4">
                    <h3>Agregar una nueva imagen</h3>
                    <p class="text-muted">Complete el formulario para añadir una nueva imagen</p>
                </div>

                <div class="container d-flex justify-content-center">
                    <label for="idOptions">Opciones:</label>
                    <select id="idOptions" name="idOptions" onchange="updateOptions(); updateHiddenIdOptions();">
                        <option value="">Seleccione una opción</option>
                        <option value="1">Universidad</option>
                        <option value="2">Área Conocimiento</option>
                        <option value="3">Género</option>
                        <option value="4">Orientación sexual</option>
                        <option value="5">Campus</option>
                    </select>
                </div>

                <form method="post" action="../action/imagenAction.php" enctype="multipart/form-data" style="width: 50vw; min-width:300px;">
                    <input type="hidden" name="idOptionsHidden" id="idOptionsHidden">
                    <input type="hidden" name="dynamic-select-name" id="dynamic-select-name">
                    <div>
                        <label for="dynamic-select">Seleccione:</label>
                        <select id="dynamic-select" name="dynamic-select" onchange="updateFileName()">
                        </select>
                    </div>

                    <div class="mt-3">
                        <label for="imageUpload">Subir imagen:</label>
                        <input type="file" id="imageUpload" name="imageUpload" accept="image/*">
                    </div>

                    <div class="mt-3">
                        <button type="submit" name="create" id="create">Enviar</button>
                    </div>
                </form>

                <table class="table mt-3">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $imagen = $imagenBusiness->getAllTbimagen();
                        $mensajeActualizar = "¿Desea actualizar esta imagen?";
                        $mensajeEliminar = "¿Desea eliminar esta imagen?";
                        if ($imagen != null) {
                            foreach ($imagen as $imag) {
                                echo '<tr>';
                                echo '<form method="post" enctype="multipart/form-data" action="../action/imagenAction.php">';
                                echo '<input type="hidden" name="id" value="' . htmlspecialchars($imag->getTbImagenId()) . '">';
                                echo '<td>' . htmlspecialchars($imag->getTbImagenId()) . '</td>';
                                echo '<td><input type="text" name="dynamic-select-name" id="dynamic-select-name" value="' . htmlspecialchars($imag->gettbImagenNombre()) . '" class="form-control" /></td>';
                                echo '<td><input type="file" id="imageUpload" name="imageUpload" accept="image/*" value="' . htmlspecialchars($imag->gettbImagenDirectorio()) . '" class="form-control" /></td>';
                                echo '<td>';
                                echo "<button type='submit' class='btn btn-warning me-2' name='update' id='update' onclick='return actionConfirmation(\"$mensajeActualizar\")'>Actualizar</button>";
                                echo "<button type='submit' class='btn btn-danger' name='delete' id='delete' onclick='return actionConfirmation(\"$mensajeEliminar\")'>Eliminar</button>";
                                echo '</td>';
                                echo '</form>';
                                echo '</tr>';
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</body>

</html>