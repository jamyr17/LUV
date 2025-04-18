<?php
include_once "../action/sessionUserAction.php";  // Se incluye sessionUserAction.php que ya contiene session_start()
include '../action/functions.php';
include '../data/userAffinityData.php';
require_once '../business/usuarioBusiness.php';

$userAffinityData = new UserAffinityData();
$usuarioBusiness = new UsuarioBusiness();

// Obtener el ID del usuario usando el nombre de usuario en la sesión
$userId = $usuarioBusiness->getIdByName($_SESSION['nombreUsuario']);

// Comprobar si el perfil ya ha sido modelado y si no se ha marcado "modificar" en la sesión
if ($userAffinityData->isProfileModeled($userId) && !isset($_GET['modificar'])) {
    echo "<script>
        if (confirm('Tú ya has modelado tu perfil, ¿deseas modificarlo?')) {
            window.location.href = '../view/userPersonalProfileCarruselView.php?modificar=1';
        } else {
            window.location.href = '../view/userNavigateView.php';
        }
    </script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <title>Carrusel de Formularios</title>
    <style>
        .form-container {
            display: none;
            /* Ocultar todos los formularios por defecto */
        }

        .form-container.active {
            display: block;
            /* Mostrar solo el formulario activo */
        }
    </style>
    <script>
        const formData = {
            genero: null,
            orientacionSexual: null,
            areaConocimiento: null,
            universidad: null,
            campus: null,
            colectivos: null,
        };

        function nextForm(formNumber) {
            // Ocultar todos los formularios
            const forms = document.querySelectorAll('.form-container');
            forms.forEach(form => {
                form.classList.remove('active');
            });
            // Mostrar el formulario seleccionado
            document.getElementById('form' + formNumber).classList.add('active');
        }


        function saveData(formId, formNumber) {
            const formElement = document.getElementById(formId);
            const formValues = new FormData(formElement);
            // Guardar los datos en el objeto
            formValues.forEach((value, key) => {
                if (key === 'colectivos[]') {
                    if (!formData.colectivos) {
                        formData.colectivos = [];
                    }
                    formData.colectivos.push(value);
                    console.log(value);
                } else {
                    formData[key] = value;
                    console.log(value);
                }
            });
            // Pasar al siguiente formulario
            if (formNumber !== 10) {
                nextForm(formNumber);
            }
        }
        function submitAll() {
            // Convertir el objeto en FormData para enviarlo
            const finalFormData = new FormData();
            for (const key in formData) {
                if (Array.isArray(formData[key])) {
                    finalFormData.append(key, JSON.stringify(formData[key]));
                } else {
                    finalFormData.append(key, formData[key]);
                }
            }

            // Añadir el campo 'registrar' para indicar que es una solicitud de registro
            finalFormData.append('registrar', 'true');

            fetch('../action/personalProfileAction.php', {
                method: 'POST',
                body: finalFormData
            })
            .then(response => response.text()) // Cambia a .text() para ver la respuesta completa
            .then(textData => {
                console.log('Texto de respuesta:', textData); // Ver qué está devolviendo el servidor

                // Intenta convertir el texto a JSON
                try {
                    const data = JSON.parse(textData);
                    if (data.success) {
                        if (data.success === "updated") {
                            alert("Se ha actualizado su perfil personal.");
                        } else {
                            alert("Se ha guardado el perfil personal.");
                        }
                        window.location.href = '../view/userWantedProfileView.php';
                    } else {
                        console.error('Error de servidor: ', data.error);
                        window.location.href = '../view/userPersonalProfileCarruselView.php?error=' + encodeURIComponent(data.error);
                    }
                } catch (error) {
                    console.error('Error al analizar el JSON:', error);
                    console.log('Datos recibidos (no válidos):', textData);
                    alert('Hubo un error al procesar la respuesta del servidor. Revisa la consola para más detalles.');
                }
            })
            .catch(error => console.error('Error de red:', error));
        }

        $(document).ready(function() {


            fetch('../data/getData.php?type=1')
                .then(response => response.json())
                .then(data => {
                    const universidadSelect = document.getElementById('universidad');
                    universidadSelect.innerHTML = ''; // Limpiar el select de universidades

                    data.forEach(function(univ) {
                        const option = new Option(univ.name, univ.name);
                        universidadSelect.appendChild(option);
                    });
                    $('#universidad').append(new Option('Otro', '0'));

                    if (data.length > 0) {
                        $('#universidad').val(data[0].name).change(); // Seleccionar la primera universidad y disparar el evento
                    }
                })
                .catch(err => console.error('Error al cargar universidades:', err));

            $('#universidad').change(function() {
                const universidadNombre = $(this).val();
                $.ajax({
                    url: '../data/getData.php',
                    method: 'GET',
                    data: {
                        universidadNombre: universidadNombre
                    },
                    dataType: 'json',
                    success: function(data) {
                        $('#campus').empty(); // Limpiar el select de campus
                        data.forEach(function(camp) {
                            $('#campus').append(new Option(camp.nombre, camp.nombre));
                        });
                        $('#campus').append(new Option('Otro', '0'));

                        if (data.length > 0) {
                            $('#campus').val(data[0].nombre).change(); // Seleccionar el primer campus y disparar el evento
                        }
                    },
                    error: function(err) {
                        console.error('Error al cargar campus:', err);
                    }
                });
            });

            $('#campus').change(function() {
                const campusNombre = $(this).val();
                if (campusNombre === '0') {
                    $('#colectivos').empty();
                } else {
                    $.ajax({
                        url: '../data/getData.php',
                        method: 'GET',
                        data: {
                            campusNombre: campusNombre
                        },
                        dataType: 'json',
                        success: function(data) {
                            $('#colectivos').empty();
                            data.forEach(function(colectivo) {
                                $('#colectivos').append(new Option(colectivo.nombre, colectivo.nombre));
                            });
                        },
                        error: function(err) {
                            console.error('Error al cargar colectivos:', err);
                        }
                    });
                }
            });
        });

        function showMessage(mensaje) {
            alert(mensaje);
        }

        function showOtherField(selectId, divId, esNuevaUniversidad) {
            var selectElement = document.getElementById(selectId);
            var otherField = document.getElementById(divId);

            if (!esNuevaUniversidad) {
                if (selectElement.value === '0') {
                    otherField.style.display = 'block';
                } else {
                    otherField.style.display = 'none';
                }
            } else {
                var campusField = document.getElementById('request-campus');
                if (selectElement.value === '0') {
                    otherField.style.display = 'block';
                    campusField.style.display = 'block';
                    $('#colectivos').empty();
                } else {
                    otherField.style.display = 'none';
                }
            }
        }

        function hideField(inputId, divId, formOtherId, formId, nextForm) {
            var inputElement = document.getElementById(inputId);
            var otherField = document.getElementById(divId);

            if (inputElement.value !== '') {
                saveData(formOtherId, nextForm);
                otherField.style.display = 'none';
            } else {
                saveData(formId, nextForm);
                otherField.style.display = 'none';
            }
        }

        function hideFields(inputUniversidadId, inputCampusId, divUniversidadId, divCampusId, formOtherUniversidadId, formOtherCampusId, nextForm) {
            const inputUniversidadElement = document.getElementById(inputUniversidadId);
            const inputCampusElement = document.getElementById(inputCampusId);
            const otherFieldUniversidad = document.getElementById(divUniversidadId);
            const otherFieldCampus = document.getElementById(divCampusId);

            // Función auxiliar para extraer y asignar valores
            const assignValues = (formularioUniversidad, formularioCampus, universidadField, campusField) => {
                if (universidadField) {
                    console.log('esta aquí entrando');
                    saveData(formularioUniversidad, 10);
                }
                if (campusField) {
                    saveData(formularioCampus, nextForm);
                }

            };

            // Comprobar los valores de entrada
            if (inputUniversidadElement.value !== '' && inputCampusElement.value !== '') {
                assignValues(
                    formOtherUniversidadId,
                    formOtherCampusId, true, true
                );
                otherFieldUniversidad.style.display = 'none';
                otherFieldCampus.style.display = 'none';
            } else if (inputCampusElement.value !== '') {
                assignValues(
                    'instalacionesForm',
                    formOtherCampusId, true, true
                );
                otherFieldCampus.style.display = 'none';
            } else {
                saveData('instalacionesForm', nextForm);
            }
        }

        function validateAndProceed(inputId, esNuevaUniversidad, divId) {
            let formulario = document.getElementById(divId);
            let formularioCampus = document.getElementById('request-campus');

            if (formulario.style.display === 'block') {

                const input = document.getElementById(inputId);
                const inputFields = [input];

                if (esNuevaUniversidad) {
                    const campusInput = document.getElementById('request-campusNombre');
                    inputFields.push(campusInput);
                }

                for (const field of inputFields) {
                    if (field.value.trim() === '') {
                        field.style.borderColor = 'red';
                        alert('Por favor, complete todos los campos requeridos.');
                        return false;
                    } else {
                        field.style.borderColor = '';
                    }
                }

                return true;
            } else if (formularioCampus.style.display === 'block') {

                let inputCampus = document.getElementById('request-campusNombre');

                if (inputCampus.value.trim() === '') {
                    inputCampus.style.borderColor = 'red';
                    alert('Por favor, complete todos los campos requeridos.');
                    return false;
                } else {
                    inputCampus.style.borderColor = '';
                }

                return true;
            } else {
                console.log('paso por aqui');
                return true;
            }
        }

        function submitRequest(event, formId) {
            event.preventDefault(); // Evita el envío del formulario

            var requestForm = document.getElementById(formId);
            var formData = new FormData(requestForm);

            fetch('../action/requestAction.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    showMessage(data.message);
                })
                .catch(error => {
                    console.error('Error:', error);
                    showMessage('Error al procesar la respuesta del servidor.');
                });
        }
    </script>
