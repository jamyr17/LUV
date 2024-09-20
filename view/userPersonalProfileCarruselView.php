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
        function nextForm(formNumber) {
            // Ocultar todos los formularios
            const forms = document.querySelectorAll('.form-container');
            forms.forEach(form => {
                form.classList.remove('active');
            });
            // Mostrar el formulario seleccionado
            document.getElementById('form' + formNumber).classList.add('active');
        }

        function submitForms() {
            const universidad = document.getElementById('universidad').value;
            const campus = document.getElementById('campus').value;
            console.log(`Universidad seleccionada: ${universidad}, Campus seleccionado: ${campus}`);
        }
    </script>
</head>

<body>

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
                        echo '<option value="' . htmlspecialchars($genero->getTbGeneroId()) . '" title="' . htmlspecialchars($genero->getTbGeneroDescripcion()) . '">' . htmlspecialchars($genero->getTbGeneroNombre()) . '</option>';
                    }
                }
                ?>
            </select>
            <button type="button" onclick="nextForm(2)">Siguiente</button>
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
                        echo '<option value="' . htmlspecialchars($orientacionSexual->getTbOrientacionSexualId()) . '" title="' . htmlspecialchars($orientacionSexual->getTbOrientacionSexualDescripcion()) . '">' . htmlspecialchars($orientacionSexual->getTbOrientacionSexualNombre()) . '</option>';
                    }
                }
                ?>
            </select>
            <button type="button" onclick="nextForm(3)">Siguiente</button>
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
                        echo '<option value="' . htmlspecialchars($areaConocimiento->getTbAreaConocimientoId()) . '" title="' . htmlspecialchars($areaConocimiento->getTbAreaConocimientoDescripcion()) . '">' . htmlspecialchars($areaConocimiento->getTbAreaConocimientoNombre()) . '</option>';
                    }
                }
                ?>
            </select>
            <button type="button" onclick="nextForm(4)">Siguiente</button>
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
                        echo '<option value="' . htmlspecialchars($universidad->getTbUniversidadId()) . '">' . htmlspecialchars($universidad->getTbUniversidadNombre()) . '</option>';
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
                        echo '<option value="' . htmlspecialchars($camp->getTbCampusId()) . '">' . htmlspecialchars($camp->getTbCampusNombre()) . '</option>';
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
                    echo '<option value="' . htmlspecialchars($idColectivo) . '" ' . $selected . '>' . htmlspecialchars($nombreColectivo) . '</option>';
                }
            }
            echo '</select>';
            ?>
            <br>
            <button type="button" onclick="nextForm(5)">Siguiente</button>
        </form>

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
                    <button type="submit" name="registrar">Enviar</button>
                    <button type="button" onclick="submitForms()">Enviar</button>
                </form>
                <!-- Acá termina el ordenamiento tipo árbol -->
            </div>
        </div>
    </div>
    <script src="../js/profileModel.js"></script>
</body>

</html>