<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LUV</title>
  <script>
    function deleteConfirmation(){
      var response = confirm("¿Desea eliminar esta universidad?")
      if(response==true){
        return true
      }else{
        return false
      }
    }

    function updateConfirmation(){
      var response = confirm("¿Desea actualizar el nombre de esta universidad?")
      if(response==true){
        return true
      }else{
        return false
      }
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
      <div class="containter">

        <div class="text-center mb-4">
            <h3>Agregar una nueva universidad</h3>
            <p class="text-muted">Complete el formulario para añadir una nueva universidad</p>
        </div>

        <div class="container d-flex justify-content-center">
            <form method="post" action="../bussiness/universidadAction.php" style="width: 50vvw; min-width:300px;">
                <input type="hidden" name="universidad" value="<?php echo htmlspecialchars($idUniversidad); ?>">

                <div class="row">
                    <div class="col">
                        <label for="nombre" class="form-label">Nombre: </label>
                        <input required type="text" name="nombre" id="nombre" class="form-control" placeholder="Universidad Nacional" />
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
      <h3>Universidades registradas</h3>
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
          include '../bussiness/universidadBussiness.php';
          $universidadBusiness = new UniversidadBusiness();
          $universidades = $universidadBusiness->getAllTbUniversidad();

          if ($universidades != null) {
            foreach ($universidades as $universidad) {
              echo '<tr>';
              echo '<form method="post" enctype="multipart/form-data" action="../bussiness/universidadAction.php">';
              echo '<input type="hidden" name="idUniversidad" value="' . htmlspecialchars($universidad->getTbUniversidadId()) . '">';
              echo '<td>' . htmlspecialchars($universidad->getTbUniversidadId()) . '</td>';
              echo '<td><input type="text" name="nombre" id="nombre" value="' . htmlspecialchars($universidad->getTbUniversidadNombre()) . '" class="form-control" /></td>';
              echo '<td>';
              echo '<button type="submit" class="btn btn-warning me-2" name="update" id="update" onclick="return updateConfirmation()" >Actualizar</button>';
              echo '<button type="submit" class="btn btn-danger" name="delete" id="delete" onclick="return deleteConfirmation()" >Eliminar</button>';
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