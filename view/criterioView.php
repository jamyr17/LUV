<?php
include_once "../action/sessionAdminAction.php";
include_once '../action/functions.php';
include_once '../business/criterioBusiness.php';
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

  <title>Criterios</title>
  <script>
    function actionConfirmation(mensaje, idCriterio) {
      if (confirm(mensaje)) {
        confirmDelete(idCriterio);
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
      if (nombre.length > 255) {
        alert("El nombre no puede exceder los 255 caracteres.");
        return false;
      }
      return true;
    }

    function toggleDeletedCriterios() {
      var section = document.getElementById("table-deleted");
      section.style.display = (section.style.display === "none") ? "block" : "none";
    }

    function confirmDelete(idCriterio) {
      var xhr = new XMLHttpRequest();
      xhr.open("POST", "../action/criterioAction.php", true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

      xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
          try {
              var response = JSON.parse(xhr.responseText); 
              
              if (response.status === 'confirm') {
                  var confirmed = confirm(response.message);
                  if (confirmed) {
                      deleteCriterio(idCriterio);
                  }
              } 
              else if (response.status === 'proceed') {
                  deleteCriterio(idCriterio);
              } 
              else if (response.status === 'success') {
                  window.location.href = "criterioView.php?success=deleted";
              } 
              else {
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

      xhr.send("idCriterio=" + idCriterio + "&action=delete");
    }

    function deleteCriterio(idCriterio) {
      var xhr = new XMLHttpRequest();
      xhr.open("POST", "../action/criterioAction.php", true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

      xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
          try {
            var response = JSON.parse(xhr.responseText);
            if (response.status === 'success') {
              window.location.href = "criterioView.php?success=deleted";
            } else {
              alert(response.message);
            }
          } catch (e) {
            console.error("Error al procesar el JSON: ", e);
            console.log("Respuesta del servidor: ", xhr.responseText);
          }
        }
      };

      xhr.send("idCriterio=" + idCriterio + "&action=deleteConfirmed");
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
          $_GET['error'] == "exist" => "que dicho criterio ya existe.",
          $_GET['error'] == "alike" => "que el nombre es muy similar.",
          $_GET['error'] == "nameTooLong" => "que el nombre es demasiado largo.",
          default => "un problema inesperado.",
        };
      } else if (isset($_GET['success'])) {
        $mensaje = match (true) {
          $_GET['success'] == "inserted" => "Criterio creado correctamente.",
          $_GET['success'] == "updated" => "Criterio actualizado correctamente.",
          $_GET['success'] == "deleted" => "Criterio eliminado correctamente.",
          $_GET['success'] == "restored" => "Criterio restaurado correctamente.",
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
        <button onclick="window.location.href='../index.php';">Volver</button>
        <form method="post" action="../action/sessionAdminAction.php">
          <button type="submit" class="btn btn-success" name="logout" id="logout">Cerrar sesión</button>
        </form>

        <div class="text-center mb-4">
          <h3>Agregar un nuevo criterio</h3>
          <p class="text-muted">Complete el formulario para añadir un nuevo criterio</p>
        </div>

        <div class="container d-flex justify-content-center">
          <form method="post" action="../action/criterioAction.php" style="width: 50vw; min-width:300px;" onsubmit="return validateForm()">
            <input type="hidden" name="criterio" value="<?php echo htmlspecialchars($idCriterio); ?>">
            <div class="row">
              <div class="col">
                <label for="nombre" class="form-label">Nombre: </label>
                <?php generarCampoTexto('nombre', 'formCrearData', 'Nombre del criterio', ''); ?>
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
        <h3>Criterios registrados</h3>
      </div>

      <table class="table mt-3">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody id="table-content">
          <?php
          $criterioBusiness = new CriterioBusiness();
          $criterios = $criterioBusiness->getAllTbCriterio();

          if ($criterios != null) {
            foreach ($criterios as $criterio) {
              echo '<tr>';
              echo '<form method="post" enctype="multipart/form-data" action="../action/criterioAction.php" onsubmit="return validateForm()">';
              echo '<input type="hidden" name="idCriterio" value="' . htmlspecialchars($criterio->getTbCriterioId()) . '">';
              echo '<td>' . htmlspecialchars($criterio->getTbCriterioId()) . '</td>';
              echo '<td><input required type="text" class="form-control" name="nombre" id="nombre" value="' . $criterio->getTbCriterioNombre() . '"></td>';
              echo '<td><input type="submit" name="update" id="update" value="Actualizar"></td>';
              echo '<td><button type="button" name="delete" id="delete" onclick="actionConfirmation( \'¿Desea eliminar este criterio?\', ' . htmlspecialchars($criterio->getTbCriterioId()) . ')">Eliminar</button></td>';
              echo '</form>';
              echo '</tr>';
            }
          } else {
            echo '<tr>';
            echo '<td colspan="8">No hay criterios registrados</td>';
            echo '</tr>';
          }
          ?>
        </tbody>
      </table>
    </section>

    <section id="table-deleted" style="display: none;">
      <div class="text-center mb-4">
        <h3>Criterios eliminados</h3>
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
          $criteriosEliminados = $criterioBusiness->getAllDeletedTbCriterio();

          if ($criteriosEliminados != null) {
            foreach ($criteriosEliminados as $criterios) {
              echo '<tr>';
              echo '<form method="post" enctype="multipart/form-data" action="../action/criterioAction.php" onsubmit="return validateForm()">';
              echo '<input type="hidden" name="idCriterio" value="' . htmlspecialchars($criterios->getTbCriterioId()) . '">';
              echo '<td>' . htmlspecialchars($criterios->getTbCriterioId()) . '</td>';
              echo '<td><input required type="text" class="form-control" name="nombre" id="nombre" value="' . $criterios->getTbCriterioNombre() . '" readonly></td>';
              echo '<td><input type="submit" name="restore" id="restore" value="Restaurar" onclick="return actionConfirmationRestore(\'¿Desea restaurar?\')"></td>';
              echo '</form>';
              echo '</tr>';
            }
          } else {
            echo '<tr>';
            echo '<td colspan="8">No hay criterios eliminados</td>';
            echo '</tr>';
          }
          ?>
        </tbody>
      </table>
    </section>

    <button onclick="toggleDeletedCriterios()" style="margin-top: 20px;">Ver/Ocultar Criterios Eliminados</button>

  </div>

</body>

</html>
