<!DOCTYPE html>
<?php
    include "../action/sessionAction.php";
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LUV Perfil personal</title>
</head>
<body>
    <section id="alerts">
        <?php
            if (isset($_GET['error'])) {
                $mensaje = "Ocurrió un error debido a ";
                $mensaje .= match(true){
                    $_GET['error']=="formIncomplete" => "problemas en el procesamiento de su respuesta.",
                    default => "un problema inesperado.",
                };
            } else if (isset($_GET['success'])) {
                $mensaje = match(true){
                    $_GET['success']=="inserted" => "Se ha guardado el perfil personal.",
                    default => "Transacción realizada.",
                };
            }

            if(isset($mensaje)){
                echo "<script>alert('$mensaje')</script>";
            }
        ?>
    </section>

    <div id="container">
        <h3>Modela tu perfil</h3>

        <form id="criteriaForm" method="post" action="../action/personalProfileAction.php" onsubmit="return perfilPersonal.submitForm()">
            <div id="criteriaSection">
                <!-- Los criterios y valores se cargarán aquí -->
            </div>

            <input type="hidden" id="criteriaString" name="criteriaString">
            <input type="hidden" id="valuesString" name="valuesString">

            <button type="submit" name="registrar">Enviar</button>
        </form>
    </div>

    <script src="../js/userPersonalProfile.js"></script>
</body>
</html>
