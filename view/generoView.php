<?php
  include "../action/sessionAdminAction.php";
  include '../action/functions.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Géneros</title>
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
            $_GET['error']=="exist" => "que dicho género ya existe.",
            default => "un problema inesperado.",
          };
        } else if (isset($_GET['success'])) {
            $mensaje = match(true){
              $_GET['success']=="inserted" => "Género creado correctamente.",
              $_GET['success']=="updated" => "Género actualizado correctamente.",
              $_GET['success']=="deleted" => "Género eliminado correctamente.",
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
            <h3>Agregar un nuevo género</h3>
            <p class="text-muted">Complete el formulario para añadir un nuevo género</p>
        </div>

        <div class="container d-flex justify-content-center">
            <form method="post" action="../action/generoAction.php" style="width: 50vw; min-width:300px;">
                <input type="hidden" name="genero" value="<?php echo htmlspecialchars($idGenero); ?>">

                <div class="row">
                    <div class="col">

                        <label for="nombre" class="form-label">Nombre: </label>
                        <?php generarCampoTexto('nombre','formCrearData','Nombre del género','') ?>

                    </div>
                </div>
                
                <div class="row">
                    <div class="col">

                        <label for="descripcion" class="form-label">Descripción: </label>
                        <?php generarCampoTexto('descripcion','formCrearData','Descripción del género','') ?>

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
        <h3>Géneros registrados</h3>
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
          include '../bussiness/generoBusiness.php';
          $generoBusiness = new GeneroBusiness();
          $generos = $generoBusiness->getAllTbGenero();
          $mensajeActualizar = "¿Desea actualizar este género?";
          $mensajeEliminar = "¿Desea eliminar este género?";

          if ($generos != null) {
            foreach ($generos as $genero) {
              echo '<tr>';
              echo '<form method="post" enctype="multipart/form-data" action="../action/generoAction.php">';
              echo '<input type="hidden" name="idGenero" value="' . htmlspecialchars($genero->getTbGeneroId()) . '">';
              echo '<td>' . htmlspecialchars($genero->getTbGeneroId()) . '</td>';

              echo '<td>';
              if (isset($_SESSION['formActualizarData']) && $_SESSION['formActualizarData']['idGenero'] == $genero->getTbGeneroId()) {
                generarCampoTexto('nombre', 'formActualizarData', '', '');
                echo '</td>';
                echo '<td>';
                generarCampoTexto('descripcion', 'formActualizarData', '', '');
                echo '</td>';
              } else {
                generarCampoTexto('nombre', '', '', $genero->getTbGeneroNombre());
                echo '</td>';
                echo '<td>';
                generarCampoTexto('descripcion', '', '', $genero->getTbGeneroDescripcion());
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