</head>

<body data-view="PersonalProfile">

    <section id="alerts">
        <?php
        if (isset($_GET['error'])) {
            $mensaje = "Ocurrió un error debido a ";
            $mensaje .= match (true) {
                $_GET['error'] == "formIncomplete" => "problemas en el procesamiento de su respuesta.",
                default => "un problema inesperado.",
            };
        }

        if (isset($mensaje) && !isset($_GET['success'])) {
            echo "<script>alert('$mensaje')</script>";
        }
        ?>
    </section>

    <div id="form1" class="form-container active">
        <h2>Selecciona tu Género</h2>
        <form id="generoForm">
            <label for="genero">Género:</label>
            <select name="genero" id="genero" onchange="showOtherField('genero', 'request-genero', false)">
                <?php
                include '../business/generoBusiness.php';
                $generoBusiness = new GeneroBusiness();
                $generos = $generoBusiness->getAllTbGenero();
                if ($generos != null) {
                    foreach ($generos as $genero) {
                        echo '<option value="' . htmlspecialchars($genero->getTbGeneroNombre()) . '" title="' . htmlspecialchars($genero->getTbGeneroDescripcion()) . '">' . htmlspecialchars($genero->getTbGeneroNombre()) . '</option>';
                    }
                    echo '<option value="0" title="Solicitar otro género a los administradores">Otro</option>';
                }
                ?>
            </select>
            <button type="button" onclick="if (validateAndProceed('request-generoNombre', false, 'request-genero')) hideField('request-generoNombre', 'request-genero', 'request-genero-form', 'generoForm', 2)">Siguiente</button>
        </form>
    </div>

    <div id="form2" class="form-container">
        <h2>Selecciona tu Orientación Sexual</h2>
        <form id="orientacionSexualForm">
            <label for="orientacionSexual">Orientación Sexual:</label>
            <select name="orientacionSexual" id="orientacionSexual" onchange="showOtherField('orientacionSexual', 'request-orientacionSexual', false)">
                <?php
                include '../business/orientacionSexualBusiness.php';
                $orientacionSexualBusiness = new OrientacionSexualBusiness();
                $orientacionesSexuales = $orientacionSexualBusiness->getAllTbOrientacionSexual();

                if ($orientacionesSexuales != null) {
                    foreach ($orientacionesSexuales as $orientacionSexual) {
                        echo '<option value="' . htmlspecialchars($orientacionSexual->getTbOrientacionSexualNombre()) . '" title="' . htmlspecialchars($orientacionSexual->getTbOrientacionSexualDescripcion()) . '">' . htmlspecialchars($orientacionSexual->getTbOrientacionSexualNombre()) . '</option>';
                    }
                    echo '<option value="0" title="Solicitar otra orientación sexual a los administradores">Otra</option>';
                }
                ?>
            </select>
            <button type="button" onclick="if (validateAndProceed('request-orientacionSexualNombre', false, 'request-orientacionSexual')) hideField('request-orientacionSexualNombre', 'request-orientacionSexual', 'request-orientacionSexual-form', 'orientacionSexualForm', 3)">Siguiente</button>
        </form>
    </div>

    <div id="form3" class="form-container">
        <h2>Selecciona tu área de conocimiento</h2>
        <form id="areaConocimientoForm">
            <label for="areaConocimiento">Área de conocimiento:</label>
            <select name="areaConocimiento" id="areaConocimiento" onchange="showOtherField('areaConocimiento', 'request-areaConocimiento', false)">
                <?php
                include '../business/areaConocimientoBusiness.php';
                $areaConocimientoBusiness = new AreaConocimientoBusiness();
                $areasConocimiento = $areaConocimientoBusiness->getAllTbAreaConocimiento();

                if ($areasConocimiento != null) {
                    foreach ($areasConocimiento as $areaConocimiento) {
                        echo '<option value="' . htmlspecialchars($areaConocimiento->getTbAreaConocimientoNombre()) . '" title="' . htmlspecialchars($areaConocimiento->getTbAreaConocimientoDescripcion()) . '">' . htmlspecialchars($areaConocimiento->getTbAreaConocimientoNombre()) . '</option>';
                    }
                    echo '<option value="0" title="Solicitar otra área de conocimiento a los administradores">Otra</option>';
                }
                ?>
            </select>
            <button type="button" onclick="if (validateAndProceed('request-areaConocimientoNombre', false, 'request-areaConocimiento')) hideField('request-areaConocimientoNombre', 'request-areaConocimiento', 'request-areaConocimiento-form', 'areaConocimientoForm', 6)">Siguiente</button>
        </form>
    </div>
    <div id="form6" class="form-container">
    <h2>Analiza esta imagen por unos segundos</h2>
    <form id="imagenForm">
        <title>Análisis de Imagen con Zoom y Regiones</title>
        <style>
            body {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                background-color: #f0f0f0;
                margin: 0;
            }
            .container {
                position: relative;
                width: 80%;
                max-width: 800px;
                overflow: hidden;
                border: 1px solid #ddd;
            }
            .image {
                width: 100%;
                transition: transform 0.3s ease;
            }
        </style>

        <div class="container">
            <img src="https://res.cloudinary.com/dwhwrxgud/image/upload/v1731381317/images/r9loiygghsxdcihqaisv.jpg" 
                alt="Imagen a analizar" class="image" id="image">
        </div>

        <!-- Botón "Siguiente" que aparece después de un tiempo -->
        <button type="button" id="siguienteBtn" style="display:none;" onclick="nextForm(4)">Siguiente</button>

        <script>
            $(document).ready(function() {
                let startTime = null;
                let activeRegion = null;
                let zoomScale = 1;

                const image = document.getElementById('image');

                // Funcionalidad de zoom
                image.addEventListener('wheel', (event) => {
                    event.preventDefault();
                    const zoomFactor = 0.1;
                    zoomScale += event.deltaY < 0 ? zoomFactor : -zoomFactor;
                    zoomScale = Math.max(1, zoomScale);
                    image.style.transform = `scale(${zoomScale})`;
                });

                // Divide la imagen en una cuadrícula de 3x3 y limita los valores a 1-3
                image.addEventListener('mousemove', (event) => {
                    const imageWidth = image.offsetWidth;
                    const imageHeight = image.offsetHeight;
                    const mouseX = event.offsetX;
                    const mouseY = event.offsetY;

                    // Calcular la región y limitarla entre 1 y 3
                    let regionX = Math.min(3, Math.max(1, Math.floor(mouseX / (imageWidth / 3)) + 1));
                    let regionY = Math.min(3, Math.max(1, Math.floor(mouseY / (imageHeight / 3)) + 1));
                    const currentRegion = `${regionY},${regionX}`;

                    // Rastrear cambios de región
                    if (activeRegion !== currentRegion) {
                        if (startTime && activeRegion) {
                            const duration = Date.now() - startTime;
                            enviarDatosSegmentacion(activeRegion, duration, zoomScale);
                        }
                        activeRegion = currentRegion;
                        startTime = Date.now();
                        console.log(`Entrando en la región ${activeRegion}`);
                    }
                });

                // Envía los datos de segmentación al backend
                function enviarDatosSegmentacion(region, duration, zoomScale) {
                    console.log(`Saliendo de la región ${region} después de ${duration}ms`);
                    fetch('../action/userAffinityAction.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ region, duration, zoomScale, imageURL: image.src })
                    })
                    .then(response => response.json())
                    .then(data => console.log('Datos enviados:', data))
                    .catch(error => console.error('Error al enviar datos:', error));
                }

                // Muestra el botón "Siguiente" después de cierto tiempo
                setTimeout(() => {
                    document.getElementById('siguienteBtn').style.display = 'block';
                    console.log("Botón 'Siguiente' activado");
                }, 5000); // Cambia el tiempo según sea necesario (5000ms = 5 segundos)
            });
        </script>

    </form>
