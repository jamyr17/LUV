<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LUV</title>
  <script>
    function showMessage(mensaje) {
        alert(mensaje);
    }
  </script>
</head>
<body>
    <section id="alerts">
        <?php
            if (isset($_GET['error'])) {
                $mensaje = match(true) {
                    $_GET['error'] == "accessDenied" => "Por favor, inicie sesión e inténtelo de nuevo.",
                    $_GET['error'] == "noValidated" => "No se pudo verificar su cuenta. Inténtelo de nuevo.",
                    default => "Ocurrió un problema inesperado.",
                };
            }

            if (isset($_GET['success'])) {
                $mensaje = match(true) {
                    $_GET['success'] == "logout" => "Ha cerrado su sesión exitosamente.",
                    $_GET['success'] == "inserted" => "Ha creado una cuenta de manera exitosa. Puede iniciar sesión.",
                    default => "Transacción realizada correctamente.",
                };
            }

            if (isset($mensaje)) {
                echo "<script>showMessage('$mensaje')</script>";
            }
        ?>
    </section>

    <section id="login">
        <div>
            <h3>Inicie sesión en LUV</h3>
            <form method="post" action="../action/usuarioAction.php">
                <input required type="text" name="nombreUsuario" id="nombreUsuario" placeholder="Ingrese su nombre de usuario" />
                <input required type="password" name="contrasena" id="contrasena" placeholder="Ingrese su contraseña" />
                <div>
                    <button type="submit" name="login">Ingresar</button>
                </div>
            </form>
        </div>

        <div>
            <h5>¿No tiene una cuenta?</h5>
            <a href="registerView.php">Regístrese</a>
        </div>
    </section>
</body>
</html>
