let criteriaCount = 1;
let criterios = [];
let valores = [];

// Función para agregar un nuevo conjunto de criterios, valores y porcentajes
function addCriterion() {
    criteriaCount++;
    const criteriaSection = document.getElementById('criteriaSection');

    const newCriterion = document.createElement('div');
    newCriterion.className = 'criterion';
    newCriterion.innerHTML = `
        <label for="criterion${criteriaCount}">Criterio:</label>
        <select name="criterion[]" id="criterion${criteriaCount}" onchange="loadValues(this, ${criteriaCount})">
            <!-- Las opciones de criterios se cargarán dinámicamente -->
        </select>

        <label for="value${criteriaCount}">Prefiero:</label>
        <select name="value[]" id="value${criteriaCount}">
            <!-- Las opciones de valores se cargarán dinámicamente -->
        </select>
        
        <label for="percent${criteriaCount}">Porcentaje:</label>
        <input type="number" id="percent${criteriaCount}" name="percentage[]" min="0" max="100" oninput="updateTotalPercentage()">
    `;
    criteriaSection.appendChild(newCriterion);

    // Cargar criterios para el nuevo select
    populateCriteria(`criterion${criteriaCount}`);
    
    // Cargar valores para el nuevo criterio (debe ser llamada después de agregar el nuevo elemento)
    const select = document.getElementById(`criterion${criteriaCount}`);
    loadValues(select, criteriaCount);
}

// Función para actualizar el porcentaje total
function updateTotalPercentage() {
    let total = 0;
    const percentageInputs = document.querySelectorAll('input[name="percentage[]"]');
    percentageInputs.forEach(input => {
        total += parseFloat(input.value) || 0;
    });

    document.getElementById('totalPercentageDisplay').textContent = `Porcentaje total: ${total}%`;
    document.getElementById('totalPercentageInp').value = total;
}

// Función para cargar criterios y valores una sola vez
function loadInitialCriteriaData() {
    fetch('../data/getData.php?type=6')
        .then(response => response.json())
        .then(data => {
            criterios = data;
            
        })
        .catch(error => console.error('Error al cargar datos iniciales:', error));
}

function loadInitialValuesData() {
    fetch('../data/getData.php?type=7')
        .then(response => response.json())
        .then(data => {
            valores = data;

            const select = document.getElementById('criterion1');
            populateCriteria('criterion1'); // Cargar criterios en el primer select
            loadValues(select, 1); // Cargar valores para el primer criterio
        })
        .catch(error => console.error('Error al cargar datos iniciales:', error));
}

// Función para popular el select de criterios con datos cargados
function populateCriteria(selectId) {
    const select = document.getElementById(selectId);
    if (!select) {
        console.error(`No se encontró el select con ID ${selectId}`);
        return;
    }
    
    select.innerHTML = '';  // Limpiar opciones actuales
    if (criterios.length === 0) {
        console.warn('No hay criterios disponibles para cargar.');
        return;
    }

    criterios.forEach(criterio => {
        const option = document.createElement('option');
        option.value = criterio.id;
        option.textContent = criterio.name;
        select.appendChild(option);
    });
}

function loadValues(select, index) {
    const criterionId = select.value;

    const valueSelect = document.getElementById(`value${index}`);
    if (!valueSelect) {
        console.error(`Elemento select de valores no encontrado para el índice ${index}`);
        return;
    }

    valueSelect.innerHTML = '';  // Limpiar opciones actuales

    const filteredValues = valores.filter(valor => valor.idCriterio == criterionId);

    if (filteredValues.length === 0) {
        const option = document.createElement('option');
        option.textContent = 'No hay valores disponibles';
        valueSelect.appendChild(option);
        return;
    }

    filteredValues.forEach(valor => {
        const option = document.createElement('option');
        option.value = valor.id;
        option.textContent = valor.name;
        valueSelect.appendChild(option);
    });
}

// Función para validar el formulario antes de enviarlo
function submitForm() {
    const totalPercentage = parseFloat(document.getElementById('totalPercentageInp').value);

    if (Math.abs(totalPercentage - 100) > 0.01) {  // Permite una pequeña tolerancia
        alert('El porcentaje total debe ser 100%.');
        return false;
    }

    const criteria = document.querySelectorAll('select[name="criterion[]"]');
    const values = document.querySelectorAll('select[name="value[]"]');
    const percentages = document.querySelectorAll('input[name="percentage[]"]');

    let criteriaString = '';
    let valuesString = '';
    let percentagesString = '';

    for (let i = 0; i < criteria.length; i++) {
        criteriaString += criteria[i].value;
        valuesString += values[i].value;
        percentagesString += percentages[i].value;

        if (i < criteria.length - 1) {
            criteriaString += ',';
            valuesString += ',';
            percentagesString += ',';
        }
    }
    console.log('Criteria String:', criteriaString);
    console.log('Values String:', valuesString);
    console.log('Percentages String:', percentagesString);


    document.getElementById('criteriaString').value = criteriaString;
    document.getElementById('valuesString').value = valuesString;
    document.getElementById('percentagesString').value = percentagesString;

    return true;
}

document.addEventListener('DOMContentLoaded', () => {
    loadInitialCriteriaData();
    loadInitialValuesData();
});
