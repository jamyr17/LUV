<?php
    session_start();
    
    if($_SESSION["tipoUsuario"]=="Usuario" || empty($_SESSION["tipoUsuario"])){
        header("location: view/login.php?error=accessDenied");
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Campus Colectivo</title>
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

        <div class="text-center mb-4">
            <h3>Agregar un nuevo campus colectivo</h3>
            <p class="text-muted">Complete el formulario para añadir un nuevo campus colectivo</p>
        </div>

        <div class="container d-flex justify-content-center">
            <form method="post" action="../bussiness/campusColectivoAction.php" style="width: 50vw; min-width:300px;">
                <input type="hidden" name="idCampusColectivo" value="<?php echo htmlspecialchars($tbCampusColectivoId); ?>">

                <div class="row">
                    <div class="col">
                        <label for="nombre" class="form-label">Nombre: </label>
                        <input required type="text" name="nombre" id="nombre" class="form-control" placeholder="Campus Colectivo" />
                    </div>
                    <div class="col">
                        <label for="descripcion" class="form-label">Descripción: </label>
                        <input required type="text" name="descripcion" id="descripcion" class="form-control" placeholder="Descripción del campus colectivo" />
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
          include '../bussiness/campusColectivoBussiness.php';
          $campusColectivoBussiness = new CampusColectivoBussiness();
          $campusColectivos = $campusColectivoBussiness->getAllTbCampusColectivo();
          $mensajeActualizar = "¿Desea actualizar este campus colectivo?";
          $mensajeEliminar = "¿Desea eliminar este campus colectivo?";

          if ($campusColectivos != null) {
            foreach ($campusColectivos as $campusColectivo) {
              echo '<tr>';
              echo '<form method="post" enctype="multipart/form-data" action="../bussiness/campusColectivoAction.php">';
              echo '<input type="hidden" name="idCampusColectivo" value="' . htmlspecialchars($campusColectivo->getTbCampusColectivoId()) . '">';
              echo '<td>' . htmlspecialchars($campusColectivo->getTbCampusColectivoId()) . '</td>';
              echo '<td><input type="text" name="nombre" id="nombre" value="' . htmlspecialchars($campusColectivo->getTbCampusColectivoNombre()) . '" class="form-control" /></td>';
              echo '<td><input type="text" name="descripcion" id="descripcion" value="' . htmlspecialchars($campusColectivo->getTbCampusColectivoDescripcion()) . '" class="form-control" /></td>';
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
