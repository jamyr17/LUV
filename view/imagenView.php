<?php
include "../action/sessionAdminAction.php";
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
        async function updateOptions(type, selectId, selectedValue) {
            const select = document.getElementById(selectId);
            select.innerHTML = '';

            const option = document.createElement('option');
            option.value = 0;
            option.text = "Seleccione una opción";
            select.add(option);

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

                // Seleccionar la opción correcta
                if (selectedValue) {
                    select.value = selectedValue;
                }
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        }

        async function updateAllDynamicSelects() {
            const selects = document.querySelectorAll('select[id^="idOptionsUpdate"]');
            selects.forEach(async (select) => {
                const type = select.value;
                const dynamicSelectId = select.getAttribute('data-dynamic-select-id');
                const selectedDynamicValue = select.getAttribute('data-selected-dynamic-value');
                await updateOptions(type, dynamicSelectId, selectedDynamicValue);
            });
        }

        function updateHiddenIdOptions() {
            const select = document.getElementById('idOptions');
            const idOptions = document.getElementById('idOptionsHidden');
            idOptions.value = select.value;
        }

        function updateFileName(selectId, nameFieldId) {
            const select = document.getElementById(selectId);
            const hiddenNameField = document.getElementById(nameFieldId);
            hiddenNameField.value = select.options[select.selectedIndex].text;
        }

        function updateFileNameU(selectId, nameFieldId) {
            const select = document.getElementById(selectId);
            const hiddenNameField = document.getElementById(nameFieldId);
            hiddenNameField.value = select.value;
        }

        function updateHiddenIdOptionsU(selectId, nameFieldId) {
            const select = document.getElementById(selectId);
            const idOptionsHidden = document.getElementById(nameFieldId);
            idOptionsHidden.value = select.value;
        }

        function actionConfirmation(mensaje) {
            return confirm(mensaje);
        }

        function showMessage(mensaje) {
            alert(mensaje);
        }

        document.addEventListener("DOMContentLoaded", () => {
            updateAllDynamicSelects();
        });
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
                    "fileDeleteError" => "no se borró el archivo en el server.",
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
                <form method="post" action="../action/sessionAdminAction.php">
                    <button type="submit" class="btn btn-success" name="logout" id="logout">Cerrar sesión</button>
                </form>

                <div class="text-center mb-4">
                    <h3>Agregar una nueva imagen</h3>
                    <p class="text-muted">Complete el formulario para añadir una nueva imagen</p>
                </div>

                <div class="container d-flex justify-content-center">
                    <label for="idOptions">Opciones:</label>
                    <select id="idOptions" name="idOptions" onchange="updateOptions(this.value, 'dynamic-select', ''); updateHiddenIdOptions();">
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
                        <select id="dynamic-select" name="dynamic-select" onchange="updateFileName('dynamic-select', 'dynamic-select-name')">
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
                            <th>Opciones</th>
                            <th>Registro</th>
                            <th>Archivo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $imagen = $imagenBusiness->getAllTbImagen();
                        $mensajeActualizar = "¿Desea actualizar esta imagen?";
                        $mensajeEliminar = "¿Desea eliminar esta imagen?";
                        if ($imagen != null) {
                            foreach ($imagen as $imag) {

                                $selectedOption = htmlspecialchars($imag->getTbImagenCrudId());
                                $selectedDynamic = htmlspecialchars($imag->getTbImagenRegistroId());

                                echo '<tr>';
                                echo '<form method="post" enctype="multipart/form-data" action="../action/imagenAction.php">';
                                echo '<input type="hidden" name="id" value="' . htmlspecialchars($imag->getTbImagenId()) . '">';
                                echo '<td>' . htmlspecialchars($imag->getTbImagenId()) . '</td>';
                                echo '<td><input type="text" name="nombreArchivo" id="nombreArchivo" value="' . htmlspecialchars($imag->getTbImagenNombre()) . '" class="form-control" /></td>';
                                echo '<td>
                                    <select 
                                     id="idOptionsUpdate_' . htmlspecialchars($imag->getTbImagenId()) . '"
                                     name="idOptionsUpdate" 

                                     data-dynamic-select-id="dynamic-select-update_' . htmlspecialchars($imag->getTbImagenId()) . '" 
                                     data-selected-dynamic-value="' . htmlspecialchars($selectedDynamic) . '" 
                                     data-dynamic-hidden-input="idOptionsHidden_' . htmlspecialchars($imag->getTbImagenId()) . '"
                                     data-dynamic-hidden-directorio="dynamic-directorio-update_' . htmlspecialchars($imag->getTbImagenId()) . '""
                                    onchange="updateOptions(this.value, this.getAttribute(\'data-dynamic-select-id\'), this.getAttribute(\'data-selected-dynamic-value\'));
                                    updateHiddenIdOptionsU(this.id, this.getAttribute(\'data-dynamic-hidden-input\'));">

                                        <option value="1"' . ($selectedOption == '1' ? ' selected' : '') . '>Universidad</option>
                                        <option value="2"' . ($selectedOption == '2' ? ' selected' : '') . '>Área Conocimiento</option>
                                        <option value="3"' . ($selectedOption == '3' ? ' selected' : '') . '>Género</option>
                                        <option value="4"' . ($selectedOption == '4' ? ' selected' : '') . '>Orientación sexual</option>
                                        <option value="5"' . ($selectedOption == '5' ? ' selected' : '') . '>Campus</option>
                                    </select>
                                </td>';
                                echo '<td>
                                    <input type="hidden" name="idOptionsHidden" id="idOptionsHidden_' . htmlspecialchars($imag->getTbImagenId()) . '">
                                    <input type="hidden" name="idRegistroHidden" id="dynamic-select-name-update_' . htmlspecialchars($imag->getTbImagenId()) . '">
                                    <input type="hidden" name="directorioActualHidden" id="dynamic-directorio-update_' . htmlspecialchars($imag->getTbImagenId()) . '" value="' . htmlspecialchars($imag->getTbImagenDirectorio()) . '">
                                    <input type="hidden" id="selectedDynamicValueUpdate_' . htmlspecialchars($imag->getTbImagenId()) . '" value="' . htmlspecialchars($selectedDynamic) . '" />
                                    <input type="hidden" name="dynamic-select-name-update" id="dynamic-select-name-update">

                                    <select 
                                     id="dynamic-select-update_' . htmlspecialchars($imag->getTbImagenId()) . '" 
                                     name="dynamic-select" 
                                     data-dynamic-select-name="dynamic-select-name-update_' . htmlspecialchars($imag->getTbImagenId()) . '"
                                    onchange="updateFileNameU(this.id, this.getAttribute(\'data-dynamic-select-name\'));
                                    updateFileName(this.id, \'dynamic-select-name-update\')">
                                    </select>
                                </td>';
                                echo '<td><input type="file" id="imageUpload_" name="imageUpload_" accept="image/*" class="form-control" /></td>';
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