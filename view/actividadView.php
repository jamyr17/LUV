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
    <script src='../js/activitiesValidations.js'></script>
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

        <form id="formCrear" method="post" action="../action/actividadAction.php" style="width: 50vw; min-width:300px;" enctype="multipart/form-data">
            
            <label for="titulo" class="form-label">Título: </label>
            <?php generarCampoTexto('titulo', 'formCrearData', 'Nombre de la actividad', '') ?><br>

            <label for="descripcion" class="form-label">Descripción: </label>
            <?php generarCampoTexto('descripcion', 'formCrearData', 'Descripción de la actividad', '') ?><br>

            <label for="fechaInicioInput" class="form-label">Fecha y hora de inicio: </label>
            <?php 
            $fechaInicioValue = isset($_SESSION['formCrearData']['fechaInicioInput']) ? htmlspecialchars($_SESSION['formCrearData']['fechaInicioInput']) : '';
            ?>

            <input required type="datetime-local" name="fechaInicioInput" id="fechaInicioInput" value="<?php echo $fechaInicioValue; ?>" />
            <br>

            <label for="fechaTerminaInput" class="form-label">Fecha y hora de final: </label>
            <?php 
            $fechaTerminaValue = isset($_SESSION['formCrearData']['fechaTerminaInput']) ? htmlspecialchars($_SESSION['formCrearData']['fechaTerminaInput']) : '';
            ?>

            <input required type="datetime-local" name="fechaTerminaInput" id="fechaTerminaInput" value="<?php echo $fechaTerminaValue; ?>" />
            <br>

            <label>Suba 1 imagen relacionada a la actividad: </label>
            <input required type='file' name='imagen' id='imagen' /><br/>

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
                <th>Imagen</th>
                <th>Actualizar imagen</th>
                <th>Fecha Inicio</th>
                <th>Fecha Termina</th>
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
                echo '<form id="formActualizar" method="post" enctype="multipart/form-data" action="../action/actividadAction.php" onsubmit="return validateForm()">';
                echo '<input type="hidden" name="idActividad" value="' . htmlspecialchars($actividad->getTbActividadId()) . '">';
                echo '<td>' . htmlspecialchars($actividad->getTbActividadId()) . '</td>';
                echo '<td><input type="text" class="form-control" name="titulo" id="titulo" value="' . $actividad->getTbActividadTitulo() . '"></td>';
                echo '<td><input type="text" class="form-control" name="descripcion" id="descripcion" value="' . $actividad->getTbActividadDescripcion() . '"></td>';
                echo '<td><img src="' . htmlspecialchars($actividad->getTbActividadImagen()) . '" alt="Imagen de ' . htmlspecialchars($actividad->getTbActividadTitulo()) . '" style="width: 80px; height: auto; object-fit: cover;"></td>';
                echo '<td><input type="file" class="form-control" name="imagen" id="imagen"></td>';
                echo '<td><input type="datetime-local" class="form-control" name="fechaInicioInput" id="fechaInicio" value="' . $actividad->getTbActividadFechaInicio() . '"></td>';
                echo '<td><input type="datetime-local" class="form-control" name="fechaTerminaInput" id="fechaTermina" value="' . $actividad->getTbActividadFechaTermina() . '"></td>';
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

    <!-- Botón para mostrar/ocultar actividades eliminadas -->
    <div class="toggle-button-container">
        <button onclick="toggleDeletedActivities()" class="btn btn-primary">Ver/Ocultar Actividades Eliminadas</button>
    </div>

    <section id="table-deleted" style="display: none;">
        <div class="section-title">
            <h3>Actividades eliminadas</h3>
        </div>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Fecha y hora</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $actividadesEliminadas = $actividadBusiness->getAllDeletedTbActividad();
                if ($actividadesEliminadas != null) {
                    foreach ($actividadesEliminadas as $actividad) {
                        echo '<tr>';
                        echo '<form method="post" action="../action/actividadAction.php">';
                        echo '<input type="hidden" name="idActividad" value="' . htmlspecialchars($actividad->getTbActividadId()) . '">';
                        echo '<td>' . htmlspecialchars($actividad->getTbActividadId()) . '</td>';
                        echo '<td>' . htmlspecialchars($actividad->getTbActividadTitulo()) . '</td>';
                        echo '<td><input type="datetime-local" class="form-control" name="fechaInicioInput" value="' . htmlspecialchars($actividad->getTbActividadFechaInicio()) . '" readonly></td>';
                        echo '<td><input type="submit" name="restore" value="Restaurar" class="btn btn-primary" onclick="return actionConfirmationRestore(\'¿Desea restaurar esta actividad?\')"></td>';
                        echo '</form>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="4">No hay actividades eliminadas</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </section>
</body>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Manejo de validaciones en formularios
        const formCrear = document.getElementById('formCrear');
        const formActualizar = document.getElementById('formActualizar');

        // Aplicar el evento 'submit' para validar el formulario de Crear
        if (formCrear) {
            formCrear.onsubmit = function() {
                return validateForm('formCrear');
            };
        }

        // Aplicar el evento 'submit' para validar el formulario de Actualizar
        if (formActualizar) {
            formActualizar.onsubmit = function() {
                return validateForm('formActualizar');
            };
        }
    })
</script>

<?php
eliminarFormData();
?>

</html>