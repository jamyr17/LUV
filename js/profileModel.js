let criteriaCount = 1;
let criterios = [];
let valores = [];

// Determinar si es para la vista de perfil personal o perfil deseado
const currentView = document.body.getAttribute('data-view');

// Función para hacer fetch con reintento
async function fetchDataWithRetry(url, retries = 3) {
    for (let i = 0; i < retries; i++) {
        try {
            const response = await fetch(url);
            if (!response.ok) throw new Error('Error en la respuesta');
            const data = await response.json();
            return data;
        } catch (error) {
            console.error(`Error en el intento ${i + 1}: ${error.message}`);
            if (i === retries - 1) throw error;
        }
    }
}

// Función para agregar un nuevo conjunto de criterios, valores y porcentajes
function addCriterion() {
    criteriaCount++;
    const criteriaSection = document.getElementById('criteriaSection');

    const newCriterion = document.createElement('div');
    newCriterion.className = 'criterion';

    // Contenido HTML dependiendo de la vista actual
    newCriterion.innerHTML = `
        <label for="criterion${criteriaCount}">Criterio:</label>
        <select name="criterion[]" id="criterion${criteriaCount}" onchange="loadValues(this, ${criteriaCount})" disabled>
            <!-- Las opciones de criterios se cargarán dinámicamente -->
        </select>

        <label for="value${criteriaCount}">Prefiero:</label>
        <select name="value[]" id="value${criteriaCount}" onchange="toggleOtherField(this, ${criteriaCount})" disabled>
            <!-- Las opciones de valores se cargarán dinámicamente -->
        </select>
        <input type="text" id="otherField${criteriaCount}" name="otherValue[]" style="display: none;" placeholder="Especifique otro valor">
        
        ${currentView === 'PersonalProfile' ? '' : `
        <label for="percent${criteriaCount}">Porcentaje:</label>
        <input type="number" id="percent${criteriaCount}" name="percentage[]" min="0" max="100" oninput="updateTotalPercentage()">
        `}
    `;
    criteriaSection.appendChild(newCriterion);

    populateCriteria(`criterion${criteriaCount}`); 

    const select = document.getElementById(`criterion${criteriaCount}`);
    loadValues(select, criteriaCount);
}

// Función para actualizar el porcentaje total (solo para vistas que no sean PersonalProfile)
function updateTotalPercentage() {
    if (currentView === 'PersonalProfile') return; // No hacer nada si estamos en la vista PersonalProfile

    let total = 0;
    const percentageInputs = document.querySelectorAll('input[name="percentage[]"]');
    percentageInputs.forEach(input => {
        total += parseFloat(input.value) || 0;
    });

    document.getElementById('totalPercentageDisplay').textContent = `Porcentaje total: ${total}%`;
    document.getElementById('totalPercentageInp').value = total;
}

// Función para cargar criterios y valores una sola vez
async function loadInitialCriteriaData() {
    try {
        criterios = await fetchDataWithRetry('../data/getData.php?type=6');
        if (criterios.length > 0) {
            populateCriteria('criterion1');
        } else {
            console.warn('No se recibieron criterios.');
        }
    } catch (error) {
        console.error('Error al cargar datos iniciales de criterios:', error);
    }
}

async function loadInitialValuesData() {
    try {
        valores = await fetchDataWithRetry('../data/getData.php?type=7');
        if (valores.length > 0) {
            const select = document.getElementById('criterion1');
            loadValues(select, 1); // Cargar valores para el primer criterio
        } else {
            console.warn('No se recibieron valores.');
        }
    } catch (error) {
        console.error('Error al cargar datos iniciales de valores:', error);
    }
}

// Función para cargar el select de criterios con datos
function populateCriteria(selectId) {
    const select = document.getElementById(selectId);
    if (!select) {
        console.error(`No se encontró el select con ID ${selectId}`);
        return;
    }

    select.innerHTML = '';

    if (criterios.length === 0) {
        console.warn('No hay criterios disponibles para cargar.');
        const option = document.createElement('option');
        option.textContent = 'No hay criterios disponibles';
        select.appendChild(option);
        return;
    }

    criterios.forEach(criterio => {
        const option = document.createElement('option');
        option.value = criterio.id;
        option.textContent = criterio.name;
        option.setAttribute('data-nombre', criterio.name); 
        select.appendChild(option);
    });

    select.disabled = false; 
}

// Función para cargar los valores basados en el criterio seleccionado
function loadValues(select, index) {
    const criterionId = select.value;

    const valueSelect = document.getElementById(`value${index}`);
    if (!valueSelect) {
        console.error(`Elemento select de valores no encontrado para el índice ${index}`);
        return;
    }

    valueSelect.innerHTML = ''; 

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
        option.setAttribute('data-nombre', valor.name);  
        valueSelect.appendChild(option);
    });

    // Añadir la opción "Otro" al final
    const otherOption = document.createElement('option');
    otherOption.value = 'other';
    otherOption.textContent = 'Otro';
    valueSelect.appendChild(otherOption);

    valueSelect.disabled = false; 
}

// Para que el usuario pueda agregar un valor personalizado
function toggleOtherField(select, index) {
    const otherField = document.getElementById(`otherField${index}`);
    if (select.value === 'other') {
        otherField.style.display = 'block';
    } else {
        otherField.style.display = 'none';
        otherField.value = ''; 
    }
}

function submitForm() {
    if (currentView !== 'PersonalProfile') {
        const totalPercentage = parseFloat(document.getElementById('totalPercentageInp').value);

        if (Math.abs(totalPercentage - 100) > 0.01) {
            alert('El porcentaje total debe ser 100%.');
            return false;
        }
    }

    const criteria = document.querySelectorAll('select[name="criterion[]"]');
    const values = document.querySelectorAll('select[name="value[]"]');
    const percentages = document.querySelectorAll('input[name="percentage[]"]');
    const otherValues = document.querySelectorAll('input[name="otherValue[]"]');       

    let criteriaString = '';
    let valuesString = '';
    let percentagesString = '';

    for (let i = 0; i < criteria.length; i++) {
        const selectedCriterion = criteria[i].selectedOptions[0];
        const criterionName = selectedCriterion.getAttribute('data-nombre');
        criteriaString += criterionName;

        if (values[i].value === 'other' && otherValues[i].value) {
            valuesString += otherValues[i].value;
        } else {
            const selectedValue = values[i].selectedOptions[0];
            const valueName = selectedValue.getAttribute('data-nombre');
            valuesString += valueName;
        }

        percentagesString += percentages[i] ? percentages[i].value : '';

        if (i < criteria.length - 1) {
            criteriaString += ',';
            valuesString += ',';
            percentagesString += ',';
        }
    }

    document.getElementById('criteriaString').value = criteriaString;
    document.getElementById('valuesString').value = valuesString;
    document.getElementById('percentagesString').value = percentagesString;

    return true;
}

document.addEventListener('DOMContentLoaded', async () => {
    await loadInitialCriteriaData();
    await loadInitialValuesData();

    document.getElementById('criterion1').disabled = false;
});
