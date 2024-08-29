<?php
  include "../action/sessionAdminAction.php";
  include '../action/functions.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Universidad Campus Colectivo</title>
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
            $_GET['error']=="exist" => "dicho campus colectivo ya existe.",
            default => "un problema inesperado.",
          };
        } else if (isset($_GET['success'])) {
            $mensaje = match(true){
              $_GET['success']=="inserted" => "Campus colectivo creado correctamente.",
              $_GET['success']=="updated" => "Campus colectivo actualizado correctamente.",
              $_GET['success']=="deleted" => "Campus colectivo eliminado correctamente.",
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
        <form method="post" action="../action/sessionAdmininAction.php">
          <button type="submit" class="btn btn-success" name="logout" id="logout">Cerrar sesión</button>
        </form>

        <div class="text-center mb-4">
            <h3>Agregar un nuevo campus colectivo</h3>
            <p class="text-muted">Complete el formulario para añadir un nuevo campus colectivo</p>
        </div>

        <div class="container d-flex justify-content-center">
            <form method="post" action="../action/universidadCampusColectivoAction.php" style="width: 50vw; min-width:300px;">
                <input type="hidden" name="idUniversidadCampusColectivo" value="<?php echo htmlspecialchars($tbUniversidadCampusColectivoId); ?>">

                <div class="row">
                    <div class="col">

                      <label for="nombre" class="form-label">Nombre: </label>
                      <?php generarCampoTexto('nombre','formCrearData','Campus Colectivo','') ?>

                    </div>
                    <div class="col">

                      <label for="descripcion" class="form-label">Descripción: </label>
                      <?php generarCampoTexto('descripcion','formCrearData','Descripción del colectivo','') ?>

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
              echo '<tr>';
              echo '<form method="post" enctype="multipart/form-data" action="../action/universidadCampusColectivoAction.php">';
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
