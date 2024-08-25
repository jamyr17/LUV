let criterios = [];
let valores = [];

// Función para cargar criterios y valores una sola vez
function loadInitialCriteriaData() {
    fetch('../data/getData.php?type=6')
        .then(response => response.json())
        .then(data => {
            criterios = data;
            populateCriteriaSection(); // Cargar todos los criterios en la sección de criterios
        })
        .catch(error => console.error('Error al cargar datos iniciales:', error));
}

function loadInitialValuesData() {
    fetch('../data/getData.php?type=7')
        .then(response => response.json())
        .then(data => {
            valores = data;
            criterios.forEach((criterio, index) => {
                const select = document.getElementById(`value${index + 1}`);
                loadValues(select, index + 1);
            });
        })
        .catch(error => console.error('Error al cargar datos iniciales:', error));
}

// Función para cargar todos los criterios en la vista
function populateCriteriaSection() {
    const criteriaSection = document.getElementById('criteriaSection');

    criterios.forEach((criterio, index) => {
        const criterionIndex = index + 1;
        const criterionDiv = document.createElement('div');
        criterionDiv.className = 'criterion';
        criterionDiv.innerHTML = `
            <label for="value${criterionIndex}">${criterio.name}:</label>
            <select name="value[]" id="value${criterionIndex}" onchange="toggleOtherField(this, ${criterionIndex})">
                <!-- Las opciones de valores se cargarán dinámicamente -->
            </select>
            <input type="text" id="otherField${criterionIndex}" name="otherValue[]" style="display: none;" placeholder="Especifique otro valor">
        `;
        criteriaSection.appendChild(criterionDiv);
    });
}

function loadValues(select, index) {
    const criterionId = criterios[index - 1].id;

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
        option.setAttribute('data-nombre', valor.name);  // Agregar atributo data-nombre
        valueSelect.appendChild(option);
    });

    const otherOption = document.createElement('option');
    otherOption.value = 'other';
    otherOption.textContent = 'Otro';
    valueSelect.appendChild(otherOption);
}

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
    const criteria = criterios.map(c => c.name);
    const values = document.querySelectorAll('select[name="value[]"]');
    const otherValues = document.querySelectorAll('input[name="otherValue[]"]');

    let criteriaString = criteria.join(',');
    let valuesString = '';

    for (let i = 0; i < values.length; i++) {
        if (values[i].value === 'other' && otherValues[i].value) {
            valuesString += otherValues[i].value;
        } else {
            const selectedValue = values[i].selectedOptions[0];
            const valueName = selectedValue.getAttribute('data-nombre');
            valuesString += valueName;
        }
 
        if (i < values.length - 1) {
            valuesString += ',';
        }
    }

    console.log('Criteria String:', criteriaString);
    console.log('Values String:', valuesString);

    document.getElementById('criteriaString').value = criteriaString;
    document.getElementById('valuesString').value = valuesString;

    return true;
}

document.addEventListener('DOMContentLoaded', () => {
    loadInitialCriteriaData();
    loadInitialValuesData();
});
