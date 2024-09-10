<?php
  include_once "../action/sessionAdminAction.php";
  include_once '../action/functions.php';
  include_once '../business/universidadBusiness.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="../js/autocomplete.js" defer></script>
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

  <title>LUV</title>
  <script>
    function actionConfirmation(mensaje, idUniversidad) {

      switch (mensaje){
        case '¿Desea eliminar esta universidad?': 
          if(confirm(mensaje)){
            confirmDelete(idUniversidad);
          }
          break;

        default: 
        return confirm(mensaje);
      }
    }

    function showMessage(mensaje) {
      alert(mensaje);
    }

    function validateForm() {
      var nombre = document.getElementById("nombre").value;
      if (nombre.length > 150) {
        alert("El texto no puede exceder los 150 caracteres.");
        return false;
        return false;
      }
      return true;
    }

    function toggleDeletedUniversities() {
      var section = document.getElementById("table-deleted");
      if (section.style.display === "none") {
        section.style.display = "block";
      } else {
        section.style.display = "none";
      }
    }

    function confirmDelete(idUniversidad) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../action/universidadAction.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            try {
                var response = JSON.parse(xhr.responseText);

                if (response.status === 'confirm') {
                    var confirmed = confirm(response.message);
                    if (confirmed) {
                        // Si el usuario confirma, llamamos a deleteUniversity
                        deleteUniversity(idUniversidad);
                    }
                } else if (response.status === 'proceed') {
                    deleteUniversity(idUniversidad); // Eliminar directamente si no hay campus
                } else {
                    alert(response.message); // Mostrar cualquier error
                }
            } catch (e) {
                console.error("Error al procesar el JSON: " + e);
                console.log("Respuesta del servidor: ", xhr.responseText);
            }
        }
    };

    xhr.send("idUniversidad=" + idUniversidad + "&action=delete");
}

// Función para eliminar la universidad después de la confirmación
function deleteUniversity(idUniversidad) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../action/universidadAction.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            try {
                var response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    alert(response.message);
                    window.location.reload(); // Recargar la página después de eliminar
                } else {
                    alert(response.message); // Mostrar cualquier error
                }
            } catch (e) {
                console.error("Error al procesar el JSON: " + e);
                console.log("Respuesta del servidor: ", xhr.responseText);
            }
        }
    };

    xhr.send("idUniversidad=" + idUniversidad + "&action=deleteConfirmed");
}
</script>

</head>

<body>

  <header>
    <nav class="navbar bg-body-tertiary"></nav>
  </header>

  <div class="container mt-3">
    <section id="alerts">
      <?php
      if (isset($_GET['error'])) {
        $mensaje = "Ocurrió un error debido a ";
        $mensaje .= match (true) {
          $_GET['error'] == "emptyField" => "campo(s) vacío(s).",
          $_GET['error'] == "numberFormat" => "ingreso de valores númericos.",
          $_GET['error'] == "dbError" => "un problema al procesar la transacción.",
          $_GET['error'] == "exist" => "que dicha universidad ya existe.",
          $_GET['error'] == "alike" => "que el nombre es muy similar.",
          $_GET['error'] == "longText" => "el nombre supera los 150 caracteres.",
          default => "un problema inesperado.",
        };
      } else if (isset($_GET['success'])) {
        $mensaje = match (true) {
          $_GET['success'] == "inserted" => "Universidad creada correctamente.",
          $_GET['success'] == "updated" => "Universidad actualizada correctamente.",
          $_GET['success'] == "deleted" => "Universidad eliminada correctamente.",
          $_GET['success'] == "restored" => "Universidad restaurada correctamente.",
          default => "Transacción realizada.",
        };
      }

      if (isset($mensaje)) {
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
          <h3>Agregar una nueva universidad</h3>
          <p class="text-muted">Complete el formulario para añadir una nueva universidad</p>
        </div>

        <div class="container d-flex justify-content-center">
          <form method="post" action="../action/universidadAction.php" style="width: 50vw; min-width:300px;" onsubmit="return validateForm()">
            <input type="hidden" name="universidad" value="<?php echo htmlspecialchars($idUniversidad); ?>">
            <input type="hidden" id="type" name="type" value="universidad"> <!-- Campo oculto para el tipo de objeto -->

            <div class="row">
              <div class="col">
                <label for="nombre" class="form-label">Nombre: </label>
                <?php generarCampoTexto('nombre', 'formCrearData', 'Universidad Nacional', ''); ?>
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
          $universidadBusiness = new UniversidadBusiness();
          $universidades = $universidadBusiness->getAllTbUniversidad();

          if ($universidades != null) {
            foreach ($universidades as $universidad) {
              echo '<tr>';
              echo '<form method="post" enctype="multipart/form-data" action="../action/universidadAction.php" onsubmit="return validateForm()">';
              echo '<input type="hidden" name="idUniversidad" value="' . htmlspecialchars($universidad->getTbUniversidadId()) . '">';
              echo '<td>' . htmlspecialchars($universidad->getTbUniversidadId()) . '</td>';
              echo '<td><input required type="text" class="form-control" name="nombre" id="nombre" value="' . $universidad->getTbUniversidadNombre() . '"></td>';
              echo '<td><input type="submit" name="update" id="update" value="Actualizar"></td>';
              echo '<td><button type="button" name="delete" id="delete" onclick="actionConfirmation( \'¿Desea eliminar esta universidad?\', ' . htmlspecialchars($universidad->getTbUniversidadId()) . ')">Eliminar</button></td>';
              echo '</form>';
              echo '</tr>';
            }
          } else {
            echo '<tr>';
            echo '<td colspan="8">No hay universidades registradas</td>';
            echo '</tr>';
          }
          ?>
        </tbody>
      </table>
    </section>

    <!-- Ocultar esta sección por defecto -->
    <section id="table-deleted" style="display: none;">
      <div class="text-center mb-4">
        <h3>Universidades eliminadas</h3>
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
          $universidadesEliminadas = $universidadBusiness->getAllDeletedTbUniversidad();

          if ($universidadesEliminadas != null) {
            foreach ($universidadesEliminadas as $universidad) {
              echo '<tr>';
              echo '<form method="post" enctype="multipart/form-data" action="../action/universidadAction.php" onsubmit="return validateForm()">';
              echo '<input type="hidden" name="idUniversidad" value="' . htmlspecialchars($universidad->getTbUniversidadId()) . '">';
              echo '<td>' . htmlspecialchars($universidad->getTbUniversidadId()) . '</td>';
              echo '<td><input required type="text" class="form-control" name="nombre" id="nombre" value="' . $universidad->getTbUniversidadNombre() . '" readonly></td>';
              echo '<td><input type="submit" name="restore" id="restore" value="Restaurar" onclick="return actionConfirmation(\'¿Desea restaurar?\')"></td>';
              echo '</form>';
              echo '</tr>';
            }
          } else {
            echo '<tr>';
            echo '<td colspan="8">No hay universidades eliminadas</td>';
            echo '</tr>';
          }
          ?>
        </tbody>
      </table>
    </section>

    <button onclick="toggleDeletedUniversities()" style="margin-top: 20px;">Ver/Ocultar Universidades Eliminadas</button>

  </div>

</body>

</html>