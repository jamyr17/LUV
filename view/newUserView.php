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

    function updateHiddenFields() {
        var universidadSelect = document.getElementById('universidad');
        var universidadIdInput = document.getElementById('idUniversidad');
        universidadIdInput.value = universidadSelect.value;
    }

    document.addEventListener('DOMContentLoaded', function () {
        updateHiddenFields();

        var universidadSelect = document.getElementById('universidad');
        var campusSelect = document.getElementById('campus');
        var universidadIdInput = document.getElementById('idUniversidad');

        function updateCampusOptions(universityId) {
            while (campusSelect.options.length >= 1) {
                campusSelect.removeChild(campusSelect.lastChild);
            }

            // Verifica si hay campus para la universidad seleccionada
            var campuses = campusByUniversity[universityId] || [];
            campuses.forEach(function (campus) {
                var option = document.createElement('option');
                option.value = campus.id;
                option.textContent = campus.name;
                campusSelect.appendChild(option);
            });

            var option = document.createElement('option');
            option.value = 0;
            option.textContent = 'Otro';
            campusSelect.appendChild(option);

            // Selecciona el primer campus si hay alguno
            if (campuses.length > 0) {
                campusSelect.value = campuses[0].id;
            } else {
                campusSelect.value = '0';
            }
        }

        // Inicializa el menú de campus con la primera universidad
        var initialUniversityId = universidadSelect.value;
        updateCampusOptions(initialUniversityId);

        // Actualiza los campus cuando se cambia la universidad
        universidadSelect.addEventListener('change', function () {
            updateCampusOptions(this.value);
            updateHiddenFields();
        });

    });

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
        <div>                                       <!--cambiar envio de form-->
            <form method="post" action="../bussiness/universidadAction.php" style="width: 50vw; min-width:300px;">
                <?php
                    include '../bussiness/universidadBussiness.php';
                    include '../bussiness/orientacionSexualBussiness.php';
                    include '../bussiness/generoBusiness.php';
                    include '../bussiness/campusBussiness.php';

                    $universidadBusiness = new UniversidadBusiness();
                    $universidades = $universidadBusiness->getAllTbUniversidad();
                    $orientacionSexualBusiness = new OrientacionSexualBusiness();
                    $orientacionesSexuales = $orientacionSexualBusiness->getAllTbOrientacionSexual();
                    $generoBusiness = new GeneroBusiness();
                    $generos = $generoBusiness->getAllTbGenero();
                    $campusBusiness = new CampusBusiness();
                    $campusPlural = $campusBusiness->getAllTbCampus();

                    $campusByUniversity = [];
                    foreach ($campusPlural as $campus) {
                        $universityId = $campus->gettbCampusUniversidadId();
                        if (!isset($campusByUniversity[$universityId])) {
                            $campusByUniversity[$universityId] = [];
                        }
                        $campusByUniversity[$universityId][] = [
                            'id' => $campus->getTbCampusId(),
                            'name' => $campus->getTbCampusNombre()
                        ];
                    }

                    // Codifica los datos en JSON para usarlos en JavaScript
                    echo '<script>';
                    echo 'var campusByUniversity = ' . json_encode($campusByUniversity) . ';';
                    echo '</script>';

                    echo '<label for="universidad">Seleccione su universidad: </label>';
                    echo '<select name="universidad" id="universidad" onchange="showOtherField(\'universidad\', \'request-universidad\')">';
                    foreach ($universidades as $universidad){
                        echo '<option value="' . htmlspecialchars($universidad->getTbUniversidadId()) . '"> ' . htmlspecialchars($universidad->getTbUniversidadNombre()) . '</option>';
                    }
                    echo '<option value="0">Otra</option>';
                    echo '</select><br>';

                    echo '<label for="campus">Seleccione su campus: </label>';
                    echo '<select name="campus" id="campus" onchange="showOtherField(\'campus\', \'request-campus\')">';

                    echo '</select><br>';

                    echo '<label for="genero">Seleccione su género: </label>';
                    echo '<select name="genero" id="genero" onchange="showOtherField(\'genero\', \'request-genero\')">';
                    foreach ($generos as $genero){
                        echo '<option value="' . htmlspecialchars($genero->getTbGeneroId()) . '"> ' . htmlspecialchars($genero->getTbGeneroNombre()) . '</option>';
                    }
                    echo '<option value="0">Otro</option>';
                    echo '</select><br>';

                    echo '<label for="orientacionSexual">Seleccione su orientación sexual: </label>';
                    echo '<select name="orientacionSexual" id="orientacionSexual" onchange="showOtherField(\'orientacionSexual\', \'request-orientacionSexual\')">';
                    foreach ($orientacionesSexuales as $orientacionSexual){
                        echo '<option value="' . htmlspecialchars($orientacionSexual->getTbOrientacionSexualId()) . '"> ' . htmlspecialchars($orientacionSexual->getTbOrientacionSexualNombre()) . '</option>';
                    }
                    echo '<option value="0">Otra</option>';
                    echo '</select><br>';
                ?>

                <button type="submit" class="btn btn-success" name="new" id="new">Enviar</button>
            </form>

            <div id="request-universidad" style="display:none;">
                <form id="request-universidad-form" onsubmit="submitRequest(event, 'request-universidad-form')" style="width: 50vw; min-width:300px;">
                    <input type="text" name="request-universidadNombre" placeholder="Especifique otra universidad"> 
                    <button type="submit"  class="btn btn-success" name="request-universidad-btn" id="request-universidad-btn">Solicitar</button>
                </form>
            </div>

            <div id="request-campus" style="display:none;">
                <form id="request-campus-form" onsubmit="submitRequest(event, 'request-campus-form')" style="width: 50vw; min-width:300px;">
                    <input type="hidden" name="idUniversidad" id="idUniversidad">
                    <input type="text" name="request-campusNombre" placeholder="Especifique otro campus"> 
                    <button type="submit" class="btn btn-success" name="request-campus-btn" id="request-campus-btn">Solicitar</button>
                </form>
            </div>

            <div id="request-genero" style="display:none;">
                <form id="request-genero-form" onsubmit="submitRequest(event, 'request-genero-form')" style="width: 50vw; min-width:300px;">
                    <input type="text" name="request-generoNombre" placeholder="Especifique otro género"> 
                    <button type="submit" class="btn btn-success" name="request-genero-btn" id="request-genero-btn">Solicitar</button>
                </form>
            </div>

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
