<?php
  include_once "../action/sessionAdminAction.php";
  include_once '../action/functions.php';
  include_once '../business/actividadBusiness.php';
  include_once '../business/universidadCampusColectivoBusiness.php';

  $campusColectivoBusiness = new UniversidadCampusColectivoBusiness();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>LUV | Actividades</title>
    <script>
        function actionConfirmation(mensaje, idCriterio) {
            if (confirm(mensaje)) {
                return true;
            }
            else{
                return false;
            }
        }

        function actionConfirmationRestore(mensaje) {
            return confirm(mensaje);
        }

        function showMessage(mensaje) {
            alert(mensaje);
        }

        function validateForm() {
            var titulo = document.getElementById("titulo").value;
            var descripcion = document.getElementById("descripcion").value;
            var direccion = document.getElementById("direccion").value;

            if (titulo.length > 63) {
                alert("El titulo no puede tener más de " + 63 + " caracteres.");
                return false;
            }

            if (descripcion.length > 255) {
                alert("La descripción no puede tener más de " + 255 + " caracteres.");
                return false;
            }

            if (direccion.length > 255) {
                alert("La direccion no puede tener más de " + 255 + " caracteres.");
                return false;
            }

            return true;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const dateInput = document.getElementById('fechaInput');
            const valueDate = dateInput.value;

            const today = new Date();
            const formattedToday = today.toISOString().split('T')[0]; 
            dateInput.value = formattedToday;

            const minDate = formattedToday; 
            const maxDate = new Date();
            maxDate.setFullYear(today.getFullYear() +50);
            const formattedMaxDate = maxDate.toISOString().split('T')[0]; 

            dateInput.min = minDate; 
            dateInput.max = formattedMaxDate; 

            if(valueDate!=null){
                dateInput.value = valueDate;
            }

            function updateDisplayedDate() {
                const selectedDate = new Date(dateInput.value);
                const day = String(selectedDate.getDate()).padStart(2, '0');
                const month = String(selectedDate.getMonth() + 1).padStart(2, '0'); 
                const year = selectedDate.getFullYear();
                fechaMostrar.textContent = `${day}/${month}/${year}`;
            }

            updateDisplayedDate();

            dateInput.addEventListener('input', updateDisplayedDate);
        });

    </script>
    
</head>

