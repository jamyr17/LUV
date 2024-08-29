<?php 
  include '../action/functions.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LUV</title>
  
</head>

<body>
    <?php
        if (isset($_GET['error'])) {
          $mensaje = "Ocurrió un error debido a ";
          $mensaje .= match(true){
            $_GET['error']=="emptyField" => "campo(s) vacío(s).",
            $_GET['error']=="numberFormat" => "ingreso de valores númericos.",
            $_GET['error']=="dbError" => "un problema al procesar la transacción.",
            $_GET['error']=="existPerson" => "que ya hay una cuenta asociada a su cédula.",
            $_GET['error']=="existUsername" => "que dicho nombre de usuario ya existe.",
            default => "un problema inesperado.",
          };
        } 

        if(isset($mensaje)){
          echo "<script>alert('$mensaje')</script>";
        }
    ?>

    <button onclick="window.location.href='login.php';">Volver</button>
    
    <section id="form-register">
        <div>
            <h3>Ingrese sus datos</h3>

            <form method="post" action="../action/usuarioAction.php">

              <label for="nombre" class="form-label">Cédula: </label>
              <?php generarCampoTexto('cedula','formCrearData','Ingrese su número de cédula','') ?><br>

              <label for="nombre" class="form-label">Primer nombre: </label>
              <?php generarCampoTexto('primerNombre','formCrearData','Ingrese su primer nombre','') ?><br>

              <label for="nombre" class="form-label">Primer apellido: </label>
              <?php generarCampoTexto('primerApellido','formCrearData','Ingrese su primer apellido','') ?><br>

              <label for="nombre" class="form-label">Nombre de usuario: </label>
              <?php generarCampoTexto('nombreUsuario','formCrearData','Ingrese un nombre de usuario','') ?><br>

              <label for="nombre" class="form-label">Contraseña: </label>
              <?php generarCampoContrasena('contrasena','formCrearData', 'Ingrese su contraseña','') ?><br>

              <div>
                  <button type="submit" class="btn btn-success" name="newUser" id="newUser">Registrarse</button>
              </div>
            </form>
        </div>

    </section>

</body>

<footer>
</footer>

<?php 
  eliminarFormData();
?>
</html>