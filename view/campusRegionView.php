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
            default => "un problema inesperado.",
          };
        } else if (isset($_GET['success'])) {
            $mensaje = match(true){
              $_GET['success']=="inserted" => "Región creada correctamente.",
              $_GET['success']=="updated" => "Región actualizada correctamente.",
              $_GET['success']=="deleted" => "Región eliminada correctamente.",
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

        <div class="text-center mb-4">
            <h3>Agregar una nueva región</h3>
            <p class="text-muted">Complete el formulario para añadir una nueva región</p>
        </div>

        <div class="container d-flex justify-content-center">
            <form method="post" action="../bussiness/campusRegionAction.php" style="width: 50vw; min-width:300px;">
                <input type="hidden" name="campusRegion" value="<?php echo htmlspecialchars($idCampusRegion); ?>">

                <div class="row">
                    <div class="col">
                        <label for="nombre" class="form-label">Nombre: </label>
                        <input required type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre de la región" />
                    </div>
                </div>
                
                <div class="row">
                    <div class="col">
                        <label for="descripcion" class="form-label">Descripción: </label>
                        <input required type="text" name="descripcion" id="descripcion" class="form-control" placeholder="Descripción de la región" />
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
          include '../bussiness/campusRegionBusiness.php';
          $campusRegionBusiness = new CampusRegionBusiness();
          $campusRegions = $campusRegionBusiness->getAllTbCampusRegion();
          $mensajeActualizar = "¿Desea actualizar esta región?";
          $mensajeEliminar = "¿Desea eliminar esta región?";

          if ($campusRegions != null) {
            foreach ($campusRegions as $campusRegion) {
              echo '<tr>';
              echo '<form method="post" enctype="multipart/form-data" action="../bussiness/campusRegionAction.php">';
              echo '<input type="hidden" name="idCampusRegion" value="' . htmlspecialchars($campusRegion->getTbCampusRegionId()) . '">';
              echo '<td>' . htmlspecialchars($campusRegion->getTbCampusRegionId()) . '</td>';
              echo '<td><input type="text" name="nombre" id="nombre" value="' . htmlspecialchars($campusRegion->getTbCampusRegionNombre()) . '" class="form-control" /></td>';
              echo '<td><input type="text" name="descripcion" id="descripcion" value="' . htmlspecialchars($campusRegion->getTbCampusRegionDescripcion()) . '" class="form-control" /></td>';
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
  </div>

</body>

<footer>
</footer>

</html>
