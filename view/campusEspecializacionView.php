<?php
    session_start();
    
    if($_SESSION["tipoUsuario"]=="Usuario" || empty($_SESSION["tipoUsuario"])){
        header("location: ./login.php?error=accessDenied");
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Especializaciones de Campus</title>
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
            $_GET['error']=="exist" => "que dicha especialización de campus ya existe.",
            default => "un problema inesperado.",
          };
        } else if (isset($_GET['success'])) {
            $mensaje = match(true){
              $_GET['success']=="inserted" => "Especialización de campus creada correctamente.",
              $_GET['success']=="updated" => "Especialización de campus actualizada correctamente.",
              $_GET['success']=="deleted" => "Especialización de campus eliminada correctamente.",
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

        <div class="text-center mb-4">
            <h3>Agregar una nueva especialización de campus</h3>
            <p class="text-muted">Complete el formulario para añadir una nueva especialización de campus</p>
        </div>

        <div class="container d-flex justify-content-center">
            <form method="post" action="../bussiness/campusEspecializacionAction.php" style="width: 50vw; min-width:300px;">
                <input type="hidden" name="campusEspecializacion" value="<?php echo htmlspecialchars($idCampusEspecializacion); ?>">

                <div class="row">
                    <div class="col">
                        <label for="nombre" class="form-label">Nombre: </label>
                        <input required type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre de la especialización" />
                    </div>
                </div>
                
                <div class="row">
                    <div class="col">
                        <label for="descripcion" class="form-label">Descripción: </label>
                        <input required type="text" name="descripcion" id="descripcion" class="form-control" placeholder="Descripción de la especialización" />
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
          include '../bussiness/campusEspecializacionBussiness.php';
          $campusEspecializacionBussiness = new CampusEspecializacionBussiness();
          $especializaciones = $campusEspecializacionBussiness->getAllTbCampusEspecializacion();
          $mensajeActualizar = "¿Desea actualizar esta especialización?";
          $mensajeEliminar = "¿Desea eliminar esta especialización?";

          if ($especializaciones != null) {
            foreach ($especializaciones as $especializacion) {
              echo '<tr>';
              echo '<form method="post" enctype="multipart/form-data" action="../bussiness/campusEspecializacionAction.php">';
              echo '<input type="hidden" name="idCampusEspecializacion" value="' . htmlspecialchars($especializacion->getTbCampusEspecializacionId()) . '">';
              echo '<td>' . htmlspecialchars($especializacion->getTbCampusEspecializacionId()) . '</td>';
              echo '<td><input type="text" name="nombre" id="nombre" value="' . htmlspecialchars($especializacion->getTbCampusEspecializacionNombre()) . '" class="form-control" /></td>';
              echo '<td><input type="text" name="descripcion" id="descripcion" value="' . htmlspecialchars($especializacion->getTbCampusEspecializacionDescripcion()) . '" class="form-control" /></td>';
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
