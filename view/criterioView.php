<?php
  include "../action/sessionAdminAction.php";
  include '../action/functions.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Criterios</title>
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
      
      var maxLength = 255;

      if (nombre.length > maxLength) {
        alert("El nombre no puede tener más de " + maxLength + " caracteres.");
        return false;
      }

      return true;
    }

    function toggleDeletedCriterios() {
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
            $_GET['error']=="exist" => "que dicho criterio ya existe.",
            $_GET['error']=="nameTooLong" => "que el nombre es demasiado largo, el limite es de 255 caracteres.",
            default => "un problema inesperado.",
          };
        } else if (isset($_GET['success'])) {
            $mensaje = match(true){
              $_GET['success']=="inserted" => "Criterio creado correctamente.",
              $_GET['success']=="updated" => "Criterio actualizado correctamente.",
              $_GET['success']=="deleted" => "Criterio eliminado correctamente.",
              $_GET['success']=="restored" => "Criterio restaurado correctamente.",
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
            <h3>Agregar un nuevo criterio</h3>
            <p class="text-muted">Complete el formulario para añadir un nuevo criterio</p>
        </div>

        <div class="container d-flex justify-content-center">
            <form method="post" action="../action/criterioAction.php" style="width: 50vw; min-width:300px;" onsubmit="return validateForm()">
                <input type="hidden" name="criterio" value="<?php echo htmlspecialchars($idCriterio); ?>">

                <div class="row">
                    <div class="col">

                        <label for="nombre" class="form-label">Nombre: </label>
                        <?php generarCampoTexto('nombre','formCrearData','Nombre del criterio','') ?>

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
        <h3>Criterios registrados</h3>
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
          include '../business/criterioBusiness.php';
          $criterioBusiness = new CriterioBusiness();
          $criterios = $criterioBusiness->getAllTbCriterio();
          $mensajeActualizar = "¿Desea actualizar este criterio?";
          $mensajeEliminar = "¿Desea eliminar este criterio?";

          if ($criterios != null) {
            foreach ($criterios as $criterio) {
              echo '<tr>';
              echo '<form method="post" enctype="multipart/form-data" action="../action/criterioAction.php" onsubmit="return validateForm()">';
              echo '<input type="hidden" name="idCriterio" value="' . htmlspecialchars($criterio->getTbCriterioId()) . '">';
              echo '<td>' . htmlspecialchars($criterio->getTbCriterioId()) . '</td>';
 
              echo '<td>';
              if (isset($_SESSION['formActualizarData']) && $_SESSION['formActualizarData']['idCriterio'] == $criterio->getTbCriterioId()) {
                  generarCampoTexto('nombre', 'formActualizarData', '', '');
              } else {
                  generarCampoTexto('nombre', '', '', $criterio->getTbCriterioNombre());
              }
              echo '</td>';

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
    </section>\

    <section id="table-deleted" style="display: none;">
      <div class="text-center mb-4">
        <h3>Criterios eliminados</h3>
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
          $criteriosEliminados = $criterioBusiness->getAllDeletedTbCriterio();

          if ($criteriosEliminados != null) {
            foreach ($criteriosEliminados as $criterios) {
              echo '<tr>';
              echo '<form method="post" enctype="multipart/form-data" action="../action/criterioAction.php" onsubmit="return validateForm()">';
              echo '<input type="hidden" name="idCriterio" value="' . htmlspecialchars($criterios->getTbCriterioId()) . '">';
              echo '<td>' . htmlspecialchars($criterios->getTbCriterioId()) . '</td>';
              echo '<td><input required type="text" class="form-control" name="nombre" id="nombre" value="' . $criterios->getTbCriterioNombre() . '" readonly></td>';
              echo '<td><input type="submit" name="restore" id="restore" value="Restaurar" onclick="return actionConfirmation(\'¿Desea restaurar?\')"></td>';
              echo '</form>';
              echo '</tr>';
            }
          } else {
            echo '<tr>';
            echo '<td colspan="8">No hay criterios eliminados</td>';
            echo '</tr>';
          }
          ?>
        </tbody>
      </table>
    </section>

     <button onclick="toggleDeletedCriterios()" style="margin-top: 20px;">Ver/Ocultar Criterios Eliminados</button>

  </div>

</body>

</html>
