<?php
include "../action/sessionAdminAction.php";
include '../business/valorBusiness.php';
include '../business/criterioBusiness.php';
include '../action/functions.php';

$valorBusiness = new ValorBusiness();
$criterioBusiness = new CriterioBusiness();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="../js/autocomplete.js" defer></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <title>LUV</title>
    <script>
        function actionConfirmation(mensaje) {
            var response = confirm(mensaje)
            if (response == true) {
                return true
            } else {
                return false
            }
        }

        function showMessage(mensaje) {
            alert(mensaje);
        }

        function validateForm() {
            var nombre = document.getElementById("nombre").value;

            var maxLength = 255;

            if (nombre.length > maxLength) {
                alert("El nombre no puede tener más de " + maxLength + " caracteres.");
                return false;
            }

            return true;
        }

        function toggleDeletedValores() {
            var section = document.getElementById("table-deleted");
            if (section.style.display === "none") {
                section.style.display = "block";
            } else {
                section.style.display = "none";
            }
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
                    "exist" => "que dicho valor ya existe.",
                    "alike" => "que el nombre es muy similar.",
                    "nameTooLong" => "que el nombre es demasiado largo, el limite es de 255 caracteres.",
                    default => "un problema inesperado.",
                };
            } else if (isset($_GET['success'])) {
                $mensaje = match ($_GET['success']) {
                    "inserted" => "Valor creado correctamente.",
                    "updated" => "Valor actualizado correctamente.",
                    "deleted" => "Valor eliminado correctamente.",
                    "restored" => "Valor restaurado correctamente.",
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
                <button onclick="window.location.href='../index.php';">Volver</button>
                <form method="post" action="../action/sessionAdminAction.php">
                    <button type="submit" class="btn btn-success" name="logout" id="logout">Cerrar sesión</button>
                </form>

                <div class="text-center mb-4">
                    <h3>Agregar un nuevo valor</h3>
                    <p class="text-muted">Complete el formulario para añadir un nuevo valor</p>
                </div>
                <div class="container d-flex justify-content-center">
                    <form method="post" action="../action/valorAction.php" style="width: 50vw; min-width:300px;" onsubmit="return validateForm()">
                        <input type="hidden" name="idValor" value="<?php echo htmlspecialchars($idValor); ?>">

                        <label for="idCriterio">Criterio:</label>
                        <select name="idCriterio" id="idCriterio" onchange="updateCriterioNombre()">
                            <?php
                            $criterios = $criterioBusiness->getAllTbCriterio();
                            $valorSeleccionado = isset($_SESSION['formCrearData']['idCriterio']) ? $_SESSION['formCrearData']['idCriterio'] : '';

                            if ($criterios != null) {
                                foreach ($criterios as $criterio) {
                                    $selected = ($criterio->getTbCriterioId() == $valorSeleccionado) ? 'selected' : '';
                                    echo '<option value="' . htmlspecialchars($criterio->getTbCriterioId()) . '" ' . $selected . '>' . htmlspecialchars($criterio->getTbCriterioNombre()) . '</option>';
                                }
                            }
                            ?>
                        </select><br>

                        <input type="hidden" name="criterioNombre" id="criterioNombre" value="">
                        <input type="hidden" id="type" name="type" value="valor"> <!-- Campo oculto para el tipo de objeto -->

                        <label for="nombre" class="form-label">Nombre: </label>
                        <?php generarCampoTexto('nombre', 'formCrearData', 'Nombre de la opción', '') ?>

                        <div>
                            <button type="submit" class="btn btn-success" name="create" id="create">Crear</button>
                        </div>
                    </form>
                </div>

                <script>
                    function updateCriterioNombre() {
                        var select = document.getElementById('idCriterio');
                        var nombre = select.options[select.selectedIndex].text;
                        document.getElementById('criterioNombre').value = nombre;
                    }

                    updateCriterioNombre();
                </script>
            </div>
        </section>
        <section id="table">
            <div class="text-center mb-4">
                <h3>Valores registrados</h3>
            </div>

            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Criterio</th>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $logicaArchivosDat = new LogicaArchivosDat();
                    $criterios = $logicaArchivosDat->obtenerCriterios(); // Obtener todos los criterios
                    $mensajeActualizar = "¿Desea actualizar este valor?";
                    $mensajeEliminar = "¿Desea eliminar este valor?";
                    $contador = 1; // Inicializamos el contador

                    // Suponiendo que tienes un método para obtener todos los valores de un criterio específico
                    foreach ($criterios as $criterio) {
                        $valores = $logicaArchivosDat->obtenerValoresDeCriterio($criterio); // Obtener valores del archivo .dat

                        if ($valores != null) {
                            foreach ($valores as $valor) {
                                echo '<tr>';
                                echo '<td>' . $contador . '</td>'; // Muestra el número de la fila
                                echo '<td>' . htmlspecialchars($criterio) . '</td>'; // Mostrar el nombre del criterio
                                echo '<td><input type="text" class="form-control" value="' . htmlspecialchars($valor) . '" readonly></td>'; // Mostrar el nombre del valor en un campo de texto

                                // Botones de acción (puedes adaptar la lógica según necesites)
                                echo '<td>';
                                echo "<form method='post' action='../action/valorAction.php' onsubmit='return validateForm()'>";
                                echo "<input type='hidden' name='criterio' value='" . htmlspecialchars($criterio) . "'>"; // Añadir el criterio
                                echo "<button type='submit' class='btn btn-warning me-2' name='update' id='update' onclick='return actionConfirmation(\"$mensajeActualizar\")'>Actualizar</button>";
                                echo "<button type='submit' class='btn btn-danger' name='delete' id='delete' onclick='return actionConfirmation(\"$mensajeEliminar\")'>Eliminar</button>";
                                echo '</form>';
                                echo '</td>';

                                echo '</tr>';
                                $contador++; // Incrementamos el contador en cada iteración
                            }
                        }
                    }

                    ?>
                </tbody>
            </table>
        </section>


        <section id="table-deleted" style="display: none;">
            <div class="text-center mb-4">
                <h3>Valores eliminados</h3>
            </div>

            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Criterio</th>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $valoresEliminados = $valorBusiness->getAllDeletedTbValor();
                    $criterios = $criterioBusiness->getAllTbCriterio();

                    if ($valoresEliminados != null) {
                        foreach ($valoresEliminados as $valores) {
                            $criterioNombre = '';
                            foreach ($criterios as $criterio) {
                                if ($criterio->getTbCriterioId() == $valores->getTbCriterioId()) {
                                    $criterioNombre = $criterio->getTbCriterioNombre();
                                    break;
                                }
                            }

                            echo '<tr>';
                            echo '<form method="post" enctype="multipart/form-data" action="../action/valorAction.php" onsubmit="return validateForm()">';
                            echo '<input type="hidden" name="idValor" value="' . htmlspecialchars($valores->getTbValorId()) . '">';
                            echo '<td>' . htmlspecialchars($valores->getTbValorId()) . '</td>';
                            echo '<td><input type="text" class="form-control" name="criterioNombre" value="' . htmlspecialchars($criterioNombre) . '" readonly></td>';
                            echo '<td><input type="text" class="form-control" name="nombre" value="' . htmlspecialchars($valores->getTbValorNombre()) . '" readonly></td>';
                            echo '<td><input type="submit" name="restore" id="restore" value="Restaurar" onclick="return actionConfirmation(\'¿Desea restaurar?\')"></td>';
                            echo '</form>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr>';
                        echo '<td colspan="4">No hay valores eliminados</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </section>

        <button onclick="toggleDeletedValores()" style="margin-top: 20px;">Ver/Ocultar Valores Eliminados</button>

    </div>

</body>

</html>