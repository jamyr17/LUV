<?php
  session_start();
      
  if ($_SESSION["tipoUsuario"] == "Usuario" || empty($_SESSION["tipoUsuario"])) {
      header("location: ./login.php?error=accessDenied");
  }
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
            default => "un problema inesperado.",
          };
        } else if (isset($_GET['success'])) {
            $mensaje = match(true){
              $_GET['success']=="inserted" => "Área de conocimiento creada correctamente.",
              $_GET['success']=="updated" => "Área de conocimiento actualizada correctamente.",
              $_GET['success']=="deleted" => "Área de conocimiento eliminada correctamente.",
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
      
        <div class="text-center mb-4">
          <h3>Agregar una nueva área de conocimiento</h3>
          <p class="text-muted">Complete el formulario para añadir una nueva área de conocimiento</p>
        </div>

        <div class="container d-flex justify-content-center">
          <form method="post" action="../bussiness/areaConocimientoAction.php" style="width: 50vw; min-width:300px;">
            <input type="hidden" name="areaConocimiento" value="<?php echo htmlspecialchars($idAreaConocimiento ?? ''); ?>">

            <div class="row mb-3">
              <div class="col">
                <label for="nombre" class="form-label">Nombre: </label>
                <input required type="text" name="nombre" id="nombre" class="form-control" placeholder="Ciencias sociales" />
              </div>
            </div>

            <div class="row mb-3">
              <div class="col">
                <label for="descripcion" class="form-label">Descripción: </label>
                <textarea required name="descripcion" id="descripcion" class="form-control" placeholder="Descripción del área de conocimiento" rows="3"></textarea>
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
          include '../bussiness/areaConocimientoBussiness.php';
          $areaConocimientoBussiness = new AreaConocimientoBussiness();
          $areasConocimiento = $areaConocimientoBussiness->getAllTbAreaConocimiento();
          $mensajeActualizar = "¿Desea actualizar esta área de conocimiento?";
          $mensajeEliminar = "¿Desea eliminar esta área de conocimiento?";

          if ($areasConocimiento != null) {
            foreach ($areasConocimiento as $areaConocimiento) {
              echo '<tr>';
              echo '<form method="post" enctype="multipart/form-data" action="../bussiness/areaConocimientoAction.php">';
              echo '<input type="hidden" name="idAreaConocimiento" value="' . htmlspecialchars($areaConocimiento->getTbAreaConocimientoId()) . '">';
              echo '<td>' . htmlspecialchars($areaConocimiento->getTbAreaConocimientoId()) . '</td>';
              echo '<td><input type="text" name="nombre" id="nombre" value="' . htmlspecialchars($areaConocimiento->getTbAreaConocimientoNombre()) . '" class="form-control" /></td>';
              echo '<td><input type="text" name="descripcion" id="descripcion" value="' . htmlspecialchars($areaConocimiento->getTbAreaConocimientoDescripcion()) . '" class="form-control" /></td>';
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