</div>


    
    <div id="form4" class="form-container">
        <h2>Tus instalaciones</h2>
        <h3>Cuéntale a otros sobre tu lugar de estudio!</h3>


        <form id="instalacionesForm">
            <label for="universidad">Universidad:</label>
            <select name="universidad" id="universidad" onchange="showOtherField('universidad', 'request-universidad', true)">
                <?php
                include_once '../business/universidadBusiness.php';
                $universidadBusiness = new UniversidadBusiness();
                $universidades = $universidadBusiness->getAllTbUniversidad();

                if ($universidades != null) {
                    foreach ($universidades as $universidad) {
                        echo '<option value="' . htmlspecialchars($universidad->getTbUniversidadNombre()) . '">' . htmlspecialchars($universidad->getTbUniversidadNombre()) . '</option>';
                    }
                    echo '<option value="0" title="Solicitar otra universidad a los administradores">Otra</option>';
                }
                ?>
            </select>
            <br>
            <label for="campus">Campus:</label>
            <select name="campus" id="campus" onchange="showOtherField('campus', 'request-campus', false)">
                <?php
                include_once '../business/campusBusiness.php';
                $campusBusiness = new CampusBusiness();
                $campus = $campusBusiness->getAllTbCampus();

                if ($campus != null) {
                    foreach ($campus as $camp) {
                        echo '<option value="' . htmlspecialchars($camp->getTbCampusNombre()) . '">' . htmlspecialchars($camp->getTbCampusNombre()) . '</option>';
                    }
                    echo '<option value="0" title="Solicitar otro campus a los administradores">Otro</option>';
                }
                ?>

            </select>
            <br>
            <label for="colectivos">Colectivos:</label>
            <?php
            include_once '../business/universidadCampusColectivoBusiness.php';

            $campusColectivoBusiness = new UniversidadCampusColectivoBusiness();
            $campusColectivos = $campusColectivoBusiness->getColectivosByCampusId($camp->getTbCampusId());
            $allColectivos = $campusColectivoBusiness->getAllTbUniversidadCampusColectivo();

            $colectivosSeleccionados = array_map(function ($colectivo) {
                return $colectivo->getTbUniversidadCampusColectivoId();
            }, $campusColectivos);


            echo '<select name="colectivos[]" id="colectivos" multiple class="form-control">';
            foreach ($allColectivos as $colectivo) {
                $idColectivo = $colectivo->getTbUniversidadCampusColectivoId();
                $descripcion = $colectivo->getTbUniversidadCampusColectivoDescripcion();
                $estado = $colectivo->getTbUniversidadCampusColectivoEstado();
                $nombreColectivo = $colectivo->getTbUniversidadCampusColectivoNombre();

                if ($estado == 1 || ($estado == 0 && in_array($idColectivo, $colectivosSeleccionados) && $descripcion == "1")) {
                    $selected = in_array($idColectivo, $colectivosSeleccionados) ? 'selected' : '';
                    echo '<option value="' . htmlspecialchars($nombreColectivo) . '" ' . $selected . '>' . htmlspecialchars($nombreColectivo) . '</option>';
                }
            }
            echo '</select>';
            ?>
            <br>
            <button type="button" onclick="if(validateAndProceed('request-universidadNombre', true, 'request-universidad')) hideFields('request-universidadNombre', 'request-campusNombre', 'request-universidad', 'request-campus', 'request-universidad-form', 'request-campus-form', 5)">Siguiente</button>
        </form>
    </div>

    <div id="form5" class="form-container">
        <div id="container">
            <button onclick="window.location.href='../view/userNavigateView.php';">Volver</button>
            <h3>Modela tu perfil</h3>

            <div id="loading" style="display:none;">Cargando...</div>

            <form id="criteriaForm" method="post" action="../action/personalProfileAction.php" onsubmit="return submitForm()">
                <div id="criteriaSection">

                    <div class="criterion">
                        <label for="criterion1">Criterio:</label>
                        <input type="text" name="criterion[]" id="criterion1" placeholder="Especifique el criterio" oninput="actualizarTablaConCriterio()">

                        <label for="value1">Prefiero:</label>
                        <input type="text" name="value[]" id="value1" placeholder="Especifique el valor" oninput="actualizarTablaConCriterio()">

                        <button type="button" onclick="removeCriterion(this)">Eliminar</button>
                    </div>

                </div>

                <input type="hidden" id="criteriaString" name="criteriaString">
                <input type="hidden" id="valuesString" name="valuesString">
                <button type="button" onclick="addCriterion()">Agregar criterio</button>

            </form>

            <hr><br>
            <!-- Acá empieza el ordenamiento tipo árbol -->
            <h3>Define el orden de los criterios de búsqueda</h3>
            <form id="ordenForm" method="post" action="../action/personalProfileAction.php" onsubmit="return submitOrden()">
                <table id="sortableTable">
                    <thead>
                        <tr>
                            <th>Criterio</th>
                            <th>Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Las filas se cargarán aquí mediante JavaScript -->
                    </tbody>
                </table>
                <button type="button" onclick="submitAll()">Enviar Todo</button>
            </form>
            <!-- Acá termina el ordenamiento tipo árbol -->
        </div>
    </div>


    <div id="request-universidad" style="display:none;">
        <form id="request-universidad-form" onsubmit="submitRequest(event, 'request-universidad-form')" style="width: 50vw; min-width:300px;">
            <input type="text" id="request-universidadNombre" name="request-universidadNombre" placeholder="Especifique otra universidad">
            <button type="submit" class="btn btn-success" name="request-universidad-btn" id="request-universidad-btn">Solicitar</button>
        </form>
    </div>

    <div id="request-campus" style="display:none;">
        <form id="request-campus-form" onsubmit="submitRequest(event, 'request-campus-form')" style="width: 50vw; min-width:300px;">
            <input type="hidden" name="idUniversidad" id="idUniversidad">
            <input type="text" id="request-campusNombre" name="request-campusNombre" placeholder="Especifique otro campus">
            <button type="submit" class="btn btn-success" name="request-campus-btn" id="request-campus-btn">Solicitar</button>
        </form>
    </div>

    <div id="request-genero" style="display:none;">
        <form id="request-genero-form" onsubmit="submitRequest(event, 'request-genero-form')" style="width: 50vw; min-width:300px;">
            <input type="text" name="request-generoNombre" id="request-generoNombre" placeholder="Especifique otro género" required>
            <button type="submit" class="btn btn-success" name="request-genero-btn" id="request-genero-btn">Solicitar</button>
        </form>
    </div>

    <div id="request-orientacionSexual" style="display:none;">
        <form id="request-orientacionSexual-form" onsubmit="submitRequest(event, 'request-orientacionSexual-form')" style="width: 50vw; min-width:300px;">
            <input type="text" id="request-orientacionSexualNombre" name="request-orientacionSexualNombre" placeholder="Especifique otra orientación sexual">
            <button type="submit" class="btn btn-success" name="request-orientacionSexual-btn" id="request-orientacionSexual-btn">Solicitar</button>
        </form>
    </div>

    <div id="request-areaConocimiento" style="display:none;">
        <form id="request-areaConocimiento-form" onsubmit="submitRequest(event, 'request-areaConocimiento-form')" style="width: 50vw; min-width:300px;">
            <input type="text" id="request-areaConocimientoNombre" name="request-areaConocimientoNombre" placeholder="Especifique otra área de conocimiento">
            <button type="submit" class="btn btn-success" name="request-areaConocimiento-btn" id="request-areaConocimiento-btn">Solicitar</button>
        </form>
    </div>

    <script src="../js/profileModel.js"></script>
</body>

</html>