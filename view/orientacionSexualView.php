<?php
  include "../action/sessionAdminAction.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Orientacion Sexual</title>
  <script>
    function actionConfirmation(mensaje){
      var response = confirm(mensaje)
      if(response==true){
        return true
      }else{
        return false
      }
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
            $_GET['error']=="numberFormat" => "ingreso de valores numéricos.",
            $_GET['error']=="dbError" => "un problema al procesar la transacción.",
            $_GET['error']=="exist" => "dicha orientación sexual ya existe.",
            default => "un problema inesperado.",
          };
        } else if (isset($_GET['success'])) {
            $mensaje = match(true){
              $_GET['success']=="inserted" => "Orientación sexual creada correctamente.",
              $_GET['success']=="updated" => "Orientación sexual actualizada correctamente.",
              $_GET['success']=="deleted" => "Orientación sexual eliminada correctamente.",
              default => "Transacción realizada.",
            };
        }

        if(isset($mensaje)){
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
            <form method="post" action="../action/orientacionSexualAction.php" style="width: 50vw; min-width:300px;">
                <input type="hidden" name="idOrientacionSexual" value="<?php echo htmlspecialchars($tbOrientacionSexualId); ?>">

                <div class="row">
                    <div class="col">
                        <label for="nombre" class="form-label">Nombre: </label>
                        <input required type="text" name="nombre" id="nombre" class="form-control" placeholder="Orientación Sexual" />
                    </div>
                    <div class="col">
                        <label for="descripcion" class="form-label">Descripción: </label>
                        <input required type="text" name="descripcion" id="descripcion" class="form-control" placeholder="Descripción de la orientación sexual" />
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
          include '../bussiness/orientacionSexualBussiness.php';
          $orientacionSexualBusiness = new OrientacionSexualBusiness();
          $orientacionesSexuales = $orientacionSexualBusiness->getAllTbOrientacionSexual();
          $mensajeActualizar = "¿Desea actualizar esta orientación sexual?";
          $mensajeEliminar = "¿Desea eliminar esta orientación sexual?";

          if ($orientacionesSexuales != null) {
            foreach ($orientacionesSexuales as $orientacionSexual) {
              echo '<tr>';
              echo '<form method="post" enctype="multipart/form-data" action="../action/orientacionSexualAction.php">';
              echo '<input type="hidden" name="idOrientacionSexual" value="' . htmlspecialchars($orientacionSexual->getTbOrientacionSexualId()) . '">';
              echo '<td>' . htmlspecialchars($orientacionSexual->getTbOrientacionSexualId()) . '</td>';
              echo '<td><input type="text" name="nombre" id="nombre" value="' . htmlspecialchars($orientacionSexual->getTbOrientacionSexualNombre()) . '" class="form-control" /></td>';
              echo '<td><input type="text" name="descripcion" id="descripcion" value="' . htmlspecialchars($orientacionSexual->getTbOrientacionSexualDescripcion()) . '" class="form-control" /></td>';
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