<body>
    <div id="actions">
        <button onclick="window.location.href='../index.php';">Volver</button>

        <form method="post" action="../action/sessionAdminAction.php">
          <button type="submit" class="btn btn-success" name="logout" id="logout">Cerrar sesión</button>
        </form>
    </div>

    <div id="alerts">
        <?php
        if (isset($_GET['error'])) {
            $mensaje = "Ocurrió un error debido a ";
            $mensaje .= match (true) {
              $_GET['error'] == "emptyField" => "campo(s) vacío(s).",
              $_GET['error'] == "numberFormat" => "ingreso de valores numéricos.",
              $_GET['error'] == "dbError" => "un problema al procesar la transacción.",
              $_GET['error'] == "exist" => "que dicha actividad ya existe.",
              $_GET['error'] == "alike" => "que el título es muy similar a uno ya existente.",
              $_GET['error'] == "nameTooLong" => "que el título es demasiado largo, el límite es de 63 caracteres.",
              $_GET['error'] == "descriptionTooLong" => "que la descripción es demasiado larga, el límite es de 255 caracteres.",
              $_GET['error'] == "directionTooLong" => "que la dirección es demasiado larga, el límite es de 255 caracteres.",
              default => "un problema inesperado.",
            };
          } else if (isset($_GET['success'])) {
            $mensaje = match (true) {
              $_GET['success'] == "inserted" => "Actividad creada correctamente.",
              $_GET['success'] == "updated" => "Actividad actualizada correctamente.",
              $_GET['success'] == "deleted" => "Actividad eliminada correctamente.",
              $_GET['success'] == "restored" => "Actividad restaurada correctamente.",
              default => "Transacción realizada.",
            };
          }
    
          if (isset($mensaje)) {
            echo "<script>showMessage('$mensaje')</script>";
          }
        ?>
    </div>

    <div id="form">
        <h3>Agregar una nueva actividad</h3>
        <p class="text-muted">Complete el formulario para añadir una nueva actividad</p>

        <form method="post" action="../action/actividadAction.php" style="width: 50vw; min-width:300px;" onsubmit="return validateForm()">
            
            <label for="nombre" class="form-label">Título: </label>
            <?php generarCampoTexto('titulo', 'formCrearData', 'Nombre de la actividad', '') ?><br>

            <label for="descripcion" class="form-label">Descripción: </label>
            <?php generarCampoTexto('descripcion', 'formCrearData', 'Descripción de la actividad', '') ?><br>

            <label for="fechaInput" class="form-label">Fecha y hora: </label>
            <?php 
            $fechaValue = isset($_SESSION['formCrearData']['fechaInput']) ? htmlspecialchars($_SESSION['formCrearData']['fechaInput']) : '';
            ?>

            <input required type="datetime-local" name="fechaInput" id="fechaInput" value="<?php echo $fechaValue; ?>" />
            <br>

            <label for="duracion" class="form-label">Duración (en minutos): </label>
            <?php 
            $duracionValue = isset($_SESSION['formCrearData']['duracion']) ? htmlspecialchars($_SESSION['formCrearData']['duracion']) : '';
            ?>

            <input required type="number" name="duracion" id="duracion" max="999" value="<?php echo $duracionValue; ?>" />
            <br>

            <label for="direccion" class="form-label">Dirección: </label>
            <?php generarCampoTexto('direccion', 'formCrearData', 'Dirección de la actividad', '') ?><br>

            <label for="anonimo">Anónimo</label>
            <input type="radio" name="anonimo" value="true" /><br>

            <label for="colectivos">Colectivos:</label>
            <select name="colectivos[]" id="colectivos" multiple>
                <?php
                $campusColectivos = $campusColectivoBusiness->getAllTbUniversidadCampusColectivo();
                var_dump($campusColectivos);
                $valoresSeleccionados = isset($_SESSION['formCrearData']['colectivos']) ? $_SESSION['formCrearData']['colectivos'] : [];
                if (!empty($campusColectivos)) {
                    foreach ($campusColectivos as $campusColectivo) {
                        if ($campusColectivo->getTbUniversidadCampusColectivoEstado() == 1) {
                            $selected = in_array($campusColectivo->getTbUniversidadCampusColectivoId(), $valoresSeleccionados) ? 'selected' : '';
                            echo '<option value="' . htmlspecialchars($campusColectivo->getTbUniversidadCampusColectivoId()) . '" ' . $selected . '>' . htmlspecialchars($campusColectivo->getTbUniversidadCampusColectivoNombre()) . '</option>';
                        }
                    }
                }
                ?>
            </select><br>

        <button type="submit" class="btn btn-success" name="create" id="create">Crear</button>
        </form>
    </div>

    <div id="table">
        <h3>Actividades registradas</h3>

        <table class="table mt-3">
            <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Descripción</th>
                <th>Fecha y hora</th>
                <th>Duración (en min)</th>
                <th>Dirección</th>
                <th>Anónimo</th>
                <th>Colectivos</th>
                <th>Acciones</th>
            </tr>
            </thead>

        <tbody id="table-content">
            <?php
            $actividadBusiness = new ActividadBusiness();
            $actividades = $actividadBusiness->getTbActividad();

            if ($actividades != null) {
                foreach ($actividades as $actividad) {
                echo '<tr>';
                echo '<form method="post" enctype="multipart/form-data" action="../action/actividadAction.php" onsubmit="return validateForm()">';
                echo '<input type="hidden" name="idActividad" value="' . htmlspecialchars($actividad->getTbActividadId()) . '">';
                echo '<td>' . htmlspecialchars($actividad->getTbActividadId()) . '</td>';
                echo '<td><input type="text" class="form-control" name="titulo" id="titulo" value="' . $actividad->getTbActividadTitulo() . '"></td>';
                echo '<td><input type="text" class="form-control" name="descripcion" id="descripcion" value="' . $actividad->getTbActividadDescripcion() . '"></td>';
                echo '<td><input type="datetime-local" class="form-control" name="fecha" id="fecha" value="' . $actividad->getTbActividadFecha() . '"></td>';
                echo '<td><input type="number" class="form-control" name="duracion" id="duracion" value="' . $actividad->getTbActividadDuracion() . '"></td>';
                echo '<td><input type="text" class="form-control" name="direccion" id="direccion" value="' . $actividad->getTbActividadDireccion() . '"></td>';
                echo ($actividad->getTbActividadAnonimo()==1) ? 
                    '<td><input type="radio" class="form-control" name="anonimo" id="anonimo" checked "></td>' :
                    '<td><input type="radio" class="form-control" name="anonimo" id="anonimo" "></td>';

                echo '<td>';
                $campusColectivos = $campusColectivoBusiness->getColectivosByActividadId($actividad->getTbActividadId());
                $allColectivos = $campusColectivoBusiness->getAllTbUniversidadCampusColectivo();

                $colectivosSeleccionados = array_map(function ($colectivo) {
                    return $colectivo->getTbUniversidadCampusColectivoId();
                }, $campusColectivos);

                echo '<select name="colectivos[]" id="colectivos" multiple class="form-control">';
                foreach ($allColectivos as $colectivo) {
                    $idColectivo = $colectivo->getTbUniversidadCampusColectivoId();
                    $descripcion = $colectivo->getTbUniversidadCampusColectivoDescripcion();
                    $estado = $colectivo->getTbUniversidadCampusColectivoEstado();
                    $nombreColectivo = $colectivo->getTbUniversidadCampusColectivoNombre();

                    if ($estado == 1 || ($estado == 0 && in_array($idColectivo, $colectivosSeleccionados) && $descripcion == "1")) {
                        $selected = in_array($idColectivo, $colectivosSeleccionados) ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars($idColectivo) . '" ' . $selected . '>' . htmlspecialchars($nombreColectivo) . '</option>';
                    }
                }

                echo '</select>';
                echo '</td>';

                echo '<td><input type="submit" name="update" id="update" value="Actualizar"></td>';
                echo '<td><button type="submit" name="delete" id="delete" onclick="actionConfirmation( \'¿Desea eliminar esta Actividad?\', ' . htmlspecialchars($actividad->getTbActividadId()) . ')">Eliminar</button></td>';
                echo '</form>';
                echo '</tr>';
                }
            } else {
                echo '<tr>';
                echo '<td colspan="8">No hay Actividades registradas</td>';
                echo '</tr>';
            }
            ?>
        </tbody>
      </table>  
    </div>

</body>

<?php
eliminarFormData();
?>

</html>