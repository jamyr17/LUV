<?php
  include "../action/sessionAction.php";
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
            default => "un problema inesperado.",
          };
        } else if (isset($_GET['success'])) {
            $mensaje = match(true){
              $_GET['success']=="inserted" => "Criterio creado correctamente.",
              $_GET['success']=="updated" => "Criterio actualizado correctamente.",
              $_GET['success']=="deleted" => "Criterio eliminado correctamente.",
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
        <form method="post" action="../action/sessionAction.php">
          <button type="submit" class="btn btn-success" name="logout" id="logout">Cerrar sesión</button>
        </form>

        <div class="text-center mb-4">
            <h3>Agregar un nuevo criterio</h3>
            <p class="text-muted">Complete el formulario para añadir un nuevo criterio</p>
        </div>

        <div class="container d-flex justify-content-center">
            <form method="post" action="../action/criterioAction.php" style="width: 50vw; min-width:300px;">
                <input type="hidden" name="criterio" value="<?php echo htmlspecialchars($idCriterio); ?>">

                <div class="row">
                    <div class="col">
                        <label for="nombre" class="form-label">Nombre: </label>
                        <input required type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre del criterio" />
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
          include '../bussiness/criterioBusiness.php';
          $criterioBusiness = new CriterioBusiness();
          $criterios = $criterioBusiness->getAllTbCriterio();
          $mensajeActualizar = "¿Desea actualizar este criterio?";
          $mensajeEliminar = "¿Desea eliminar este criterio?";

          if ($criterios != null) {
            foreach ($criterios as $criterio) {
              echo '<tr>';
              echo '<form method="post" enctype="multipart/form-data" action="../action/criterioAction.php">';
              echo '<input type="hidden" name="idCriterio" value="' . htmlspecialchars($criterio->getTbCriterioId()) . '">';
              echo '<td>' . htmlspecialchars($criterio->getTbCriterioId()) . '</td>';
              echo '<td><input type="text" name="nombre" id="nombre" value="' . htmlspecialchars($criterio->getTbCriterioNombre()) . '" class="form-control" /></td>';   
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
