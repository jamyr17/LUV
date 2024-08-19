<?php


include '../bussiness/universidadBussiness.php';
$universidadBusiness = new UniversidadBusiness();
include '../bussiness/campusBussiness.php';
$campusBusiness = new CampusBusiness();
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>LUV</title>
        <script>
            function showField() {
                var selectedOption = document.getElementById("idOptions").value;
                var fields = document.querySelectorAll('.field');

                fields.forEach(function(field) {
                    field.style.display = 'none';
                });
                if (selectedOption === "Universidad") {
                    document.getElementById("universidad-field").style.display = "block";
                } else if (selectedOption === "Área conocimiento") {
                    document.getElementById("areaconocimiento-field").style.display = "block";
                } else if (selectedOption === "Género") {
                    document.getElementById("genero-field").style.display = "block";
                } else if (selectedOption === "Orientación sexual") {
                    document.getElementById("orientacionsexual-field").style.display = "block";
                } else if (selectedOption === "Campus") {
                    document.getElementById("campus-field").style.display = "block";
                }
            }
        </script>
    </head>

    <body>

        <header>
            <nav class="navbar bg-body-tertiary">
            </nav>
        </header>

        <div class="container mt-3">

            <section id="alerts"></section>

            <section id="form">
                <div class="container">
                    <div class="container d-flex justify-content-center">
                        <label for="idOptions">Opciones:</label>
                        <select id="idOptions" name="idOptions" onchange="showField()">
                            <option value="Área conocimiento">Área conocimiento</option>
                            <option value="Género">Género</option>
                            <option value="Universidad">Universidad</option>
                            <option value="Orientación sexual">Orientación sexual</option>
                            <option value="Campus">Campus</option>
                        </select>
                    </div>

                    <div id="universidad-field" class="field" style="display: none;">
                        <form method="post" action="../bussiness/universidadAction.php" style="width: 50vw; min-width:300px;">
                            <?php
                            $universidades = $universidadBusiness->getAllTbUniversidad();
                            echo '<label for="universidad">Seleccione su universidad: </label>';
                            echo '<select name="universidad" id="universidad">';
                            foreach ($universidades as $universidad) {
                                echo '<option value="' . htmlspecialchars($universidad->getTbUniversidadId()) . '"> ' . htmlspecialchars($universidad->getTbUniversidadNombre()) . '</option>';
                            }
                            echo '</select>';
                            ?>
                        </form>
                    </div>

                    <div id="areaconocimiento-field" class="field" style="display: none;">
                        <form method="post" action="../bussiness/areaConocimientoAction.php" style="width: 50vw; min-width:300px;">
                            <?php
                            include '../bussiness/areaConocimientoBussiness.php';
                            $areaConocimientodBusiness = new AreaConocimientoBussiness();
                            $areasConocimiento = $areaConocimientodBusiness->getAllTbAreaConocimiento();

                            echo '<label for="areaconocimiento">Seleccione su área de conocimiento: </label>';
                            echo '<select name="areaconocimiento" id="areaconocimiento">';
                            foreach ($areasConocimiento as $areaconocimiento) {
                                echo '<option value="' . htmlspecialchars($areaconocimiento->getTbAreaConocimientoId()) . '"> ' . htmlspecialchars($areaconocimiento->getTbAreaConocimientoNombre()) . '</option>';
                            }
                            echo '</select>';
                            ?>
                        </form>
                    </div>

                    <div id="genero-field" class="field" style="display: none;">
                        <form method="post" action="../bussiness/generoAction.php" style="width: 50vw; min-width:300px;">
                            <?php
                            include '../bussiness/generoBusiness.php';
                            $generoBusiness = new GeneroBusiness();
                            $generos = $generoBusiness->getAllTbGenero();

                            echo '<label for="genero">Seleccione su género: </label>';
                            echo '<select name="genero" id="genero">';
                            foreach ($generos as $genero) {
                                echo '<option value="' . htmlspecialchars($genero->getTbGeneroId()) . '"> ' . htmlspecialchars($genero->getTbGeneroNombre()) . '</option>';
                            }
                            echo '</select>';
                            ?>
                        </form>
                    </div>

                    <div id="orientacionsexual-field" class="field" style="display: none;">
                        <form method="post" action="../bussiness/orientacionSexualAction.php" style="width: 50vw; min-width:300px;">
                            <?php
                            include '../bussiness/orientacionSexualBussiness.php';
                            $orientacionSexualBusiness = new OrientacionSexualBusiness();
                            $orientacionesSexuales = $orientacionSexualBusiness->getAllTbOrientacionSexual();

                            echo '<label for="orientacionsexual">Seleccione su orientación sexual: </label>';
                            echo '<select name="orientacionsexual" id="orientacionsexual">';
                            foreach ($orientacionesSexuales as $orientacionSexual) {
                                echo '<option value="' . htmlspecialchars($orientacionSexual->getTbOrientacionSexualId()) . '"> ' . htmlspecialchars($orientacionSexual->getTbOrientacionSexualNombre()) . '</option>';
                            }
                            echo '</select>';
                            ?>
                        </form>
                    </div>

                    <div id="campus-field" class="field" style="display: none;">
                        <form method="post" action="../bussiness/campusAction.php" style="width: 50vw; min-width:300px;">
                            <?php
                            include '../bussiness/campusBusiness.php';
                            $campusBusiness = new CampusRegionBusiness();
                            $campus = $campus->getAllTbCampus();

                            echo '<label for="campusRegion">Seleccione su campus por región: </label>';
                            echo '<select name="campusRegion" id="campusRegion">';
                            foreach ($campus as $campus) {
                                echo '<option value="' . htmlspecialchars($campus->getTbCampusRegionId()) . '"> ' . htmlspecialchars($campus->getTbCampusRegionNombre()) . '</option>';
                            }
                            echo '</select>';
                            ?>
                        </form>
                    </div>


                </div>
            </section>
        </div>

    </body>

</html>
