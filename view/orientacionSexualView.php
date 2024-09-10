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
  <title>Orientacion Sexual</title>
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

    function toggleDeletedOrientacionSexual() {
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
          $_GET['error'] == "exist" => "dicha orientación sexual ya existe.",
          $_GET['error'] == "alike" => "que el nombre es muy similar.",
          $_GET['error'] == "nameTooLong" => "que el nombre es demasiado largo, el limite es de 255 caracteres.",
          $_GET['error'] == "descriptionTooLong" => "que la descripción es demasiado larga, el limite es de 255 caracteres.",
          default => "un problema inesperado.",
        };
      } else if (isset($_GET['success'])) {
        $mensaje = match (true) {
          $_GET['success'] == "inserted" => "Orientación sexual creada correctamente.",
          $_GET['success'] == "updated" => "Orientación sexual actualizada correctamente.",
          $_GET['success'] == "deleted" => "Orientación sexual eliminada correctamente.",
          $_GET['success'] == "restored" => "Orientación sexual restaurada correctamente.",
          default => "Transacción realizada.",
        };
      }

      if (isset($mensaje)) {
        echo "<script>showMessage('$mensaje')</script>";
      }
      ?>
    </section>
    <section id="form">
      <div class="containter">

        <button onclick="window.location.href='../indexView.php';">Volver</button>
        <form method="post" action="../action/sessionAdminAction.php">
          <button type="submit" class="btn btn-success" name="logout" id="logout">Cerrar sesión</button>
        </form>

        <div class="text-center mb-4">
          <h3>Agregar una nueva orientación sexual</h3>
          <p class="text-muted">Complete el formulario para añadir una nueva orientación sexual</p>
        </div>

        <div class="container d-flex justify-content-center">
          <form method="post" action="../action/orientacionSexualAction.php" style="width: 50vw; min-width:300px;" onsubmit="return validateForm()">
            <input type="hidden" name="idOrientacionSexual" value="<?php echo htmlspecialchars($tbOrientacionSexualId); ?>">

            <div class="row">
              <div class="col">
                <input type="hidden" id="type" name="type" value="orientacionSexual"> <!-- Campo oculto para el tipo de objeto -->

                <label for="nombre" class="form-label">Nombre: </label>
                <?php generarCampoTexto('nombre', 'formCrearData', 'Orientación Sexual', '') ?>

              </div>
              <div class="col">

                <label for="descripcion" class="form-label">Descripción: </label>
                <?php generarCampoTexto('descripcion', 'formCrearData', 'Descripción de la orientación sexual', '') ?>

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
        <h3>Orientaciones sexuales registradas</h3>
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
          include '../business/orientacionSexualBusiness.php';
          $orientacionSexualBusiness = new OrientacionSexualBusiness();
          $orientacionesSexuales = $orientacionSexualBusiness->getAllTbOrientacionSexual();
          $mensajeActualizar = "¿Desea actualizar esta orientación sexual?";
          $mensajeEliminar = "¿Desea eliminar esta orientación sexual?";

          if ($orientacionesSexuales != null) {
            foreach ($orientacionesSexuales as $orientacionSexual) {
              echo '<tr>';
              echo '<form method="post" enctype="multipart/form-data" action="../action/orientacionSexualAction.php" onsubmit="return validateForm()">';
              echo '<input type="hidden" name="idOrientacionSexual" value="' . htmlspecialchars($orientacionSexual->getTbOrientacionSexualId()) . '">';
              echo '<td>' . htmlspecialchars($orientacionSexual->getTbOrientacionSexualId()) . '</td>';

              echo '<td>';
              if (isset($_SESSION['formActualizarData']) && $_SESSION['formActualizarData']['idOrientacionSexual'] == $orientacionSexual->getTbOrientacionSexualId()) {
                generarCampoTexto('nombre', 'formActualizarData', '', '');
                echo '</td>';
                echo '<td>';
                generarCampoTexto('descripcion', 'formActualizarData', '', '');
                echo '</td>';
              } else {
                generarCampoTexto('nombre', '', '', $orientacionSexual->getTbOrientacionSexualNombre());
                echo '</td>';
                echo '<td>';
                generarCampoTexto('descripcion', '', '', $orientacionSexual->getTbOrientacionSexualDescripcion());
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
        <h3>Orientaciones sexuales eliminadas</h3>
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
          $orientacionesSexualEliminados = $orientacionSexualBusiness->getAllDeletedTbOrientacionSexual();

          if ($orientacionesSexualEliminados != null) {
            foreach ($orientacionesSexualEliminados as $orientacionesSexuales) {
              echo '<tr>';
              echo '<form method="post" enctype="multipart/form-data" action="../action/orientacionSexualAction.php" onsubmit="return validateForm()">';
              echo '<input type="hidden" name="idOrientacionSexual" value="' . htmlspecialchars($orientacionesSexuales->getTbOrientacionSexualId()) . '">';
              echo '<td>' . htmlspecialchars($orientacionesSexuales->getTbOrientacionSexualId()) . '</td>';
              echo '<td><input required type="text" class="form-control" name="nombre" id="nombre" value="' . $orientacionesSexuales->getTbOrientacionSexualNombre() . '" readonly></td>';
              echo '<td><input type="submit" name="restore" id="restore" value="Restaurar" onclick="return actionConfirmation(\'¿Desea restaurar?\')"></td>';
              echo '</form>';
              echo '</tr>';
            }
          } else {
            echo '<tr>';
            echo '<td colspan="8">No hay orientaciones sexuales eliminadas</td>';
            echo '</tr>';
          }
          ?>
        </tbody>
      </table>
    </section>

    <button onclick="toggleDeletedOrientacionSexual()" style="margin-top: 20px;">Ver/Ocultar Orientaciones Sexuales Eliminadas</button>

  </div>

</body>

</html>