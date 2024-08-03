<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LUV</title>
  <script>
    function deleteConfirmation(){
      var response = confirm("¿Desea eliminar esta área de conocimiento?");
      return response;
    }

    function updateConfirmation(){
      var response = confirm("¿Desea actualizar el nombre de esta área de conocimiento?");
      return response;
    }
  </script>
</head>

<body>

  <header>
    <nav class="navbar bg-body-tertiary">
    </nav>
  </header>

  <div class="container mt-3">
    <section id="form">
      <div class="container">

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
                <textarea required type="text" name="descripcion" id="descripcion" class="form-control" placeholder="Descripción del área de conocimiento" rows="3"></textarea>
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

          if ($areasConocimiento != null) {
            foreach ($areasConocimiento as $areaConocimiento) {
              echo '<tr>';
              echo '<form method="post" enctype="multipart/form-data" action="../bussiness/areaConocimientoAction.php">';
              echo '<input type="hidden" name="idAreaConocimiento" value="' . htmlspecialchars($areaConocimiento->getTbAreaConocimientoId()) . '">';
              echo '<td>' . htmlspecialchars($areaConocimiento->getTbAreaConocimientoId()) . '</td>';
              echo '<td><input type="text" name="nombre" id="nombre" value="' . htmlspecialchars($areaConocimiento->getTbAreaConocimientoNombre()) . '" class="form-control" /></td>';
              echo '<td><input type="text" name="descripcion" id="descripcion" value="' . htmlspecialchars($areaConocimiento->getTbAreaConocimientoDescripcion()) . '" class="form-control" /></td>';
              echo '<td>';
              echo '<button type="submit" class="btn btn-warning me-2" name="update" id="update" onclick="return updateConfirmation()">Actualizar</button>';
              echo '<button type="submit" class="btn btn-danger" name="delete" id="delete" onclick="return deleteConfirmation()">Eliminar</button>';
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
