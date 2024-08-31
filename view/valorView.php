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
    <title>LUV</title>
    <script>
        function actionConfirmation(mensaje){
      var response = confirm(mensaje)
      if(response==true){
        return true
      }else{
        return false
      }
    }

        function showMessage(mensaje) {
            alert(mensaje);
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
                    default => "un problema inesperado.",
                };
            } else if (isset($_GET['success'])) {
                $mensaje = match ($_GET['success']) {
                    "inserted" => "Valor creado correctamente.",
                    "updated" => "Valor actualizado correctamente.",
                    "deleted" => "Valor eliminado correctamente.",
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
                    <h3>Agregar un nuevo valor</h3>
                    <p class="text-muted">Complete el formulario para añadir un nuevo valor</p>
                </div>
                <div class="container d-flex justify-content-center">
                    <form method="post" action="../action/valorAction.php" style="width: 50vw; min-width:300px;">
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

                        <label for="nombre" class="form-label">Nombre: </label>
                        <?php generarCampoTexto('nombre','formCrearData','Nombre de la opción','') ?>

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
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $valores = $valorBusiness->getAllTbValor();
                    $mensajeActualizar = "¿Desea actualizar este valor?";
                    $mensajeEliminar = "¿Desea eliminar este valor?";
                    if ($valores != null) {
                        foreach ($valores as $valor) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($valor->getTbValorId()) . '</td>';
                            echo '<td><form method="post" enctype="multipart/form-data" action="../action/valorAction.php">';
                            echo '<input type="hidden" name="idValor" value="' . htmlspecialchars($valor->getTbValorId()) . '">';
                            echo '<input type="hidden" name="idCriterio" value="' . htmlspecialchars($valor->getTbCriterioId()) . '">';
                            
                            if (isset($_SESSION['formActualizarData']) && $_SESSION['formActualizarData']['idValor'] == $valor->getTbValorId()) {
                                generarCampoTexto('nombre', 'formActualizarData', '', '');
                            } else {
                                generarCampoTexto('nombre', '', '', $valor->getTbValorNombre());
                            }

                            echo '<td>';
                            echo "<button type='submit' class='btn btn-warning me-2' name='update' id='update' onclick='return actionConfirmation(\"$mensajeActualizar\")'>Actualizar</button>";
                            echo "<button type='submit' class='btn btn-danger' name='delete' id='delete' onclick='return actionConfirmation(\"$mensajeEliminar\")'>Eliminar</button>";
                            echo '</td>';
                            echo '</form>';
                            echo '</td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </div>

</body>

<footer>
</footer>

<?php 
    eliminarFormData();
?>
</html>
