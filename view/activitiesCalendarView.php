<?php
  include_once "../action/sessionUserAction.php";
  include_once '../action/functions.php';
  include_once '../business/universidadCampusColectivoBusiness.php';
  $campusColectivoBusiness = new UniversidadCampusColectivoBusiness();
?>

<!DOCTYPE html>
<html lang='en'>
  <head>
    <meta charset='utf-8' />
    <title>LUV | Actividades</title>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src='../js/activitiesCalendar.js'></script>
    <script src='../js/activitiesValidations.js'></script>
  </head>

  <body>
    <div id="actions">
        <button onclick="window.location.href='./userNavigateView.php';">Volver</button>

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

    <div id='calendar' style="max-width: 1000px; margin: auto"></div>

    <!-- Modal para editar actividades:-->
    <div class="modal" id="actividadActualizarModalView"  tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Actualizar Actividad</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formActualizar" method="post" action="../action/actividadAction.php" style="width: 50vw; min-width:300px;">
                    <input type="hidden" class="form-control" name="idActividad" id="idActividad">      

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

                    <label for="direccion" class="form-label">Dirección: </label>
                    <?php generarCampoTexto('direccion', 'formCrearData', 'Dirección de la actividad', '') ?><br>

                    <label for="anonimo">Anónimo</label>
                    <input type="radio" id="anonimo" name="anonimo" value="true" /><br>

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

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" name="userUpdate" id="userUpdate">Actualizar</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Salir</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    
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

</html>