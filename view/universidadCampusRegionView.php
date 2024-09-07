<?php
  include "../action/sessionAdminAction.php";
  include '../action/functions.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Regiones de Campus</title>
  <script>
    function actionConfirmation(mensaje) {
      return confirm(mensaje);
    }

    function showMessage(mensaje) {
      alert(mensaje);
    }

    function validateForm() {
      var nombre = document.getElementById("nombre").value;
      var descripcion = document.getElementById("descripcion").value;

      if (nombre.length > 255) {
        alert("El nombre no puede exceder los 255 caracteres.");
        return false; // Evita que el formulario se envíe
      }
      
      if (descripcion.length > 255) {
        alert("La descripción no puede exceder los 255 caracteres.");
        return false; // Evita que el formulario se envíe
      }

      return true; // Permite que el formulario se envíe
    }

    function toggleDeletedCampusRegiones() {
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
    <nav class="navbar bg-body-tertiary">
      <!-- Navegación -->
    </nav>
  </header>

  <div class="container mt-3">
    <section id="alerts">
      <?php
        if (isset($_GET['error'])) {
          $mensaje = "Ocurrió un error debido a ";
          $mensaje .= match(true){
            $_GET['error']=="emptyField" => "campo(s) vacío(s).",
            $_GET['error']=="numberFormat" => "ingreso de valores numéricos.",
            $_GET['error']=="dbError" => "un problema al procesar la transacción.",
            $_GET['error']=="exist" => "que dicha región ya existe.",
            $_GET['error']=="nombreTooLong" => "El nombre excede los 255 caracteres.",
            $_GET['error']=="descripcionTooLong" => "La descripción excede los 255 caracteres.",
            default => "un problema inesperado.",
          };
        } else if (isset($_GET['success'])) {
            $mensaje = match(true){
              $_GET['success']=="inserted" => "Región creada correctamente.",
              $_GET['success']=="updated" => "Región actualizada correctamente.",
              $_GET['success']=="deleted" => "Región eliminada correctamente.",
              $_GET['success']=="restored" => "Región restaurada correctamente.",
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
            <h3>Agregar una nueva región</h3>
            <p class="text-muted">Complete el formulario para añadir una nueva región</p>
        </div>

        <div class="container d-flex justify-content-center">
            <form method="post" action="../action/universidadCampusRegionAction.php" style="width: 50vw; min-width:300px;" onsubmit="return validateForm()">
                <input type="hidden" name="idUniversidadCampusRegion" value="0">

                <div class="row">
                    <div class="col">
                      <label for="nombre" class="form-label">Nombre: </label>
                      <?php 
                        generarCampoTexto('nombre', 'formCrearData', 'Nombre de la región', '', '255');
                      ?>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col">
                    <label for="descripcion" class="form-label">Descripción: </label>
                    <?php 
                      generarCampoTexto('descripcion', 'formCrearData', 'Descripción de la región', '', '255');
                    ?>
                    </div>
                </div>
                
                <div>
                    <button type="submit" class="btn btn-success" name="create" id="create">Crear</button>
                </div>
            </form>
        </div>
      </div>
    </section>

    <section id="table">
      <div class="text-center mb-4">
        <h3>Regiones registradas</h3>
      </div>

      <table class="table mt-3">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php
          include_once '../business/universidadCampusRegionBusiness.php';
          $campusRegionBusiness = new UniversidadCampusRegionBusiness();
          $campusRegions = $campusRegionBusiness->getAllTbUniversidadCampusRegion();
          $mensajeActualizar = "¿Desea actualizar esta región?";
          $mensajeEliminar = "¿Desea eliminar esta región?";

          if ($campusRegions != null) {
            foreach ($campusRegions as $campusRegion) {
              echo '<tr>';
              echo '<form method="post" enctype="multipart/form-data" action="../action/universidadCampusRegionAction.php" onsubmit="return validateForm()">';
              echo '<input type="hidden" name="idUniversidadCampusRegion" value="' . htmlspecialchars($campusRegion->getTbUniversidadCampusRegionId()) . '">';
              echo '<td>' . htmlspecialchars($campusRegion->getTbUniversidadCampusRegionId()) . '</td>';
             
              echo '<td>';
              if (isset($_SESSION['formActualizarData']) && $_SESSION['formActualizarData']['idUniversidadCampusRegion'] == $campusRegion->getTbUniversidadCampusRegionId()) {
                generarCampoTexto('nombre', 'formActualizarData', '', '', '255');
                echo '</td>';
                echo '<td>';
                generarCampoTexto('descripcion', 'formActualizarData', '', '', '255');
                echo '</td>';
              } else {
                generarCampoTexto('nombre', '', '', $campusRegion->getTbUniversidadCampusRegionNombre(), '255');
                echo '</td>';
                echo '<td>';
                generarCampoTexto('descripcion', '', '', $campusRegion->getTbUniversidadCampusRegionDescripcion(), '255');
                echo '</td>';
              }

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
        <h3>Regiones eliminados</h3>
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
          $campusRegionesEliminados = $campusRegionBusiness->getAllDeletedTbUniversidadCampusRegion();

          if ($campusRegionesEliminados != null) {
            foreach ($campusRegionesEliminados as $regiones) {
              echo '<tr>';
              echo '<form method="post" enctype="multipart/form-data" action="../action/universidadCampusRegionAction.php" onsubmit="return validateForm()">';
              echo '<input type="hidden" name="idUniversidadCampusRegion" value="' . htmlspecialchars($regiones->getTbUniversidadCampusRegionid()) . '">';
              echo '<td>' . htmlspecialchars($regiones->getTbUniversidadCampusRegionid()) . '</td>';
              echo '<td><input required type="text" class="form-control" name="nombre" id="nombre" value="' . $regiones->getTbUniversidadCampusRegionnombre() . '" readonly></td>';
              echo '<td><input type="submit" name="restore" id="restore" value="Restaurar" onclick="return actionConfirmation(\'¿Desea restaurar?\')"></td>';
              echo '</form>';
              echo '</tr>';
            }
          } else {
            echo '<tr>';
            echo '<td colspan="8">No hay regiones eliminadas</td>';
            echo '</tr>';
          }
          ?>
        </tbody>
      </table>
    </section>

     <button onclick="toggleDeletedCampusRegiones()" style="margin-top: 20px;">Ver/Ocultar Regiones Eliminadas</button>

  </div>

</body>

<footer>
</footer>

</html>
