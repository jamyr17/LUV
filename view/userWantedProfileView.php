<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LUV Perfil Deseado</title>
</head>
<body>
    <div id="container">
        <h3>Modela lo que estás buscando</h3>

        <form id="criteriaForm" method="post" action="../action/wantedProfileAction.php" onsubmit="return submitForm()">
            <div id="criteriaSection">
                <div class="criterion">
                    <label for="criterion1">Criterio:</label>
                    <select name="criterion[]" id="criterion1">
                        <option value="personalidad">Personalidad</option>
                        <option value="gusto_musical">Gusto Musical</option>
                        <option value="vestimenta">Vestimenta</option>
                        <!-- Agrega más opciones según sea necesario -->
                    </select>

                    <label for="value1">Valor deseado:</label>
                    <input type="text" id="value1" name="value[]">

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
        </form>
    </div>
    <script src="../js/userWantedProfile.js"></script>
</body>
</html>
