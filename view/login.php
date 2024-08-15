<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LUV</title>
  <script>
    function showMessage(mensaje){
        alert(mensaje);
    }

  </script>

</head>

<body>
    <section id="alerts">
        <?php
            if (isset($_GET['error'])) {
                $mensaje = "Ocurrió un error debido a ";
                $mensaje .= match(true){
                    $_GET['error']=="accessDenied" => "que no cuenta con permisos. Por favor, inicie sesión e inténtelo de nuevo.",
                    default => "un problema inesperado.",
                  };
            }

            if(isset($mensaje)){
                echo "<script>showMessage('$mensaje')</script>";
              }
        ?>
    </section>

    <section id="login">
        <div>
            <h3>Inicie sesión en LUV</h3>

            <form method="post" action="../bussiness/usuarioAction.php">
                <input required type="text" name="nombreUsuario" id="nombreUsuario" placeholder="Ingrese su nombre de usuario" />
                <input required type="password" name="contrasena" id="contrasena" placeholder="Ingrese su contraseña" />

                <div>
                    <button type="submit" class="btn btn-success" name="login" id="login">Ingresar</button>
                </div>
            </form>
        </div>

    </section>

</body>

<footer>
</footer>

</html>