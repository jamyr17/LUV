<?php
include "../action/sessionUserAction.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LUV Perfil Deseado</title>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
</head>

<body data-view="WantedProfile">

    <section id="alerts">
        <?php
        if (isset($_GET['error'])) {
            $mensaje = "Ocurrió un error: ";
            $mensaje .= match ($_GET['error']) {
                "emptyFields" => "Los criterios o valores no deben estar vacíos.",
                "percentageIncomplete" => "Debe distribuir un 100% entre los criterios.",
                "formIncomplete" => "Problemas en el procesamiento de su respuesta.",
                "noProfiles" => "No hay perfiles registrados en este momento.",
                "percetageCal" => "Problemas al procesar su modelo deseado.",
                default => "Un problema inesperado.",
            };
            echo "<script>alert('$mensaje')</script>";
        } else if (isset($_GET['success'])) {
            $mensaje = match ($_GET['success']) {
                "inserted" => "Se ha guardado el modelo de persona que buscas.",
                default => "Transacción realizada.",
            };
            echo "<script>alert('$mensaje')</script>";
        }
        ?>
    </section>


    <div id="container">
        <button onclick="window.location.href='../view/userNavigateView.php';">Volver</button>
        <h3>Modela lo que estás buscando</h3>
        <form id="mainForm" method="post" action="../action/wantedProfileAction.php" onsubmit="return submitForm()">

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

        <!-- Campos ocultos para enviar los criterios y valores -->
        <input type="hidden" id="criteriaString" name="criteriaString">
        <input type="hidden" id="valuesString" name="valuesString">

        <hr><br>

        <h3>Define el orden de los criterios de búsqueda</h3>
        <table id="sortableTable">
            <thead>
                <tr>
                    <th>Criterio</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>

        <button type="submit" name="search">Buscar</button>
        </form>

    </div>
    <script src="../js/profileModel.js"></script>
</body>
</html>
