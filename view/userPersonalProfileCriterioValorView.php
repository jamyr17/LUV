<?php
include "../action/sessionUserAction.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
    <title>LUV Perfil personal</title>
    
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
</head>
<body data-view="PersonalProfile">
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
                $_GET['success']=="updated" => "Se ha actualizado su perfil personal.",
                default => "Transacción realizada.",
            };

            echo "<script>
                alert('$mensaje');
                document.addEventListener('DOMContentLoaded', function() {
                    var continuarBtn = document.createElement('button');
                    continuarBtn.innerHTML = 'Buscar perfiles';
                    continuarBtn.onclick = function() {
                        window.location.href = '../view/userWantedProfileView.php';
                    };
                    document.body.appendChild(continuarBtn);
                });
            </script>";
        }

        if(isset($mensaje) && !isset($_GET['success'])) {
            echo "<script>alert('$mensaje')</script>";
        }
        ?>
    </section>

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
            <button type="submit" name="registrar">Enviar</button>
        </form>
        <!-- Acá termina el ordenamiento tipo árbol -->
    </div>

    <script src="../js/profileModel.js"></script>
</body>
</html>
