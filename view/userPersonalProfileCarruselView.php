<?php
include "../action/sessionUserAction.php";
include '../action/functions.php';
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
            nextForm(formNumber);
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

            finalFormData.append('registrar', 'true');

            fetch('../action/personalProfileAction.php', {
                    method: 'POST',
                    body: finalFormData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (data.success === "updated") {
                            alert("Se ha actualizado su perfil personal.");
                        } else {
                            alert("Se ha guardado el perfil personal.");
                        }
                        window.location.href = '../view/userWantedProfileView.php';
                    } else {
                        window.location.href = '../view/userPersonalProfileCarruselView.php?error=' + data.error;

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
                            $('#campus').append(new Option(camp.nombre, camp.id));
                        });
                        $('#campus').append(new Option('Otro', '0'));

                        if (data.length > 0) {
                        $('#campus').val(data[0].id).change(); // Seleccionar el primer campus y disparar el evento
                    }
                    },
                    error: function(err) {
                        console.error('Error al cargar campus:', err);
                    }
                });
            });

            $('#campus').change(function() {
                const campusId = $(this).val();
                $.ajax({
                    url: '../data/getData.php',
                    method: 'GET',
                    data: {
                        campusId: campusId
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
            <button type="button" onclick="hideField('request-generoNombre', 'request-genero', 'request-genero-form', 'generoForm', 2)">Siguiente</button>
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
            <button type="button" onclick="hideField('request-orientacionSexualNombre', 'request-orientacionSexual', 'request-orientacionSexual-form', 'orientacionSexualForm', 3)">Siguiente</button>
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
            <button type="button" onclick="hideField('request-areaConocimientoNombre', 'request-areaConocimiento', 'request-areaConocimiento-form', 'areaConocimientoForm', 4)">Siguiente</button>
        </form>
    </div>

    <div id="form4" class="form-container">
        <h2>Tus instalaciones</h2>
        <h3>Cuéntale a otros sobre tu lugar de estudio!</h3>

        <form id="instalacionesForm">
            <label for="universidad">Universidad:</label>
            <select name="universidad" id="universidad" onchange="showOtherField('universidad', 'request-universidad', true)">
                <?php
                include '../business/universidadBusiness.php';
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
            <button type="button" onclick="saveData('instalacionesForm',5)">Siguiente</button>
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
                        <select name="criterion[]" id="criterion1" onchange="loadValues(this, 1)">
                            <!-- Las opciones de criterios se cargarán dinámicamente -->
                        </select>

                        <label for="value1">Prefiero:</label>
                        <select name="value[]" id="value1" onchange="toggleOtherField(this, 1)">
                            <!-- Las opciones de valores se cargarán dinámicamente -->
                        </select>

                        <input type="text" id="otherField1" name="otherValue[]" style="display: none;" placeholder="Especifique otro valor">
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
            <input type="text" name="request-generoNombre" id="request-generoNombre" placeholder="Especifique otro género">
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