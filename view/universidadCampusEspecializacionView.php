<?php
include_once "../action/sessionAdminAction.php";
include_once '../action/functions.php';
include_once '../business/universidadCampusEspecializacionBusiness.php';
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
  
  <title>Especializaciones de Campus</title>
  <script>
    function actionConfirmation(mensaje, idEspecializacion) {
      if (confirm(mensaje)) {
        confirmDelete(idEspecializacion);
      }
    }

    function actionConfirmationRestore(mensaje) {
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
        return false;
      }
      
      if (descripcion.length > 255) {
        alert("La descripción no puede exceder los 255 caracteres.");
        return false;
      }
      return true;
    }

    function toggleDeletedEspecializaciones() {
      var section = document.getElementById("table-deleted");
      section.style.display = (section.style.display === "none") ? "block" : "none";
    }

    function confirmDelete(idCampusEspecializacion) {
      var xhr = new XMLHttpRequest();
      xhr.open("POST", "../action/universidadCampusEspecializacionAction.php", true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

      xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
          try {
            var response = JSON.parse(xhr.responseText); 
            
            if (response.status === 'confirm') {
              var confirmed = confirm(response.message);
              if (confirmed) {
                deleteEspecializacion(idCampusEspecializacion);
              }
            } else if (response.status === 'proceed') {
              deleteEspecializacion(idCampusEspecializacion);
            } else if (response.status === 'success') {
              window.location.href = "universidadCampusEspecializacionView.php?success=deleted";
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

      xhr.send("idCampusEspecializacion=" + idCampusEspecializacion + "&action=delete");
    }

    function deleteEspecializacion(idCampusEspecializacion) {
      var xhr = new XMLHttpRequest();
      xhr.open("POST", "../action/universidadCampusEspecializacionAction.php", true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

      xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
          try {
            var response = JSON.parse(xhr.responseText);
            if (response.status === 'success') {
              window.location.href = "universidadCampusEspecializacionView.php?success=deleted";
            } else {
              alert(response.message);
            }
          } catch (e) {
            console.error("Error al procesar el JSON: ", e);
            console.log("Respuesta del servidor: ", xhr.responseText);
          }
        }
      };

      xhr.send("idCampusEspecializacion=" + idCampusEspecializacion  + "&action=deleteConfirmed");
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
          $_GET['error'] == "exist" => "que dicha especialización ya existe.",
          $_GET['error'] == "alike" => "que el nombre es muy similar.",
          $_GET['error'] == "nombreTooLong" => "El nombre excede los 255 caracteres.",
          $_GET['error'] == "descripcionTooLong" => "La descripción excede los 255 caracteres.",
          default => "un problema inesperado.",
        };
      } else if (isset($_GET['success'])) {
        $mensaje = match (true) {
          $_GET['success'] == "inserted" => "Especialización creada correctamente.",
          $_GET['success'] == "updated" => "Especialización actualizada correctamente.",
          $_GET['success'] == "deleted" => "Especialización eliminada correctamente.",
          $_GET['success'] == "restored" => "Especialización restaurada correctamente.",
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
          <h3>Agregar una nueva especialización de campus</h3>
          <p class="text-muted">Complete el formulario para añadir una nueva especialización de campus</p>
        </div>

        <div class="container d-flex justify-content-center">
          <form method="post" action="../action/universidadCampusEspecializacionAction.php" style="width: 50vw; min-width:300px;" onsubmit="return validateForm()">
            <input type="hidden" name="idEspecializacion" value="0">
            <div class="row">
              <div class="col">
                <label for="nombre" class="form-label">Nombre: </label>
                <?php generarCampoTexto('nombre', 'formCrearData', 'Nombre de la especialización', '', '255'); ?>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <label for="descripcion" class="form-label">Descripción: </label>
                <?php generarCampoTexto('descripcion', 'formCrearData', 'Descripción de la especialización', '', '255'); ?>
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
        <h3>Especializaciones registradas</h3>
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
          $especializacionBusiness = new UniversidadCampusEspecializacionBusiness();
          $especializaciones = $especializacionBusiness->getAllTbUniversidadCampusEspecializacion();

          if ($especializaciones != null) {
            foreach ($especializaciones as $especializacion) {
              echo '<tr>';
              echo '<form method="post" enctype="multipart/form-data" action="../action/universidadCampusEspecializacionAction.php" onsubmit="return validateForm()">';
              echo '<input type="hidden" name="idCampusEspecializacion" value="' . htmlspecialchars($especializacion->getTbUniversidadCampusEspecializacionId()) . '">';
              echo '<td>' . htmlspecialchars($especializacion->getTbUniversidadCampusEspecializacionId()) . '</td>';
              echo '<td><input required type="text" class="form-control" name="nombre" id="nombre" value="' . htmlspecialchars($especializacion->getTbUniversidadCampusEspecializacionNombre()) . '"></td>';
              echo '<td><input required type="text" class="form-control" name="descripcion" id="descripcion" value="' . htmlspecialchars($especializacion->getTbUniversidadCampusEspecializacionDescripcion()) . '"></td>';
              echo '<td><input type="submit" name="update" id="update" value="Actualizar"></td>';
              echo '<td><button type="button" name="delete" id="delete" onclick="actionConfirmation( \'¿Desea eliminar esta especialización?\', ' . htmlspecialchars($especializacion->getTbUniversidadCampusEspecializacionId()) . ')">Eliminar</button></td>';
              echo '</form>';
              echo '</tr>';
            }
          } else {
            echo '<tr>';
            echo '<td colspan="8">No hay especializaciones registradas</td>';
            echo '</tr>';
          }
          ?>
        </tbody>
      </table>
    </section>

    <section id="table-deleted" style="display: none;">
      <div class="text-center mb-4">
        <h3>Especializaciones eliminadas</h3>
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
          $deletedEspecializaciones = $especializacionBusiness->getAllDeletedTbUniversidadCampusEspecializacion();

          if ($deletedEspecializaciones != null) {
            foreach ($deletedEspecializaciones as $especializacion) {
              echo '<tr>';
              echo '<form method="post" enctype="multipart/form-data" action="../action/universidadCampusEspecializacionAction.php" onsubmit="return validateForm()">';
              echo '<input type="hidden" name="idCampusEspecializacion" value="' . htmlspecialchars($especializacion->getTbUniversidadCampusEspecializacionId()) . '">';
              echo '<td>' . htmlspecialchars($especializacion->getTbUniversidadCampusEspecializacionId()) . '</td>';
              echo '<td><input required type="text" class="form-control" name="nombre" id="nombre" value="' . htmlspecialchars($especializacion->getTbUniversidadCampusEspecializacionNombre()) . '" readonly></td>';
              echo '<td><input type="submit" name="restore" id="restore" value="Restaurar" onclick="return actionConfirmationRestore(\'¿Desea restaurar esta especialización?\')"></td>';
              echo '</form>';
              echo '</tr>';
            }
          } else {
            echo '<tr>';
            echo '<td colspan="8">No hay especializaciones eliminadas</td>';
            echo '</tr>';
          }
          ?>
        </tbody>
      </table>
    </section>

    <button onclick="toggleDeletedEspecializaciones()" style="margin-top: 20px;">Ver/Ocultar Especializaciones Eliminadas</button>

  </div>

</body>

</html>
