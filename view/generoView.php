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

    function toggleDeletedGeneros() {
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
            $_GET['error']=="nameTooLong" => "que el nombre es demasiado largo, el limite es de 255 caracteres.",
            $_GET['error']=="descriptionTooLong" => "que la descripción es demasiado larga, el limite es de 255 caracteres.",
            default => "un problema inesperado.",
          };
        } else if (isset($_GET['success'])) {
            $mensaje = match(true){
              $_GET['success']=="inserted" => "Género creado correctamente.",
              $_GET['success']=="updated" => "Género actualizado correctamente.",
              $_GET['success']=="deleted" => "Género eliminado correctamente.",
              $_GET['success']=="restored" => "Género restaurado correctamente.",
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
            <form method="post" action="../action/generoAction.php" style="width: 50vw; min-width:300px;" onsubmit="return validateForm()">
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
          include '../business/generoBusiness.php';
          $generoBusiness = new GeneroBusiness();
          $generos = $generoBusiness->getAllTbGenero();
          $mensajeActualizar = "¿Desea actualizar este género?";
          $mensajeEliminar = "¿Desea eliminar este género?";

          if ($generos != null) {
            foreach ($generos as $genero) {
              echo '<tr>';
              echo '<form method="post" enctype="multipart/form-data" action="../action/generoAction.php" onsubmit="return validateForm()">';
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

    <section id="table-deleted" style="display: none;">
      <div class="text-center mb-4">
        <h3>Géneros eliminados</h3>
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
          $generosEliminados = $generoBusiness->getAllDeletedTbGenero();

          if ($generosEliminados != null) {
            foreach ($generosEliminados as $generos) {
              echo '<tr>';
              echo '<form method="post" enctype="multipart/form-data" action="../action/generoAction.php" onsubmit="return validateForm()">';
              echo '<input type="hidden" name="idGenero" value="' . htmlspecialchars($generos->getTbGeneroId()) . '">';
              echo '<td>' . htmlspecialchars($generos->getTbGeneroId()) . '</td>';
              echo '<td><input required type="text" class="form-control" name="nombre" id="nombre" value="' . $generos->getTbGeneroNombre() . '" readonly></td>';
              echo '<td><input type="submit" name="restore" id="restore" value="Restaurar" onclick="return actionConfirmation(\'¿Desea restaurar?\')"></td>';
              echo '</form>';
              echo '</tr>';
            }
          } else {
            echo '<tr>';
            echo '<td colspan="8">No hay géneros eliminados</td>';
            echo '</tr>';
          }
          ?>
        </tbody>
      </table>
    </section>

     <button onclick="toggleDeletedGeneros()" style="margin-top: 20px;">Ver/Ocultar Géneros Eliminados</button>
  </div>

</body>

</html>
