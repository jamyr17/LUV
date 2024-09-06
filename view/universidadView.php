<?php
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
      return confirm(mensaje);
    }

    function showMessage(mensaje){
      alert(mensaje);
    }

    function validateForm() {
      var nombre = document.getElementById("nombre").value;
      if (nombre.length > 150) {
        alert("El texto no puede exceder los 150 caracteres.");
        return false; 
      }
      return true;
    }

    function toggleDeletedUniversities() {
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
    <nav class="navbar bg-body-tertiary"></nav>
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
            $_GET['error']=="exist" => "que dicha universidad ya existe.",
            $_GET['error']=="longText" => "el nombre supera los 150 caracteres.",
            default => "un problema inesperado.",
          };
        } else if (isset($_GET['success'])) {
            $mensaje = match(true){
              $_GET['success']=="inserted" => "Universidad creada correctamente.",
              $_GET['success']=="updated" => "Universidad actualizada correctamente.",
              $_GET['success']=="deleted" => "Universidad eliminada correctamente.",
              $_GET['success']=="restored" => "Universidad restaurada correctamente.",
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
          <h3>Agregar una nueva universidad</h3>
          <p class="text-muted">Complete el formulario para añadir una nueva universidad</p>
        </div>

        <div class="container d-flex justify-content-center">
          <form method="post" action="../action/universidadAction.php" style="width: 50vw; min-width:300px;" onsubmit="return validateForm()">
            <input type="hidden" name="universidad" value="<?php echo htmlspecialchars($idUniversidad); ?>">

            <div class="row">
              <div class="col">
                <label for="nombre" class="form-label">Nombre: </label>
                <?php generarCampoTexto('nombre', 'formCrearData', 'Universidad Nacional', ''); ?>
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
        <h3>Universidades registradas</h3>
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
          include '../business/universidadBusiness.php';
          $universidadBusiness = new UniversidadBusiness();
          $universidades = $universidadBusiness->getAllTbUniversidad();

          if ($universidades != null) {
            foreach ($universidades as $universidad) {
              echo '<tr>';
              echo '<form method="post" enctype="multipart/form-data" action="../action/universidadAction.php" onsubmit="return validateForm()">';
              echo '<input type="hidden" name="idUniversidad" value="' . htmlspecialchars($universidad->getTbUniversidadId()) . '">';
              echo '<td>' . htmlspecialchars($universidad->getTbUniversidadId()) . '</td>';
              echo '<td><input required type="text" class="form-control" name="nombre" id="nombre" value="' . $universidad->getTbUniversidadNombre() . '"></td>';
              echo '<td><input type="submit" name="update" id="update" value="Actualizar"></td>';
              echo '<td><input type="submit" name="delete" id="delete" value="Eliminar" onclick="return actionConfirmation(\'¿Desea eliminar?\')"></td>';
              echo '</form>';
              echo '</tr>';
            }
          } else {
            echo '<tr>';
            echo '<td colspan="8">No hay universidades registradas</td>';
            echo '</tr>';
          }
          ?>
        </tbody>
      </table>
    </section>

    <section id="table-deleted">
      <div class="text-center mb-4">
        <h3>Universidades eliminadas</h3>
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
          $universidadesEliminadas = $universidadBusiness->getAllDeletedTbUniversidad();

          if ($universidadesEliminadas != null) {
            foreach ($universidadesEliminadas as $universidad) {
              echo '<tr>';
              echo '<form method="post" enctype="multipart/form-data" action="../action/universidadAction.php" onsubmit="return validateForm()">';
              echo '<input type="hidden" name="idUniversidad" value="' . htmlspecialchars($universidad->getTbUniversidadId()) . '">';
              echo '<td>' . htmlspecialchars($universidad->getTbUniversidadId()) . '</td>';
              echo '<td><input required type="text" class="form-control" name="nombre" id="nombre" value="' . $universidad->getTbUniversidadNombre() . '" readonly></td>';
              echo '<td><input type="submit" name="restore" id="restore" value="Restaurar" onclick="return actionConfirmation(\'¿Desea restaurar?\')"></td>';
              echo '</form>';
              echo '</tr>';
            }
          } else {
            echo '<tr>';
            echo '<td colspan="8">No hay universidades eliminadas</td>';
            echo '</tr>';
          }
          ?>
        </tbody>
      </table>
    </section>

     <button onclick="toggleDeletedUniversities()" style="margin-top: 20px;">Ver/Ocultar Universidades Eliminadas</button>

  </div>

</body>

</html>
