<?php
  session_start();

  include "../action/sessionAdminAction.php";
  include '../action/functions.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LUV</title>
  <script>
    function actionConfirmation(mensaje){
      var response = confirm(mensaje);
      return response;
    }

    function showMessage(mensaje){
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

    function toggleDeletedCampusAreaConocimiento() {
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
          $mensaje .= match(true){
            $_GET['error']=="emptyField" => "campo(s) vacío(s).",
            $_GET['error']=="numberFormat" => "ingreso de valores númericos.",
            $_GET['error']=="dbError" => "un problema al procesar la transacción.",
            $_GET['error']=="exist" => "que dicho nombre ya existe.",
            $_GET['error']=="nameTooLong" => "que el nombre es demasiado largo, el limite es de 255 caracteres.",
            $_GET['error']=="descriptionTooLong" => "que la descripción es demasiado larga, el limite es de 255 caracteres.",
            default => "un problema inesperado.",
          };
        } else if (isset($_GET['success'])) {
            $mensaje = match(true){
              $_GET['success']=="inserted" => "Área de conocimiento creada correctamente.",
              $_GET['success']=="updated" => "Área de conocimiento actualizada correctamente.",
              $_GET['success']=="deleted" => "Área de conocimiento eliminada correctamente.",
              $_GET['success']=="restored" => "Área de conocimiento restaurada correctamente.",
              default => "Transacción realizada.",
            };
        }

        if(isset($mensaje)){
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
          <h3>Agregar una nueva área de conocimiento</h3>
          <p class="text-muted">Complete el formulario para añadir una nueva área de conocimiento</p>
        </div>

        <div class="container d-flex justify-content-center">
          <form method="post" action="../action/areaConocimientoAction.php" style="width: 50vw; min-width:300px;" onsubmit="return validateForm()">
            <input type="hidden" name="areaConocimiento" value="<?php echo htmlspecialchars($idAreaConocimiento ?? ''); ?>">

            <div class="row mb-3">
              <div class="col">

                <label for="nombre" class="form-label">Nombre: </label>
                <?php generarCampoTexto('nombre', 'formCrearData', 'Ciencias Sociales', '') ?>

              </div>
            </div>

            <div class="row mb-3">
              <div class="col">

                <label for="descripcion" class="form-label">Descripción: </label>
                <?php generarTextarea('descripcion', 'formCrearData', 'Describa el área de conocimiento', '', 3, 30, false)?>
                
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
        <h3>Áreas de conocimiento registradas</h3>
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
          include '../business/areaConocimientoBusiness.php';
          $areaConocimientoBusiness = new AreaConocimientoBusiness();
          $areasConocimiento = $areaConocimientoBusiness->getAllTbAreaConocimiento();
          $mensajeActualizar = "¿Desea actualizar esta área de conocimiento?";
          $mensajeEliminar = "¿Desea eliminar esta área de conocimiento?";

          if ($areasConocimiento != null) {
            foreach ($areasConocimiento as $areaConocimiento) {
              echo '<tr>';
              echo '<form method="post" enctype="multipart/form-data" action="../action/areaConocimientoAction.php" onsubmit="return validateForm()">';
              echo '<input type="hidden" name="idAreaConocimiento" value="' . htmlspecialchars($areaConocimiento->getTbAreaConocimientoId()) . '">';
              echo '<td>' . htmlspecialchars($areaConocimiento->getTbAreaConocimientoId()) . '</td>';

              echo '<td>';
              // Comprobar si hay datos en la sesión y si el idAreaConocimiento coincide
              if (isset($_SESSION['formActualizarData']) && $_SESSION['formActualizarData']['idAreaConocimiento'] == $areaConocimiento->getTbAreaConocimientoId()) {
                  generarCampoTexto('nombre', 'formActualizarData', '', '');
                  echo '</td>';
                  echo '<td>';
                  generarCampoTexto('descripcion', 'formActualizarData', '', '');
                  echo '</td>';
              } else {
                  // Uso de la función para generar el campo de texto sin datos de sesión
                  generarCampoTexto('nombre', '', '', $areaConocimiento->getTbAreaConocimientoNombre());
                  echo '</td>';
                  echo '<td>';
                  generarCampoTexto('descripcion', '', '', $areaConocimiento->getTbAreaConocimientoDescripcion());
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
        <h3>Especializaciones eliminados</h3>
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
          $areaConocimientoEliminados = $areaConocimientoBusiness->getAllDeletedTbAreaConocimiento();

          if ($areaConocimientoEliminados != null) {
            foreach ($areaConocimientoEliminados as $areaConocimiento) {
              echo '<tr>';
              echo '<form method="post" enctype="multipart/form-data" action="../action/areaConocimientoAction.php" onsubmit="return validateForm()">';
              echo '<input type="hidden" name="idAreaConocimiento" value="' . htmlspecialchars($areaConocimiento->getTbAreaConocimientoId()) . '">';
              echo '<td>' . htmlspecialchars($areaConocimiento->getTbAreaConocimientoId()) . '</td>';
              echo '<td><input required type="text" class="form-control" name="nombre" id="nombre" value="' . $areaConocimiento->getTbAreaConocimientoNombre() . '" readonly></td>';
              echo '<td><input type="submit" name="restore" id="restore" value="Restaurar" onclick="return actionConfirmation(\'¿Desea restaurar?\')"></td>';
              echo '</form>';
              echo '</tr>';
            }
          } else {
            echo '<tr>';
            echo '<td colspan="8">No hay áreas de conocimiento eliminadas</td>';
            echo '</tr>';
          }
          ?>
        </tbody>
      </table>
    </section>

     <button onclick="toggleDeletedCampusAreaConocimiento()" style="margin-top: 20px;">Ver/Ocultar Áreas de conocimiento Eliminadas</button>

  </div>

</body>

</html>
