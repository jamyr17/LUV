<?php
include "../action/sessionAdminAction.php";
include '../business/universidadBusiness.php';
include '../business/campusBusiness.php';
include '../business/universidadCampusRegionBusiness.php';
include '../business/universidadCampusColectivoBusiness.php';
include '../business/universidadCampusEspecializacionBusiness.php';
include '../action/functions.php';

$universidadBusiness = new UniversidadBusiness();
$campusBusiness = new CampusBusiness();
$campusRegionBusiness = new UniversidadCampusRegionBusiness();
$campusColectivoBusiness = new UniversidadCampusColectivoBusiness();
$campusEspecializacionBusiness = new UniversidadCampusEspecializacionBusiness();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus</title>
    <script async src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places&callback=initMap"></script>
    <script>
        function actionConfirmation(mensaje) {
            return confirm(mensaje);
        }

        function showMessage(mensaje) {
            alert(mensaje);
        }
        function seleccionarColectivo() {
    var nombreColectivo = document.querySelector('input[name="colectivo"]').value.trim();

    if (nombreColectivo === '') {
        alert('Por favor, ingrese un nombre para el colectivo.');
        return;
    }

    var select = document.getElementById('colectivos');
    select.disabled = false;

    var options = select.options;
    var encontrado = false;

    for (var i = 0; i < options.length; i++) {
        if (options[i].text.toLowerCase() === nombreColectivo.toLowerCase()) {
            options[i].selected = true;
            encontrado = true;
            break;
        }
    }

    if (!encontrado) {
        var nuevaOpcion = document.createElement('option');
        nuevaOpcion.text = nombreColectivo;
        nuevaOpcion.value = "new_" + nombreColectivo.toLowerCase().replace(/\s+/g, '_');
        nuevaOpcion.selected = true;
        select.appendChild(nuevaOpcion);

        var formData = new FormData();
        formData.append('colectivoadd', true);
        formData.append('nombre', nombreColectivo);

fetch('../action/campusAction.php', { 
    method: 'POST',
    body: formData
})
.then(response => {
    if (!response.ok) {
        // Si la respuesta no es "ok", lanza un error
        return response.text().then(text => { throw new Error(text) });
    }
    return response.json();
})
.then(data => {
    if (data.status === 'success') {
        nuevaOpcion.value = data.id; // Actualiza con el ID real
        alert('Se a registrado un colectivo no registrado previamente');
    } else if (data.status === 'error') {
        manejarErrores(data);
        nuevaOpcion.remove(); // Elimina la opción en caso de error
    }
})
.catch(error => {
    console.error('Error capturado:', error.message);
    alert('Hubo un problema al añadir el colectivo. Respuesta del servidor: ' + error.message);
    nuevaOpcion.remove(); // Elimina la opción en caso de error
})
.finally(() => {
    select.disabled = true; // Deshabilitar el select después de manejar la petición
});

    } else {
        select.disabled = true; // Deshabilitar si se encontró el colectivo
    }
}

        function activarYDesactivarSelectColectivos() {
            var select = document.getElementById('colectivos');
            select.disabled = false; 
            setTimeout(function() {
                select.disabled = true; 
            }, 0);
        }

        window.onload = function() {
            document.getElementById('colectivos').disabled = true; 
        };

        function toggleDeletedCampus() {
    var section = document.getElementById('table-deleted');
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
                    "numberFormat" => "ingreso de valores numéricos.",
                    "dbError" => "un problema al procesar la transacción.",
                    "exist" => "que dicho campus ya existe.",
                    default => "un problema inesperado.",
                };
            } else if (isset($_GET['success'])) {
                $mensaje = match ($_GET['success']) {
                    "inserted" => "Campus creado correctamente.",
                    "updated" => "Campus actualizado correctamente.",
                    "deleted" => "Campus eliminado correctamente.",
                    "restored"=>"Campus restaurado correctamente.",
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
                    <h3>Agregar un nuevo campus</h3>
                    <p class="text-muted">Complete el formulario para añadir un nuevo campus</p>
                </div>

                <div class="container d-flex justify-content-center">
                    <form method="post" action="../action/campusAction.php" style="width: 50vw; min-width:300px;" onsubmit="activarYDesactivarSelectColectivos()">
                        <input type="hidden" name="idCampus" value="">

                        <label for="idUniversidad">Universidad:</label>
                        <select id="idUniversidad" name="idUniversidad" class="form-control">
                            <?php
                            $universidades = $universidadBusiness->getAllTbUniversidad();
                            $valorUniversidadSeleccionado = isset($_SESSION['formCrearData']['idUniversidad']) ? $_SESSION['formCrearData']['idUniversidad'] : '';
                            if ($universidades != null) {
                                foreach ($universidades as $universidad) {
                                    $selected = ($universidad->getTbUniversidadId() == $valorUniversidadSeleccionado) ? 'selected' : '';
                                    $id = htmlspecialchars($universidad->getTbUniversidadId());
                                    $nombre = htmlspecialchars($universidad->getTbUniversidadNombre());
                                    echo '<option value="' . $id . '" ' . $selected . '>' . $nombre . '</option>';
                                }
                            }
                            ?>
                        </select><br>

                        <div class="row">
                            <div class="col">

                                <label for="nombre" class="form-label">Nombre: </label>
                                <?php generarCampoTexto('nombre','formCrearData','Campus Omar Dengo','') ?>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col">

                                <label for="direccion" class="form-label">Dirección: </label>
                                <?php generarCampoTexto('direccion','formCrearData','Ingresa el nombre de una sede...','') ?>

                                <input type="hidden" id="latitud" name="latitud"/>
                                <input type="hidden" id="longitud" name="longitud"/>
                            </div>
                        </div>

                        <label for="idRegion">Seleccione su región: </label>
                        <select name="idRegion" id="idRegion" class="form-control">
                            <?php
                            $campusRegiones = $campusRegionBusiness->getAllTbUniversidadCampusRegion();
                            $valorRegionSeleccionado = isset($_SESSION['formCrearData']['idRegion']) ? $_SESSION['formCrearData']['idRegion'] : '';
                            if ($campusRegiones != null) {
                                foreach ($campusRegiones as $campusRegion) {
                                    $selected = ($campusRegion->getTbUniversidadCampusRegionId() == $valorRegionSeleccionado) ? 'selected' : '';
                                    echo '<option value="' . htmlspecialchars($campusRegion->getTbUniversidadCampusRegionId()) . '" ' . $selected . '> ' . htmlspecialchars($campusRegion->getTbUniversidadCampusRegionNombre()) . '</option>';
                                }
                            }
                            ?>
                        </select><br>
                        

                            <label for="colectivos">Agregue los colectivos: </label>

                            <?php generarCampoTexto('colectivo', 'formCrearData', 'Volleyball', '') ?>
                            
                            <button type="button" onclick="seleccionarColectivo()">Agregar Colectivo</button>

                            <select name="colectivos[]" id="colectivos" multiple class="form-control" disabled>
                                <?php
                                $campusColectivos = $campusColectivoBusiness->getAllTbUniversidadCampusColectivo();
                                $valoresSeleccionados = isset($_SESSION['formCrearData']['colectivos']) ? $_SESSION['formCrearData']['colectivos'] : [];
                                if ($campusColectivos != null ) {
                                    foreach ($campusColectivos as $campusColectivo) {
                                        if ($campusColectivo->getTbUniversidadCampusColectivoEstado() == 1) {
                                            $selected = in_array($campusColectivo->getTbUniversidadCampusColectivoId(), $valoresSeleccionados) ? 'selected' : '';
                                            echo '<option value="' . htmlspecialchars($campusColectivo->getTbUniversidadCampusColectivoId()) . '" ' . $selected . '>' . htmlspecialchars($campusColectivo->getTbUniversidadCampusColectivoNombre()) . '</option>';
                                        }
                                    }
                                }
                                ?>
                            </select><br>

                        <label for="idEspecializacion">Seleccione su especialización: </label>
                        <select name="idEspecializacion" id="idEspecializacion" class="form-control">
                            <?php
                            $campusEspecializaciones = $campusEspecializacionBusiness->getAllTbUniversidadCampusEspecializacion();
                            $valorEspecializacionSeleccionado = isset($_SESSION['formCrearData']['idEspecializacion']) ? $_SESSION['formCrearData']['idEspecializacion'] : '';
                            if ($campusEspecializaciones != null) {
                                foreach ($campusEspecializaciones as $campusEspecializacion) {
                                    $selected = ($campusEspecializacion->getTbUniversidadCampusEspecializacionId() == $valorEspecializacionSeleccionado) ? 'selected' : '';
                                    echo '<option value="' . htmlspecialchars($campusEspecializacion->getTbUniversidadCampusEspecializacionId()) . '" ' . $selected . '>' . htmlspecialchars($campusEspecializacion->getTbUniversidadCampusEspecializacionNombre()) . '</option>';
                                }
                            }
                            ?>
                        </select><br>
                        <div>
                            <button type="submit" class="btn btn-success" name="create" id="create">Crear</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
        
        <section id="table">
            <div class="text-center mb-4">
                <h3>Campus registrados</h3>
            </div>

            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Dirección</th>
                        <th>Región</th>
                        <th>Especialización</th>
                        <th>Colectivos</th> <!-- Nueva columna -->
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $campus = $campusBusiness->getAllTbCampus();
                    $mensajeActualizar = "¿Desea actualizar este campus?";
                    $mensajeEliminar = "¿Desea eliminar este campus?";
                    if ($campus != null) {
                        foreach ($campus as $camp) {
                            echo '<tr>';
                            echo '<form method="post" enctype="multipart/form-data" action="../action/campusAction.php">';
                            echo '<input type="hidden" name="idCampus" value="' . htmlspecialchars($camp->getTbCampusId()) . '">';
                            echo '<input type="hidden" name="idUniversidad" value="' . htmlspecialchars($camp->getTbCampusUniversidadId()) . '">';
                            echo '<td>' . htmlspecialchars($camp->getTbCampusId()) . '</td>';

                            echo '<td>';
                            if (isset($_SESSION['formActualizarData']) && $_SESSION['formActualizarData']['idCampus'] == $camp->getTbCampusId()) {
                                generarCampoTexto('nombre', 'formActualizarData', '', '');
                                echo '</td>';
                                echo '<td>';
                                generarCampoTexto('direccion', 'formActualizarData', '', '');
                                echo '</td>';
                            } else {
                                generarCampoTexto('nombre', '', '', $camp->getTbCampusNombre());
                                echo '</td>';
                                echo '<td>';
                                generarCampoTexto('direccion', '', '', $camp->getTbCampusDireccion());
                                echo '</td>';
                            }

                            // Select box para 'Región'
                            echo '<td>';
                            echo '<select name="idRegion" class="form-control">';
                            $campusRegiones = $campusRegionBusiness->getAllTbUniversidadCampusRegion();

                            // Comprobar si hay datos de sesión para el campus actual
                            if (isset($_SESSION['formActualizarData']) && $_SESSION['formActualizarData']['idCampus'] == $camp->getTbCampusId()) {
                                $idRegionSeleccionada = $_SESSION['formActualizarData']['idRegion'];
                            } else {
                                $idRegionSeleccionada = $camp->getTbCampusRegionId();
                            }

                            foreach ($campusRegiones as $campusRegion) {
                                $selected = ($campusRegion->getTbUniversidadCampusRegionId() == $idRegionSeleccionada) ? 'selected' : '';
                                echo '<option value="' . htmlspecialchars($campusRegion->getTbUniversidadCampusRegionId()) . '" ' . $selected . '>' . htmlspecialchars($campusRegion->getTbUniversidadCampusRegionNombre()) . '</option>';
                            }
                            echo '</select>';
                            echo '</td>';

                            // Select box para 'Especialización'
                            echo '<td>';
                            echo '<select name="idEspecializacion" class="form-control">';
                            $campusEspecializaciones = $campusEspecializacionBusiness->getAllTbUniversidadCampusEspecializacion();            

                            // Comprobar si hay datos de sesión para el campus actual
                            if (isset($_SESSION['formActualizarData']) && $_SESSION['formActualizarData']['idCampus'] == $camp->getTbCampusId()) {
                                $idEspecializacionSeleccionada = $_SESSION['formActualizarData']['idEspecializacion'];
                            } else {
                                $idEspecializacionSeleccionada = $camp->getTbCampusEspecializacionId();
                            }

                            foreach ($campusEspecializaciones as $campusEspecializacion) {
                                $selected = ($campusEspecializacion->getTbUniversidadCampusEspecializacionId() == $idEspecializacionSeleccionada) ? 'selected' : '';
                                echo '<option value="' . htmlspecialchars($campusEspecializacion->getTbUniversidadCampusEspecializacionId()) . '" ' . $selected . '>' . htmlspecialchars($campusEspecializacion->getTbUniversidadCampusEspecializacionNombre()) . '</option>';
                            }
                            
                            echo '</select>';
                            echo '</td>';

                            echo '<td>';
                            $campusColectivos = $campusColectivoBusiness->getColectivosByCampusId($camp->getTbCampusId());
                            $allColectivos = $campusColectivoBusiness->getAllTbUniversidadCampusColectivo();
                            
                            $colectivosSeleccionados = array_map(function($colectivo) {
                                return $colectivo->getTbUniversidadCampusColectivoId();
                            }, $campusColectivos);
                            
                            echo '<select name="colectivos[]" id="colectivos" multiple class="form-control">';
                            foreach ($allColectivos as $colectivo) {
                                $idColectivo = $colectivo->getTbUniversidadCampusColectivoId();
                                $descripcion = $colectivo->getTbUniversidadCampusColectivoDescripcion();
                                $estado = $colectivo->getTbUniversidadCampusColectivoEstado();
                                $nombreColectivo = $colectivo->getTbUniversidadCampusColectivoNombre();
                            
                                if ($estado == 1 || ($estado == 0 && in_array($idColectivo, $colectivosSeleccionados) && $descripcion == "Exclusivo")) {
                                    $selected = in_array($idColectivo, $colectivosSeleccionados) ? 'selected' : '';
                                    echo '<option value="' . htmlspecialchars($idColectivo) . '" ' . $selected . '>' . htmlspecialchars($nombreColectivo) . '</option>';
                                }
                                
                            }
                            
                            echo '</select>';
                            echo '</td>';
                            


                            // Acciones
                            echo '<td>';
                            echo '<button type="submit" name="update" onclick="return actionConfirmation(\'' . $mensajeActualizar . '\')" class="btn btn-primary">Actualizar</button>';
                            echo ' <button type="submit" name="delete" onclick="return actionConfirmation(\'' . $mensajeEliminar . '\')" class="btn btn-danger">Eliminar</button>';
                            echo '</td>';
                            
                            echo '<td><input type="hidden" name="latitud" id="latitud" value="' . htmlspecialchars($camp->getTbCampusLatitud()) . '" class="form-control" /></td>';
                            echo '<td><input type="hidden" name="longitud" id="longitud" value="' . htmlspecialchars($camp->getTbCampusLongitud()) . '" class="form-control" /></td>';

                            echo '</form>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="7" class="text-center">No hay campus registrados</td></tr>';
                    }
                    ?>
                </tbody>
            </table>


        </section>
    
    <section id="table-deleted" style="display: none;">
    <div class="text-center mb-4">
        <h3>Campus eliminados</h3>
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
          $campusEliminados = $campusBusiness->getAllDeletedTbCampus();

          if ($campusEliminados != null) {
            foreach ($campusEliminados as $campuss) {
              echo '<tr>';
              echo '<form method="post" enctype="multipart/form-data" action="../action/campusAction.php" onsubmit="return validateForm()">';
              echo '<input type="hidden" name="idCampus" value="' . htmlspecialchars($campuss->getTbCampusId()) . '">';
              echo '<td>' . htmlspecialchars($campuss->getTbCampusId()) . '</td>';
              echo '<td><input required type="text" class="form-control" name="nombre" id="nombre" value="' . $campuss->getTbCampusNombre() . '" readonly></td>';
              echo '<td><input type="submit" name="restore" id="restore" value="Restaurar" onclick="return actionConfirmation(\'¿Desea restaurar?\')"></td>';
              echo '</form>';
              echo '</tr>';
            }
          } else {
            echo '<tr>';
            echo '<td colspan="8">No hay campus eliminados</td>';
            echo '</tr>';
          }
          ?>
        </tbody>
      </table>
</section>
<button onclick="toggleDeletedCampus()" style="margin-top: 20px;">Ver/Ocultar Campus Eliminados</button>
  </div>
    </div>
</body>

<?php 
    eliminarFormData();
?>
</html>