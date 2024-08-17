<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LUV</title>
  <script>
    function showOtherField(selectId, divId) {
        var selectElement = document.getElementById(selectId);
        var otherField = document.getElementById(divId);
        
        if (selectElement.value === '0') {
            otherField.style.display = 'block';
        } else {
            otherField.style.display = 'none';
        } 
    }

    function showMessage(mensaje){
      alert(mensaje);
    }

    function submitRequest(event, formId) {
        event.preventDefault(); // Evita el envío del formulario

        var requestForm = document.getElementById(formId);
        var formData = new FormData(requestForm);

        fetch('../bussiness/requests.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: formData
        })
        .then(response => response.text()) // Leer la respuesta como texto
        .then(text => {
            try {
                var data = JSON.parse(text); // Intentar analizar como JSON
                showMessage(data.message);
            } catch (error) {
                console.error('Error parsing JSON:', error);
                showMessage('Error al procesar la respuesta del servidor.');
            }
        })
        .catch(error => console.error('Error:', error));
    }

  </script>
</head>
<body>
    <section id="alerts">
    <?php
        if (isset($_GET['error'])) {
            $mensaje = "Ocurrió un error debido a ";
            $mensaje .= match(true){
                $_GET['error']=="emptyField" => "campo(s) vacío(s).",
                $_GET['error']=="numberFormat" => "ingreso de valores númericos.",
                $_GET['error']=="dbError" => "un problema al procesar la transacción.",
                $_GET['error']=="exist" => "que dicha universidad u orientación sexual ya existe.",
                default => "un problema inesperado.",
            };
        } else if (isset($_GET['success'])) {
            $mensaje = match(true){
            $_GET['success']=="requested" => "Solicitud realizada correctamente.",
            default => "Transacción realizada.",
            };
        }

        if(isset($mensaje)){
            echo "<script>showMessage('$mensaje')</script>";
        }
    ?>
    </section>

    <section id="form">
        <div>
            <!-- Formulario para seleccionar universidad -->
            <form method="post" action="../bussiness/universidadAction.php" style="width: 50vw; min-width:300px;">
                <?php
                    include '../bussiness/universidadBussiness.php';
                    $universidadBusiness = new UniversidadBusiness();
                    $universidades = $universidadBusiness->getAllTbUniversidad();

                    echo '<label for="universidad">Seleccione su universidad: </label>';
                    echo '<select name="universidad" id="universidad" onchange="showOtherField(\'universidad\', \'request-universidad\')">';
                    foreach ($universidades as $universidad){
                        echo '<option value="' . htmlspecialchars($universidad->getTbUniversidadId()) . '"> ' . htmlspecialchars($universidad->getTbUniversidadNombre()) . '</option>';
                    }
                    echo '<option value="0">Otra</option>';
                    echo '</select>';
                ?>

                <button type="submit" class="btn btn-success" name="new" id="new">Enviar</button>
            </form>

            <div id="request-universidad" style="display:none;">
                <form id="request-universidad-form" onsubmit="submitRequest(event, 'request-universidad-form')" style="width: 50vw; min-width:300px;">
                    <input type="text" name="request-universidadNombre" placeholder="Especifique otra universidad"> 
                    <button type="submit" class="btn btn-success" name="request-universidad-btn" id="request-universidad-btn">Solicitar</button>
                </form>
            </div>
        </div>

        <div>
            <!-- Formulario para seleccionar orientación sexual -->
            <form method="post" action="../bussiness/orientacionSexualAction.php" style="width: 50vw; min-width:300px;">
                <?php
                    include '../bussiness/orientacionSexualBussiness.php';
                    $orientacionSexualBusiness = new OrientacionSexualBusiness();
                    $orientacionesSexuales = $orientacionSexualBusiness->getAllTbOrientacionSexual();

                    echo '<label for="orientacionSexual">Seleccione su orientación sexual: </label>';
                    echo '<select name="orientacionSexual" id="orientacionSexual" onchange="showOtherField(\'orientacionSexual\', \'request-orientacionSexual\')">';
                    foreach ($orientacionesSexuales as $orientacionSexual){
                        echo '<option value="' . htmlspecialchars($orientacionSexual->getTbOrientacionSexualId()) . '"> ' . htmlspecialchars($orientacionSexual->getTbOrientacionSexualNombre()) . '</option>';
                    }
                    echo '<option value="0">Otra</option>';
                    echo '</select>';
                ?>

                <button type="submit" class="btn btn-success" name="new" id="new">Enviar</button>
            </form>

            <div id="request-orientacionSexual" style="display:none;">
                <form id="request-orientacionSexual-form" onsubmit="submitRequest(event, 'request-orientacionSexual-form')" style="width: 50vw; min-width:300px;">
                    <input type="text" name="request-orientacionSexualNombre" placeholder="Especifique otra orientación sexual"> 
                    <button type="submit" class="btn btn-success" name="request-orientacionSexual-btn" id="request-orientacionSexual-btn">Solicitar</button>
                </form>
            </div>
        </div>
    </section>
</body>
<footer>

</footer>
</html>
