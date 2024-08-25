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
        <select name="value[]" id="value${criteriaCount}" onchange="toggleOtherField(this, ${criteriaCount})">
            <!-- Las opciones de valores se cargarán dinámicamente -->
        </select>
        <input type="text" id="otherField${criteriaCount}" name="otherValue[]" style="display: none;" placeholder="Especifique otro valor">

        <label for="percent${criteriaCount}">Porcentaje:</label>
        <input type="number" id="percent${criteriaCount}" name="percentage[]" min="0" max="100" oninput="updateTotalPercentage()">
    `;
    criteriaSection.appendChild(newCriterion);

    // Cargar criterios para el nuevo select
    populateCriteria(`criterion${criteriaCount}`);
    
    // Cargar valores para el nuevo criterio 
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

// Función para cargar el select de criterios con datos
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
        option.setAttribute('data-nombre', criterio.name); // Agregar atributo data-nombre
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

    // Filtrar valores basados en el criterio seleccionado
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
        option.setAttribute('data-nombre', valor.name);  // Agregar atributo data-nombre
        valueSelect.appendChild(option);
    });

    // Añadir la opción "Otro" al final
    const otherOption = document.createElement('option');
    otherOption.value = 'other';
    otherOption.textContent = 'Otro';
    valueSelect.appendChild(otherOption);
}

// Para que el usuario pueda agregar un valor personalizado
function toggleOtherField(select, index) {
    const otherField = document.getElementById(`otherField${index}`);
    if (select.value === 'other') {
        otherField.style.display = 'block';
    } else {
        otherField.style.display = 'none';
        otherField.value = ''; // Limpiar el campo de texto si se oculta
    }
}

// Función para validar el formulario antes de enviarlo
function submitForm() {
    const totalPercentage = parseFloat(document.getElementById('totalPercentageInp').value);

    if (Math.abs(totalPercentage - 100) > 0.01) {
        alert('El porcentaje total debe ser 100%.');
        return false;
    }

    const criteria = document.querySelectorAll('select[name="criterion[]"]');
    const values = document.querySelectorAll('select[name="value[]"]');
    const percentages = document.querySelectorAll('input[name="percentage[]"]');
    const otherValues = document.querySelectorAll('input[name="otherValue[]"]');

    let criteriaString = '';
    let valuesString = '';
    let percentagesString = '';

    for (let i = 0; i < criteria.length; i++) {
        // Usar el atributo data-nombre para el nombre del criterio
        const selectedCriterion = criteria[i].selectedOptions[0];
        const criterionName = selectedCriterion.getAttribute('data-nombre');
        criteriaString += criterionName;

        // Usar el atributo data-nombre para el nombre del valor, o el valor del campo de texto si "Otro" está seleccionado
        if (values[i].value === 'other' && otherValues[i].value) {
            valuesString += otherValues[i].value;
        } else {
            const selectedValue = values[i].selectedOptions[0];
            const valueName = selectedValue.getAttribute('data-nombre');
            valuesString += valueName;
        }

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
