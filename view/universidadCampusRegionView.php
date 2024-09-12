<?php
include_once "../action/sessionAdminAction.php";
include_once '../action/functions.php';
include_once '../business/universidadCampusRegionBusiness.php';
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
  
  <title>Regiones de Campus</title>
  <script>
    function actionConfirmation(mensaje, idRegion) {
      if (confirm(mensaje)) {
        confirmDelete(idRegion);
      }
    }

    function showMessage(mensaje) {
      alert(mensaje);
    }

    function validateForm() {
      var nombre = document.getElementById("nombre").value;
      var descripcion = document.getElementById("descripcion").value;
      
      if (nombre.length > 255) {
        alert("El nombre no puede exceder los 255 caracteres.");
        return false;
      }
      
      if (descripcion.length > 255) {
        alert("La descripción no puede exceder los 255 caracteres.");
        return false;
      }
      return true;
    }

    function toggleDeletedRegions() {
      var section = document.getElementById("table-deleted");
      section.style.display = (section.style.display === "none") ? "block" : "none";
    }

    function confirmDelete(idUniversidadCampusRegion) {
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "../action/universidadCampusRegionAction.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  xhr.onreadystatechange = function() {
    if (xhr.readyState == 4 && xhr.status == 200) {
      try {
        var response = JSON.parse(xhr.responseText); 
        
        if (response.status === 'confirm') {
          var confirmed = confirm(response.message);
          if (confirmed) {
            deleteRegion(idUniversidadCampusRegion);
          }
        } else if (response.status === 'proceed') {
          deleteRegion(idUniversidadCampusRegion);
        } else if (response.status === 'success') {
          window.location.href = "universidadCampusRegionView.php?success=deleted";
        } else {
          alert(response.message);
        }
      } catch (e) {
        console.error("Error al procesar el JSON: ", e);
        console.log("Respuesta del servidor: ", xhr.responseText);
      }
    } else if (xhr.status == 404) {
      alert("Archivo no encontrado.");
    } else {
      console.error("Error en la solicitud: ", xhr.status);
    }
  };

  xhr.send("idUniversidadCampusRegion=" + idUniversidadCampusRegion + "&action=delete");
}

function deleteRegion(idUniversidadCampusRegion) {
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "../action/universidadCampusRegionAction.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  xhr.onreadystatechange = function() {
    if (xhr.readyState == 4 && xhr.status == 200) {
      try {
        var response = JSON.parse(xhr.responseText);
        if (response.status === 'success') {
          window.location.href = "universidadCampusRegionView.php?success=deleted";
        } else {
          alert(response.message);
        }
      } catch (e) {
        console.error("Error al procesar el JSON: ", e);
        console.log("Respuesta del servidor: ", xhr.responseText);
      }
    }
  };

  xhr.send("idUniversidadCampusRegion=" + idUniversidadCampusRegion + "&action=deleteConfirmed");
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
        $mensaje .= match (true) {
          $_GET['error'] == "emptyField" => "campo(s) vacío(s).",
          $_GET['error'] == "numberFormat" => "ingreso de valores numéricos.",
          $_GET['error'] == "dbError" => "un problema al procesar la transacción.",
          $_GET['error'] == "exist" => "que dicha región ya existe.",
          $_GET['error'] == "alike" => "que el nombre es muy similar.",
          $_GET['error'] == "nombreTooLong" => "El nombre excede los 255 caracteres.",
          $_GET['error'] == "descripcionTooLong" => "La descripción excede los 255 caracteres.",
          default => "un problema inesperado.",
        };
      } else if (isset($_GET['success'])) {
        $mensaje = match (true) {
          $_GET['success'] == "inserted" => "Región creada correctamente.",
          $_GET['success'] == "updated" => "Región actualizada correctamente.",
          $_GET['success'] == "deleted" => "Región eliminada correctamente.",
          $_GET['success'] == "restored" => "Región restaurada correctamente.",
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
            <input type="hidden" name="idRegion" value="0">
            <div class="row">
              <div class="col">
                <label for="nombre" class="form-label">Nombre: </label>
                <?php generarCampoTexto('nombre', 'formCrearData', 'Nombre de la región', '', '255'); ?>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <label for="descripcion" class="form-label">Descripción: </label>
                <?php generarCampoTexto('descripcion', 'formCrearData', 'Descripción de la región', '', '255'); ?>
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
          $campusRegionBusiness = new UniversidadCampusRegionBusiness();
          $campusRegions = $campusRegionBusiness->getAllTbUniversidadCampusRegion();

          if ($campusRegions != null) {
            foreach ($campusRegions as $campusRegion) {
              echo '<tr>';
              echo '<form method="post" enctype="multipart/form-data" action="../action/universidadCampusRegionAction.php" onsubmit="return validateForm()">';
              echo '<input type="hidden" name="idUniversidadCampusRegion" value="' . htmlspecialchars($campusRegion->getTbUniversidadCampusRegionId()) . '">';
              echo '<td>' . htmlspecialchars($campusRegion->getTbUniversidadCampusRegionId()) . '</td>';
              echo '<td><input required type="text" class="form-control" name="nombre" id="nombre" value="' . htmlspecialchars($campusRegion->getTbUniversidadCampusRegionNombre()) . '"></td>';
              echo '<td><input required type="text" class="form-control" name="descripcion" id="descripcion" value="' . htmlspecialchars($campusRegion->getTbUniversidadCampusRegionDescripcion()) . '"></td>';
              echo '<td><input type="submit" name="update" id="update" value="Actualizar"></td>';
              echo '<td><button type="button" name="delete" id="delete" onclick="actionConfirmation( \'¿Desea eliminar esta región?\', ' . htmlspecialchars($campusRegion->getTbUniversidadCampusRegionId()) . ')">Eliminar</button></td>';
              echo '</form>';
              echo '</tr>';
            }
          } else {
            echo '<tr>';
            echo '<td colspan="8">No hay regiones registradas</td>';
            echo '</tr>';
          }
          ?>
        </tbody>
      </table>
    </section>

    <section id="table-deleted" style="display: none;">
      <div class="text-center mb-4">
        <h3>Regiones eliminadas</h3>
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
          $deletedCampusRegions = $campusRegionBusiness->getAllDeletedTbUniversidadCampusRegion();

          if ($deletedCampusRegions != null) {
            foreach ($deletedCampusRegions as $deletedRegion) {
              echo '<tr>';
              echo '<form method="post" enctype="multipart/form-data" action="../action/universidadCampusRegionAction.php" onsubmit="return validateForm()">';
              echo '<input type="hidden" name="idUniversidadCampusRegion" value="' . htmlspecialchars($deletedRegion->getTbUniversidadCampusRegionId()) . '">';
              echo '<td>' . htmlspecialchars($deletedRegion->getTbUniversidadCampusRegionId()) . '</td>';
              echo '<td><input required type="text" class="form-control" name="nombre" id="nombre" value="' . htmlspecialchars($deletedRegion->getTbUniversidadCampusRegionNombre()) . '" readonly></td>';
              echo '<td><input type="submit" name="restore" id="restore" value="Restaurar" onclick="return actionConfirmation(\'¿Desea restaurar esta región?\')"></td>';
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

    <button onclick="toggleDeletedRegions()" style="margin-top: 20px;">Ver/Ocultar Regiones Eliminadas</button>

  </div>

</body>

</html>
