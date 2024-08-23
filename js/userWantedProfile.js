let criteriaCount = 1;

function addCriterion() {
    criteriaCount++;
    const criteriaSection = document.getElementById('criteriaSection');

    const newCriterion = document.createElement('div');
    newCriterion.className = 'criterion';
    newCriterion.innerHTML = `
        <label for="criterion${criteriaCount}">Criterio:</label>
        <select name="criterion[]" id="criterion${criteriaCount}">
            <option value="personalidad">Personalidad</option>
            <option value="gusto_musical">Gusto Musical</option>
            <option value="vestimenta">Vestimenta</option>
            <!-- Agrega más opciones según sea necesario -->
        </select>
        <label for="value${criteriaCount}">Valor deseado:</label>
        <input type="text" id="value${criteriaCount}" name="value[]">
        <label for="percent${criteriaCount}">Porcentaje:</label>
        <input type="number" id="percent${criteriaCount}" name="percentage[]" min="0" max="100" oninput="updateTotalPercentage()">
    `;
    criteriaSection.appendChild(newCriterion);
}

function updateTotalPercentage() {
    let total = 0;
    const percentageInputs = document.querySelectorAll('input[name="percentage[]"]');
    percentageInputs.forEach(input => {
        total += parseFloat(input.value) || 0;
    });

    document.getElementById('totalPercentageDisplay').textContent = `Porcentaje total: ${total}%`;
    document.getElementById('totalPercentageInp').value = total;
}

function submitForm() {
    const totalPercentage = parseFloat(document.getElementById('totalPercentageInp').value);
    
    if (totalPercentage !== 100) {
        alert('El porcentaje total debe ser 100%.');
        return false;
    }

    const criteria = document.querySelectorAll('select[name="criterion[]"]');
    const values = document.querySelectorAll('input[name="value[]"]');
    const percentages = document.querySelectorAll('input[name="percentage[]"]');

    let criteriaString = '';
    let valuesString = '';
    let percentagesString = '';

    for (let i = 0; i < criteria.length; i++) {
        criteriaString += criteria[i].value;
        valuesString += values[i].value;
        percentagesString += percentages[i].value;

        // Agregar comas solo si no es el último elemento
        if (i < criteria.length - 1) {
            criteriaString += ',';
            valuesString += ',';
            percentagesString += ',';
        }
    }

    // Asignar los strings concatenados a los campos ocultos
    document.getElementById('criteriaString').value = criteriaString;
    document.getElementById('valuesString').value = valuesString;
    document.getElementById('percentagesString').value = percentagesString;

    // Permitir que el formulario se envíe
    return true;
}
