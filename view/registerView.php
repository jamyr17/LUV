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
                <input required type="text" name="cedula" id="cedula" placeholder="Ingrese su número de cédula" /><br>
                <input required type="text" name="primerNombre" id="primerNombre" placeholder="Ingrese su primer nombre" /><br>
                <input required type="text" name="primerApellido" id="primerNombre" placeholder="Ingrese su primer apellido" /><br>
                <input required type="text" name="nombreUsuario" id="nombreUsuario" placeholder="Ingrese un nombre de usuario" /><br>
                <input required type="password" name="contrasena" id="contrasena" placeholder="Ingrese una contraseña" /><br>

                <div>
                    <button type="submit" class="btn btn-success" name="newUser" id="newUser">Registrarse</button>
                </div>
            </form>
        </div>

    </section>

</body>

<footer>
</footer>

</html>