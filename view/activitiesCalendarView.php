<?php
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }

  include_once "../action/sessionUserAction.php";
  include_once '../action/functions.php';
  include_once '../business/universidadCampusColectivoBusiness.php';
  include_once '../business/usuarioBusiness.php';
  $campusColectivoBusiness = new UniversidadCampusColectivoBusiness();
  $usuarioBusiness = new UsuarioBusiness();
  $_SESSION['idUsuario'] = $usuarioBusiness->getIdByName($_SESSION['nombreUsuario']);
?>

<!DOCTYPE html>
<html lang='en'>
  <head>
    <meta charset='utf-8' />
    <title>LUV | Actividades</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@3.10.2/dist/locale/es.js"></script>
    <script src='../js/activitiesCalendar.js'></script>
    <script src='../js/activitiesValidations.js'></script>
  </head>

  <body>
    <div id="actions">
        <button onclick="window.location.href='./userNavigateView.php';">Volver</button>
        <button id="add-event-button">Agregar Evento</button>
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
              $_GET['error'] == "titleTooLong" => "que el título es demasiado largo, el límite es de 63 caracteres.",
              $_GET['error'] == "descriptionTooLong" => "que la descripción es demasiado larga, el límite es de 255 caracteres.",
              $_GET['error'] == "directionTooLong" => "que la dirección es demasiado larga, el límite es de 255 caracteres.",
              $_GET['error'] == "invalidDates" => "la fecha y hora de inicio debe ser antes que la fecha y hora de fin.",
              default => "un problema inesperado.",
            };
          } else if (isset($_GET['success'])) {
            $mensaje = match (true) {
              $_GET['success'] == "inserted" => "Actividad creada correctamente.",
              $_GET['success'] == "updated" => "Actividad actualizada correctamente.",
              $_GET['success'] == "deleted" => "Actividad eliminada correctamente.",
              $_GET['success'] == "restored" => "Actividad restaurada correctamente.",
              $_GET['success'] == "registerAttendance" => "Se ha registrado su asistencia a esta actividad.",
              $_GET['success'] == "cancelAttendance" => "Se ha cancelado su asistencia a esta actividad.",
              default => "Transacción realizada.",
            };
          }
    
          if (isset($mensaje)) {
            echo "<script>showMessage('$mensaje')</script>";
          }
        ?>
    </div>

    <div id='calendar' style="max-width: 1000px; margin: auto"></div>

    <div>
          <!-- Modal para Crear Actividad -->
        <div class="modal" id="actividadCrearModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Crear Actividad</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="formCrear" method="post" action="../action/actividadAction.php" enctype="multipart/form-data">
                        <input type="hidden" name="idUsuario" value="<?php echo $_SESSION['idUsuario']; ?>">
                        
                        <label for="titulo">Título:</label>
                        <input type="text" name="titulo" required><br>
                        
                        <label for="descripcion">Descripción:</label>
                        <input type="text" name="descripcion" required><br>

                        <label for="fechaInicio">Fecha de inicio:</label>
                        <input type="datetime-local" name="fechaInicioInput" required><br>

                        <label for="fechaTermina">Fecha de fin:</label>
                        <input type="datetime-local" name="fechaTerminaInput" required><br>

                        <label>Suba 1 imagen relacionada a la actividad: </label>
                        <input required type='file' name='imagen' id='imagen' class='form-control'/><br/>

                        <label for="direccion">Dirección:</label>
                        <input type="text" name="direccion" required><br>

                        <label>Anónimo:</label>
                        <input type="radio" name="anonimo" value="true"> Sí
                        <input type="radio" name="anonimo" value="false" checked> No<br>

                        <label for="colectivos">Colectivos:</label>
                        <select name="colectivos[]" multiple>
                            <?php
                            $campusColectivos = $campusColectivoBusiness->getAllTbUniversidadCampusColectivo();
                            foreach ($campusColectivos as $campusColectivo) {
                            echo '<option value="' . htmlspecialchars($campusColectivo->getTbUniversidadCampusColectivoId()) . '">' . htmlspecialchars($campusColectivo->getTbUniversidadCampusColectivoNombre()) . '</option>';
                            }
                            
                            ?>
                        </select><br>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-success" name="createUser" value="createUser">Crear</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div>
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

                    <div id='imagenActual'>Imagen actual</div>

                    <form id="formActualizar" method="post" action="../action/actividadAction.php" style="width: 50vw; min-width:300px;" enctype="multipart/form-data">
                        <input type="hidden" class="form-control" name="idActividad" id="idActividad">      
                        <input type="hidden" id="idUsuarioLogeado" name="idUsuarioLogeado" value="<?php echo $_SESSION['idUsuario']; ?>">

                        <label for="titulo" class="form-label">Título: </label>  
                        <?php generarCampoTexto('titulo', 'formCrearData', 'Nombre de la actividad', '') ?><br> 

                        <label for="descripcion" class="form-label">Descripción: </label>
                        <?php generarCampoTexto('descripcion', 'formCrearData', 'Descripción de la actividad', '') ?><br>

                        <label>Cambie la imagen de la actividad si gusta: </label>
                        <input type='file' name='imagen' id='imagen' class='form-control'/><br/>

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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Salir</button>
                            <button type="submit" class="btn btn-success" name="userUpdate" id="userUpdate">Guardar</button>
                        </div>

                    </form>

                    <div id='listAttendanceDivOwner'> </div>

                </div>
            </div>
        </div>                   
    </div>
    
    <div>
        <!-- Modal para ver detalles y apuntarse a actividades:-->
        <div class="modal" id="verDetallesActividad"  tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detalles de Evento</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div>
                        <div id='activityTitle'></div>
                        <div id='imagenDetail'></div>
                        
                        <div>
                            <p><strong> Dirección: </strong></p>
                            <div id='activityDirection'></div>
                        </div>

                        <div>
                            <p><strong> Hora: </strong></p>
                            <div id='activityStartDate'></div>
                        </div>

                        <br/>
                        <div id='listAttendanceDivDetails'></div>
                        <br/>
                        
                    </div>
                    <form id="registerAttendance" method="post" action="../action/actividadAction.php" style="width: 50vw; min-width:300px;">
                        <input type="hidden" class="form-control" name="idActividadAttendance" id="idActividadAttendance">      
                        <input type="hidden" id="idUsuarioLogeado" name="idUsuarioLogeado" value="<?php echo $_SESSION['idUsuario']; ?>">
                        <br/>
                        <h6><strong>Importante</strong></h6>
                        <label>¿Te gustaría asistir a esta actividad?</label>
                        <input type="radio" name="attendance" value="true"> Sí

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Salir</button>
                            <button type="submit" class="btn btn-success" name="registerAttendance" id="registerAttendance">Guardar</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>           
    </div>

    <div>
        <!-- Modal para ver detalles y cancelar asistencia a actividades:-->
        <div class="modal" id="verDetallesActividadRegistered"  tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detalles de Evento</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div>
                        <div id='activityTitleRegistered'></div>
                        <div id='imagenRegistered'></div>

                        <div> 
                            <p><strong> Dirección: </strong></p>
                            <div id='activityDirectionRegistered'></div>
                        </div>

                        <div>
                            <p><strong> Hora: </strong></p>
                            <div id='activityStartDateRegistered'></div><br/>
                        </div>

                        <div id='listAttendanceDivRegistered'></div><br/>
                    </div>
                    <form id="deleteAttendance" method="post" action="../action/actividadAction.php" style="width: 50vw; min-width:300px;">
                        <input type="hidden" class="form-control" name="idActividadDelAttendance" id="idActividadDelAttendance">      
                        <input type="hidden" id="idDelUsuarioLogeado" name="idDelUsuarioLogeado" value="<?php echo $_SESSION['idUsuario']; ?>">
                        <br/>
                        <h6><strong>Importante</strong></h6>
                        <p>Usted ha indicado que asistirá a esta actividad</p>
                        <label>¿Quiere cancelar su asistencia a esta actividad?</label>
                        <input type="radio" name="cancel" value="true"> Sí

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Salir</button>
                            <button type="submit" class="btn btn-success" name="deleteAttendance" id="deleteAttendance">Guardar</button>
                        </div>

                    </form>
                </div>
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
                console.log('Formulario de actualizar enviado'); // Comprobar si se activa
                return validateForm('formActualizar');
            };
        }
    })

</script>

</html>