<!DOCTYPE html>
<?php
    include "../action/sessionAction.php";
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LUV Perfil Deseado</title>
</head>
<body>
    <section id="alerts">
        <?php
            if (isset($_GET['error'])) {
            $mensaje = "Ocurrió un error debido a ";
            $mensaje .= match(true){
                $_GET['error']=="percentageIncomplete" => "debe distribuir un 100% entre los criterios.",
                $_GET['error']=="formIncomplete" => "problemas en el procesamiento de su respuesta.",
                default => "un problema inesperado.",
            };
            } else if (isset($_GET['success'])) {
                $mensaje = match(true){
                $_GET['success']=="inserted" => "Se ha guardado el modelo de persona que buscas.",
                default => "Transacción realizada.",
                };
            }

            if(isset($mensaje)){
            echo "<script>alert('$mensaje')</script>";
            }
        ?>
    </section>

    <div id="container">
        <h3>Modela lo que estás buscando</h3>

        <form id="criteriaForm" method="post" action="../action/wantedProfileAction.php" onsubmit="return submitForm()">
            <div id="criteriaSection">
                <div class="criterion">
                    <label for="criterion1">Criterio:</label>
                    <select name="criterion[]" id="criterion1" onchange="loadValues(this, 1)">
                        
                    </select>

                    <label for="value1">Prefiero:</label>
                    <select name="value[]" id="value1">
                        
                    </select>

                    <label for="percent1">Porcentaje:</label>
                    <input type="number" id="percent1" name="percentage[]" min="0" max="100" oninput="updateTotalPercentage()">
                </div>
            </div>

            <button type="button" onclick="addCriterion()">Agregar criterio</button>
            <p id="totalPercentageDisplay">Porcentaje total: 0%</p>
            
            <!-- Campos ocultos para guardar los strings formateados -->
            <input type="hidden" id="totalPercentageInp" name="totalPercentageInp">
            <input type="hidden" id="criteriaString" name="criteriaString">
            <input type="hidden" id="valuesString" name="valuesString">
            <input type="hidden" id="percentagesString" name="percentagesString">
            

            <button type="submit" name="registrar">Enviar</button>
            <button type="submit" name="filtrado">Filtrar Perfiles</button>
            
            
        </form>
    </div>
    <script src="../js/userWantedProfile.js"></script>
</body>
</html>
