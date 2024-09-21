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
                            $('#colectivos').empty(); // Limpiar el select de colectivos
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
    </script>
</head>

<body>

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
            <select name="genero" id="genero">
                <?php
                include '../business/generoBusiness.php';
                $generoBusiness = new GeneroBusiness();
                $generos = $generoBusiness->getAllTbGenero();
                if ($generos != null) {
                    foreach ($generos as $genero) {
                        echo '<option value="' . htmlspecialchars($genero->getTbGeneroNombre()) . '" title="' . htmlspecialchars($genero->getTbGeneroDescripcion()) . '">' . htmlspecialchars($genero->getTbGeneroNombre()) . '</option>';
                    }
                }
                ?>
            </select>
            <button type="button" onclick="saveData('generoForm',2)">Siguiente</button>
        </form>
    </div>

    <div id="form2" class="form-container">
        <h2>Selecciona tu Orientación Sexual</h2>
        <form id="orientacionSexualForm">
            <label for="orientacionSexual">Orientación Sexual:</label>
            <select name="orientacionSexual" id="orientacionSexual">
                <?php
                include '../business/orientacionSexualBusiness.php';
                $orientacionSexualBusiness = new OrientacionSexualBusiness();
                $orientacionesSexuales = $orientacionSexualBusiness->getAllTbOrientacionSexual();

                if ($orientacionesSexuales != null) {
                    foreach ($orientacionesSexuales as $orientacionSexual) {
                        echo '<option value="' . htmlspecialchars($orientacionSexual->getTbOrientacionSexualNombre()) . '" title="' . htmlspecialchars($orientacionSexual->getTbOrientacionSexualDescripcion()) . '">' . htmlspecialchars($orientacionSexual->getTbOrientacionSexualNombre()) . '</option>';
                    }
                }
                ?>
            </select>
            <button type="button" onclick="saveData('orientacionSexualForm',3)">Siguiente</button>
        </form>
    </div>

    <div id="form3" class="form-container">
        <h2>Selecciona tu área de conocimiento</h2>
        <form id="areaConocimientoForm">
            <label for="areaConocimiento">Área de conocimiento:</label>
            <select name="areaConocimiento" id="areaConocimiento">
                <?php
                include '../business/areaConocimientoBusiness.php';
                $areaConocimientoBusiness = new AreaConocimientoBusiness();
                $areasConocimiento = $areaConocimientoBusiness->getAllTbAreaConocimiento();

                if ($areasConocimiento != null) {
                    foreach ($areasConocimiento as $areaConocimiento) {
                        echo '<option value="' . htmlspecialchars($areaConocimiento->getTbAreaConocimientoNombre()) . '" title="' . htmlspecialchars($areaConocimiento->getTbAreaConocimientoDescripcion()) . '">' . htmlspecialchars($areaConocimiento->getTbAreaConocimientoNombre()) . '</option>';
                    }
                }
                ?>
            </select>
            <button type="button" onclick="saveData('areaConocimientoForm',4)">Siguiente</button>
        </form>
    </div>

    <div id="form4" class="form-container">
        <h2>Tus instalaciones</h2>
        <h3>Cuéntale a otros sobre tu lugar de estudio!</h3>

        <form id="instalacionesForm">
            <label for="universidad">Universidad:</label>
            <select name="universidad" id="universidad">
                <?php
                include '../business/universidadBusiness.php';
                $universidadBusiness = new UniversidadBusiness();
                $universidades = $universidadBusiness->getAllTbUniversidad();

                if ($universidades != null) {
                    foreach ($universidades as $universidad) {
                        echo '<option value="' . htmlspecialchars($universidad->getTbUniversidadNombre()) . '">' . htmlspecialchars($universidad->getTbUniversidadNombre()) . '</option>';
                    }
                }
                ?>
            </select>
            <br>
            <label for="campus">Campus:</label>
            <select name="campus" id="campus">
                <?php
                include_once '../business/campusBusiness.php';
                $campusBusiness = new CampusBusiness();
                $campus = $campusBusiness->getAllTbCampus();

                if ($campus != null) {
                    foreach ($campus as $camp) {
                        echo '<option value="' . htmlspecialchars($camp->getTbCampusNombre()) . '">' . htmlspecialchars($camp->getTbCampusNombre()) . '</option>';
                    }
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
    <script src="../js/profileModel.js"></script>
</body>

</html>