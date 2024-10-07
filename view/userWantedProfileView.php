<?php
include "../action/sessionUserAction.php";
?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
    <title>LUV Perfil Deseado</title>

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
</head>

<body data-view="WantedProfile">
    <section id="alerts">

        <?php

        if (isset($_GET['error'])) {
            $mensaje = "Ocurrió un error debido a ";
            $mensaje .= match (true) {
                $_GET['error'] == "percentageIncomplete" => "debe distribuir un 100% entre los criterios.",
                $_GET['error'] == "formIncomplete" => "problemas en el procesamiento de su respuesta.",
                $_GET['error'] == "noProfiles" => "que no hay perfiles registrados en este momento.",
                $_GET['error'] == "percetageCal" => "problemas al procesar su modelo deseado.",
                default => "un problema inesperado.",
            };
        } else if (isset($_GET['success'])) {
            $mensaje = match (true) {
                $_GET['success'] == "inserted" => "Se ha guardado el modelo de persona que buscas.",
                default => "Transacción realizada.",
            };
        }

        if (isset($mensaje)) {
            echo "<script>alert('$mensaje')</script>";
        }
        ?>

    </section>

    <div id="container">
        <button onclick="window.location.href='../view/userNavigateView.php';">Volver</button>
        <h3>Modela lo que estás buscando</h3>

        <form id="criteriaForm" method="post" action="../action/wantedProfileAction.php" onsubmit="return submitForm()">
            <div id="criteriaSection">
                <div class="criterion">
                    <label for="criterion1">Criterio:</label>
                    <input type="text" name="criterion[]" id="criterion1" placeholder="Especifique el criterio" oninput="actualizarTablaConCriterio()">

                    <label for="value1">Prefiero:</label>
                    <input type="text" name="value[]" id="value1" placeholder="Especifique el valor" oninput="actualizarTablaConCriterio()">

                    <button type="button" onclick="removeCriterion(this)">Eliminar</button>
                </div>

            </div>

            <button type="button" onclick="addCriterion()">Agregar criterio</button>

            <!-- <p id="totalPercentageDisplay">Porcentaje total: 0%</p> -->

            <!-- Campos ocultos para guardar los strings formateados -->
            <!-- <input type="hidden" id="totalPercentageInp" name="totalPercentageInp"> -->
            <input type="hidden" id="criteriaString" name="criteriaString">
            <input type="hidden" id="valuesString" name="valuesString">
            <!-- <input type="hidden" id="percentagesString" name="percentagesString"> -->

            <!-- <button type="submit" name="search">Buscar</button> -->

        </form>
        <hr><br>
        <!-- Acá empieza el ordenamiento tipo árbol -->
         <h3>Define el orden de los criterios de búsqueda</h3>
        <form id="ordenForm" method="post" action="../action/wantedProfileAction.php" onsubmit="return submitOrden()">
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
            <button type="submit" name="search">Buscar</button>
        </form>
        <!-- Acá termina el ordenamiento tipo árbol -->


    </div>
    <script src="../js/profileModel.js"></script>
</body>

</html>