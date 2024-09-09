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
                <button onclick="window.location.href='../indexView.php';">Volver</button>
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
                        <th>Criterio</th>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php
        $valores = $valorBusiness->getAllTbValor();
        $criterios = $criterioBusiness->getAllTbCriterio(); // Obtenemos todos los criterios disponibles
        $mensajeActualizar = "¿Desea actualizar este valor?";
        $mensajeEliminar = "¿Desea eliminar este valor?";

        if ($valores != null) {
            foreach ($valores as $valor) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($valor->getTbValorId()) . '</td>';

                echo '<td><form method="post" enctype="multipart/form-data" action="../action/valorAction.php" onsubmit="return validateForm()">';
                echo '<input type="hidden" name="idValor" value="' . htmlspecialchars($valor->getTbValorId()) . '">';

                // Combo box para seleccionar el criterio
                echo '<select name="idCriterio" class="form-select">';
                foreach ($criterios as $criterio) {
                    $selected = $criterio->getTbCriterioId() == $valor->getTbCriterioId() ? 'selected' : '';
                    echo '<option value="' . htmlspecialchars($criterio->getTbCriterioId()) . '" ' . $selected . '>';
                    echo htmlspecialchars($criterio->getTbCriterioNombre());
                    echo '</option>';
                }
                echo '</select>';

                echo '</td>';

                // Campo de texto para el nombre del valor
                echo '<td>';
                if (isset($_SESSION['formActualizarData']) && $_SESSION['formActualizarData']['idValor'] == $valor->getTbValorId()) {
                    generarCampoTexto('nombre', 'formActualizarData', '', '');
                } else {
                    generarCampoTexto('nombre', '', '', $valor->getTbValorNombre());
                }
                echo '</td>';

                // Botones de acción
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
