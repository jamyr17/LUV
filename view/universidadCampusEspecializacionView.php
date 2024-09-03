<?php
  include "../action/sessionAdminAction.php";
  include '../action/functions.php';

  // Define los límites de longitud para los campos
  $maxLengthNombre = 255;
  $maxLengthDescripcion = 255;

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);

    if (strlen($nombre) > $maxLengthNombre) {
        header("Location: ../view/universidadCampusEspecializacionView.php?error=nameTooLong");
        exit();
    }

    if (strlen($descripcion) > $maxLengthDescripcion) {
        header("Location: ../view/universidadCampusEspecializacionView.php?error=descriptionTooLong");
        exit();
    }
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
      return confirm(mensaje);
    }

    function showMessage(mensaje){
      alert(mensaje);
    }

    function validarNombreEspecializacion() {
      var nombre = document.getElementById("nombre").value;
      var descripcion = document.getElementById("descripcion").value;
      
      var maxLengthNombre = 255;
      var maxLengthDescripcion = 255;

      if (nombre.length > maxLengthNombre) {
        alert("El nombre no puede tener más de " + maxLengthNombre + " caracteres.");
        return false;
      }

      if (descripcion.length > maxLengthDescripcion) {
        alert("La descripción no puede tener más de " + maxLengthDescripcion + " caracteres.");
        return false;
      }

      return true;
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
            $_GET['error']=="invalidName" => "que el nombre solo puede contener letras.",
            $_GET['error']=="nameTooLong" => "que el nombre es demasiado largo.",
            $_GET['error']=="descriptionTooLong" => "que la descripción es demasiado larga.",
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

        <button onclick="window.location.href='../indexView.php';">Volver</button>
        <form method="post" action="../action/sessionAdminAction.php">
          <button type="submit" class="btn btn-success" name="logout" id="logout">Cerrar sesión</button>
        </form>

        <div class="text-center mb-4">
            <h3>Agregar una nueva especialización de campus</h3>
            <p class="text-muted">Complete el formulario para añadir una nueva especialización de campus</p>
        </div>

        <div class="container d-flex justify-content-center">
            <form method="post" action="../action/universidadCampusEspecializacionAction.php" style="width: 50vw; min-width:300px;" onsubmit="return validarNombreEspecializacion();">
                <input type="hidden" name="universidadCampusEspecializacion" value="<?php echo htmlspecialchars($idCampusEspecializacion); ?>">

                <div class="row">
                    <div class="col">
                        <label for="nombre" class="form-label">Nombre: </label>
                        <?php generarCampoTexto('nombre','formCrearData','Nombre de la especialización','') ?>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col">
                        <label for="descripcion" class="form-label">Descripción: </label>
                        <?php generarCampoTexto('descripcion','formCrearData','Descripción de la especialización','') ?>
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
          include '../business/universidadCampusEspecializacionBusiness.php';
          $universidadCampusEspecializacionBusiness = new universidadCampusEspecializacionBusiness();
          $especializaciones = $universidadCampusEspecializacionBusiness->getAllTbUniversidadCampusEspecializacion();
          $mensajeActualizar = "¿Desea actualizar esta especialización?";
          $mensajeEliminar = "¿Desea eliminar esta especialización?";

          if ($especializaciones != null) {
            foreach ($especializaciones as $especializacion) {
              echo '<tr>';
              echo '<form method="post" enctype="multipart/form-data" action="../action/universidadCampusEspecializacionAction.php"onsubmit="return validarNombreEspecializacion()">';
              echo '<input type="hidden" name="idCampusEspecializacion" value="' . htmlspecialchars($especializacion->getTbUniversidadCampusEspecializacionId()) . '">';
              echo '<td>' . htmlspecialchars($especializacion->getTbUniversidadCampusEspecializacionId()) . '</td>';
              
              echo '<td>';
              if (isset($_SESSION['formActualizarData']) && $_SESSION['formActualizarData']['idCampusEspecializacion'] == $especializacion->getTbUniversidadCampusEspecializacionId()) {
                generarCampoTexto('nombre', 'formActualizarData', '', '');
                echo '</td>';
                echo '<td>';
                generarCampoTexto('descripcion', 'formActualizarData', '', '');
                echo '</td>';
              } else {
                generarCampoTexto('nombre', '', '', $especializacion->getTbUniversidadCampusEspecializacionNombre());
                echo '</td>';
                echo '<td>';
                generarCampoTexto('descripcion', '', '', $especializacion->getTbUniversidadCampusEspecializacionDescripcion());
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
  </div>

</body>

<footer>
</footer>

<?php 
  eliminarFormData();
?>
</html>
