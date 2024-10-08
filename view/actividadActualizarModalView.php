<?php
  include_once '../business/universidadCampusColectivoBusiness.php';
  $campusColectivoBusiness = new UniversidadCampusColectivoBusiness();
?>

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

            <div class="modal-footer">
                <button type="submit" class="btn btn-success" name="create" id="update">Actualizar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Salir</button>
            </div>

         </form>
<!--
  <form name="formEventoUpdate" id="formEventoUpdate" action="UpdateEvento.php" class="form-horizontal" method="POST">
    <input type="hidden" class="form-control" name="idEvento" id="idEvento">
    <div class="form-group">
      <label for="evento" class="col-sm-12 control-label">Nombre del Evento</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" name="evento" id="evento" placeholder="Nombre del Evento" required/>
      </div>
    </div>
    <div class="form-group">
      <label for="fecha_inicio" class="col-sm-12 control-label">Fecha Inicio</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" name="fecha_inicio" id="fecha_inicio" placeholder="Fecha Inicio">
      </div>
    </div>
    <div class="form-group">
      <label for="fecha_fin" class="col-sm-12 control-label">Fecha Final</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" name="fecha_fin" id="fecha_fin" placeholder="Fecha Final">
      </div>
    </div>

    <div class="col-md-12 activado">
 
      <input type="radio" name="color_evento" id="orangeUpd" value="#FF5722" checked>
      <label for="orangeUpd" class="circu" style="background-color: #FF5722;"> </label>

      <input type="radio" name="color_evento" id="amberUpd" value="#FFC107">  
      <label for="amberUpd" class="circu" style="background-color: #FFC107;"> </label>

      <input type="radio" name="color_evento" id="limeUpd" value="#8BC34A">  
      <label for="limeUpd" class="circu" style="background-color: #8BC34A;"> </label>

      <input type="radio" name="color_evento" id="tealUpd" value="#009688">  
      <label for="tealUpd" class="circu" style="background-color: #009688;"> </label>

      <input type="radio" name="color_evento" id="blueUpd" value="#2196F3">  
      <label for="blueUpd" class="circu" style="background-color: #2196F3;"> </label>

      <input type="radio" name="color_evento" id="indigoUpd" value="#9c27b0">  
      <label for="indigoUpd" class="circu" style="background-color: #9c27b0;"> </label>

    </div>

    
     <div class="modal-footer">
        <button type="submit" class="btn btn-success">Guardar Cambios de mi Evento</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Salir</button>
      </div>
  </form>
      
    </div>
  </div>
-->
</div>