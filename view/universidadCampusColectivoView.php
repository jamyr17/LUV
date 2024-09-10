<?php
include "../action/sessionAdminAction.php";
include '../action/functions.php';
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
  <title>Universidad Campus Colectivo</title>
  <script>
    function actionConfirmation(mensaje) {
      var response = confirm(mensaje)
      if (response == true) {
        return true
      } else {
        return false
      }
    }

    function showMessage(mensaje) {
      alert(mensaje);
    }

    function validateForm() {
      var nombre = document.getElementById("nombre").value;
      var descripcion = document.getElementById("descripcion").value;

      var maxLength = 255;

      if (nombre.length > maxLength) {
        alert("El nombre no puede tener más de " + maxLength + " caracteres.");
        return false;
      }

      if (descripcion.length > maxLength) {
        alert("La descripción no puede tener más de " + maxLength + " caracteres.");
        return false;
      }

      return true;
    }

    function toggleDeletedCampusColectivos() {
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

    </nav>
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
          $_GET['error'] == "exist" => "dicho campus colectivo ya existe.",
          $_GET['error'] == "nameTooLong" => "que el nombre es demasiado largo, el limite es de 255 caracteres.",
          $_GET['error'] == "descriptionTooLong" => "que la descripción es demasiado larga, el limite es de 255 caracteres.",
          default => "un problema inesperado.",
        };
      } else if (isset($_GET['success'])) {
        $mensaje = match (true) {
          $_GET['success'] == "inserted" => "Campus colectivo creado correctamente.",
          $_GET['success'] == "updated" => "Campus colectivo actualizado correctamente.",
          $_GET['success'] == "deleted" => "Campus colectivo eliminado correctamente.",
          $_GET['success'] == "restored" => "Colectivo restaurado correctamente.",
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
        <form method="post" action="../action/sessionAdmininAction.php">
          <button type="submit" class="btn btn-success" name="logout" id="logout">Cerrar sesión</button>
        </form>

        <div class="text-center mb-4">
          <h3>Agregar un nuevo campus colectivo</h3>
          <p class="text-muted">Complete el formulario para añadir un nuevo campus colectivo</p>
        </div>

        <div class="container d-flex justify-content-center">
          <form method="post" action="../action/universidadCampusColectivoAction.php" style="width: 50vw; min-width:300px;" onsubmit=" return validateForm();">
            <input type="hidden" name="idUniversidadCampusColectivo" value="<?php echo htmlspecialchars($tbUniversidadCampusColectivoId); ?>">

            <div class="row">
              <div class="col">
                <input type="hidden" id="type" name="type" value="campusColectivo"> <!-- Campo oculto para el tipo de objeto -->

                <label for="nombre" class="form-label">Nombre: </label>
                <?php generarCampoTexto('nombre', 'formCrearData', 'Campus Colectivo', '') ?>

              </div>
              <div class="col">

                <label for="descripcion" class="form-label">Descripción: </label>
                <?php generarCampoTexto('descripcion', 'formCrearData', 'Descripción del colectivo', '') ?>

              </div>
            </div>

            <div class="mt-3">
              <button type="submit" class="btn btn-success" name="create" id="create">Crear</button>
            </div>
          </form>
        </div>
      </div>
    </section>

    <section id="table">

      <div class="text-center mb-4">
        <h3>Campus colectivos registrados</h3>
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
          include '../business/universidadCampusColectivoBusiness.php';
          $universidadCampusColectivoBusiness = new universidadCampusColectivoBusiness();
          $universidadCampusColectivos = $universidadCampusColectivoBusiness->getAllTbUniversidadCampusColectivo();
          $mensajeActualizar = "¿Desea actualizar este campus colectivo?";
          $mensajeEliminar = "¿Desea eliminar este campus colectivo?";

          if ($universidadCampusColectivos != null) {
            foreach ($universidadCampusColectivos as $universidadCampusColectivo) {
              // Solo mostrar los colectivos con estado 1
              if ($universidadCampusColectivo->getTbUniversidadCampusColectivoEstado() == 1) {
                echo '<tr>';
                echo '<form method="post" enctype="multipart/form-data" action="../action/universidadCampusColectivoAction.php" onsubmit="return validateForm()">';
                echo '<input type="hidden" name="idUniversidadCampusColectivo" value="' . htmlspecialchars($universidadCampusColectivo->getTbUniversidadCampusColectivoId()) . '">';
                echo '<td>' . htmlspecialchars($universidadCampusColectivo->getTbUniversidadCampusColectivoId()) . '</td>';

                echo '<td>';
                if (isset($_SESSION['formActualizarData']) && $_SESSION['formActualizarData']['idUniversidadCampusColectivo'] == $universidadCampusColectivo->getTbUniversidadCampusColectivoId()) {
                  generarCampoTexto('nombre', 'formActualizarData', '', '');
                  echo '</td>';
                  echo '<td>';
                  generarCampoTexto('descripcion', 'formActualizarData', '', '');
                  echo '</td>';
                } else {
                  generarCampoTexto('nombre', '', '', $universidadCampusColectivo->getTbUniversidadCampusColectivoNombre());
                  echo '</td>';
                  echo '<td>';
                  generarCampoTexto('descripcion', '', '', $universidadCampusColectivo->getTbUniversidadCampusColectivoDescripcion());
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
          }

          ?>

        </tbody>
      </table>
    </section>

    <section id="table-deleted" style="display: none;">
      <div class="text-center mb-4">
        <h3>Colectivos eliminados</h3>
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
          $campusColectivosEliminados = $universidadCampusColectivoBusiness->getAllDeletedTbUniversidadCampusColectivo();

          if ($campusColectivosEliminados != null) {
            foreach ($campusColectivosEliminados as $colectivos) {
              echo '<tr>';
              echo '<form method="post" enctype="multipart/form-data" action="../action/universidadCampusColectivoAction.php" onsubmit="return validateForm()">';
              echo '<input type="hidden" name="idUniversidadCampusColectivo" value="' . htmlspecialchars($colectivos->getTbUniversidadCampusColectivoId()) . '">';
              echo '<td>' . htmlspecialchars($colectivos->getTbUniversidadCampusColectivoId()) . '</td>';
              echo '<td><input required type="text" class="form-control" name="nombre" id="nombre" value="' . $colectivos->getTbUniversidadCampusColectivoNombre() . '" readonly></td>';
              echo '<td><input type="submit" name="restore" id="restore" value="Restaurar" onclick="return actionConfirmation(\'¿Desea restaurar?\')"></td>';
              echo '</form>';
              echo '</tr>';
            }
          } else {
            echo '<tr>';
            echo '<td colspan="8">No hay colectivos eliminados</td>';
            echo '</tr>';
          }
          ?>
        </tbody>
      </table>
    </section>

    <button onclick="toggleDeletedCampusColectivos()" style="margin-top: 20px;">Ver/Ocultar Colectivos Eliminados</button>

  </div>

</body>

</html>