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
        <input type="text" id="otherField${criteriaCount}" name="otherValue[]" style="display: none;" placeholder="Especifique otro valor" oninput="actualizarTablaConCriterio()">
        
        ${currentView === 'PersonalProfile' ? '' : `
        <!-- <label for="percent${criteriaCount}">Porcentaje:</label>-->
        <!--<input type="number" id="percent${criteriaCount}" name="percentage[]" min="0" max="100" oninput="updateTotalPercentage()">
        -->`}
        <button type="button" onclick="removeCriterion(this)">Eliminar</button>
    `;
    criteriaSection.appendChild(newCriterion);

    populateCriteria(`criterion${criteriaCount}`);

    const select = document.getElementById(`criterion${criteriaCount}`);
    loadValues(select, criteriaCount);

    actualizarTablaConCriterio();
}

function actualizarTablaConCriterio() {
    const tbody = document.querySelector('#sortableTable tbody');
    tbody.innerHTML = ''; // Limpiar la tabla antes de llenarla nuevamente

    const criteriaSelects = document.querySelectorAll('select[name="criterion[]"]');
    const valuesSelects = document.querySelectorAll('select[name="value[]"]');
    const otherValues = document.querySelectorAll('input[name="otherValue[]"]');

    criteriaSelects.forEach((select, index) => {
        const criterionValue = select.options[select.selectedIndex]?.text || 'Sin criterio';
        const valueSelect = valuesSelects[index];
        const otherValueInput = otherValues[index];

        let valueValue = valueSelect.options[valueSelect.selectedIndex]?.text || 'Sin valor';

        if (valueSelect.value === 'other') {
            valueValue = otherValueInput.value || 'Sin valor especificado';
        }

        const tr = document.createElement('tr');
        tr.dataset.id = select.value; // Asegúrate de que el valor del select sea el ID del criterio
        tr.innerHTML = `
            <td>${criterionValue}</td>
            <td>${valueValue}</td>
        `;
        tbody.appendChild(tr);
    });
}

async function guardarNuevoOrden() {
    const rows = Array.from(document.querySelectorAll('#sortableTable tbody tr'));
    const nuevoOrden = rows.map(row => {
        return {
            criterion: row.children[0].textContent,
            value: row.children[1].textContent
        };
    });

    try {
        const response = await fetch('../action/wantedProfileAction.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ updateOrder: nuevoOrden }),
        });

        if (response.ok) {
            const responseText = await response.text(); 
            try {
                const jsonResponse = JSON.parse(responseText); 
                console.log('Orden guardado exitosamente: ', jsonResponse);
            } catch (jsonError) {
                console.error('Error al parsear JSON:', jsonError);
                console.log('Respuesta del servidor:', responseText);
            }
        } else {
            console.error('Error al guardar el orden. Estado:', response.status);
        }
    } catch (error) {
        console.error('Error de red:', error);
    }
}

// función parra poder eliminar criterios
function removeCriterion(button) {
    const criterionToRemove = button.parentNode;

    const criteriaSection = document.getElementById('criteriaSection');
    const criteria = criteriaSection.getElementsByClassName('criterion');

    if (criteria.length > 1) {
        criterionToRemove.remove();
        actualizarTablaConCriterio();
        guardarNuevoOrden();

        // Recalcular el total del porcentaje si estamos en la vista 'WantedProfile'
        if (currentView !== 'PersonalProfile') {
            updateTotalPercentage();
        }
    } else {
        alert('Debe haber al menos un criterio.');
    }
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

    actualizarTablaConCriterio();
}

// Función para inicializar el autocompletado en el campo de texto "Otro"
async function initializeAutocomplete(input, criterionName) {
    try {
        const response = await fetch(`../data/getData.php?criterion=${criterionName}`);
        const suggestions = await response.json();

        $(input).autocomplete({
            source: suggestions  // Usar las sugerencias para el autocompletado
        });
    } catch (error) {
        console.error('Error al obtener sugerencias:', error);
    }
}

// Modificar la función toggleOtherField para inicializar el autocompletado
function toggleOtherField(select, index) {
    const otherField = document.getElementById(`otherField${index}`);
    if (select.value === 'other') {
        otherField.style.display = 'block';

        // Obtener el nombre del criterio seleccionado
        const criterionSelect = document.getElementById(`criterion${index}`);
        const criterionName = criterionSelect.selectedOptions[0].getAttribute('data-nombre');

        // Inicializar autocompletado solo si se ha seleccionado un criterio
        if (criterionName) {
            initializeAutocomplete(otherField, criterionName);
        }
    } else {
        otherField.style.display = 'none';
        otherField.value = '';
    }

    actualizarTablaConCriterio();
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
    // const percentages = document.querySelectorAll('input[name="percentage[]"]');
    const otherValues = document.querySelectorAll('input[name="otherValue[]"]');

    let criteriaString = '';
    let valuesString = '';
    // let percentagesString = '';

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

        // percentagesString += percentages[i] ? percentages[i].value : '';

        if (i < criteria.length - 1) {
            criteriaString += ',';
            valuesString += ',';
            // percentagesString += ',';
        }
    }

    document.getElementById('criteriaString').value = criteriaString;
    document.getElementById('valuesString').value = valuesString;
    // document.getElementById('percentagesString').value = percentagesString;

    return true;
}

document.addEventListener('DOMContentLoaded', async () => {
    const sortableTable = document.querySelector('#sortableTable tbody');

    // Configura SortableJS
    Sortable.create(sortableTable, {
        animation: 150,
        onEnd: function (evt) {
            // Llama a la función para guardar el nuevo orden al finalizar el arrastre
            guardarNuevoOrden();
        }
    });

    await loadInitialCriteriaData();
    await loadInitialValuesData();

    if (currentView === 'PersonalProfile'){
        await cargarPerfilPersonal();
    }
    
    await cargarPerfilDeseado();

    document.getElementById('criterion1').disabled = false;
    actualizarTablaConCriterio(); // Inicializa la tabla con los datos iniciales
});


// Nueva función para cargar el perfil personal del usuario
async function cargarPerfilPersonal() {
    if (currentView != 'PersonalProfile') return;
    try {
        const response = await fetch('../action/personalProfileAction.php'); // Asegúrate de que la ruta sea correcta
        const data = await response.json();

        if (data.error) {
            console.error('Error al cargar el perfil personal:', data.error);
            return;
        }
        

        // Cargar los criterios y valores en los combobox
        data.forEach((item, index) => {
            if (index > 0) addCriterion(); // Agregar un nuevo criterio si es el segundo o más

            const criterioSelect = document.getElementById(`criterion${index + 1}`);
            const valorSelect = document.getElementById(`value${index + 1}`);

            // Seleccionar el criterio en el combobox
            const criterioOption = Array.from(criterioSelect.options).find(option => option.text === item.criterio);
            if (criterioOption) criterioOption.selected = true;

            // Cargar los valores basados en el criterio
            loadValues(criterioSelect, index + 1);

            // Revisar si el valor existe en el combobox de valores
            let valorOption = Array.from(valorSelect.options).find(option => option.text === item.valor);

            if (!valorOption) {
                // Si el valor no existe, crearlo dinámicamente
                valorOption = new Option(item.valor, item.valor);
                valorSelect.add(valorOption);
            }

            // Seleccionar el valor en el combobox
            valorOption.selected = true;
        });

        actualizarTablaConCriterio();
    } catch (error) {
        console.error('Error al cargar el perfil personal:', error);
    }
}


async function cargarPerfilDeseado() {
    if (currentView === 'PersonalProfile') return;
    try {
        const response = await fetch('../action/wantedProfileAction.php'); // Asegúrate de que la ruta sea correcta
        const data = await response.json();

        if (data.error) {
            console.error('Error al cargar el perfil deseado:', data.error);
            return;
        }

        console.log('Data:', data);

        // Cargar los criterios y valores en los combobox
        data.forEach((item, index) => {
            if (index > 0) addCriterion(); // Agregar un nuevo criterio si es el segundo o más

            const criterioSelect = document.getElementById(`criterion${index + 1}`);
            const valorSelect = document.getElementById(`value${index + 1}`);

            // Seleccionar el criterio en el combobox
            const criterioOption = Array.from(criterioSelect.options).find(option => option.text === item.criterio);
            if (criterioOption) criterioOption.selected = true;

            // Cargar los valores basados en el criterio
            loadValues(criterioSelect, index + 1);

            // Revisar si el valor existe en el combobox de valores
            let valorOption = Array.from(valorSelect.options).find(option => option.text === item.valor);

            if (!valorOption) {
                // Si el valor no existe, crearlo dinámicamente
                valorOption = new Option(item.valor, item.valor);
                valorSelect.add(valorOption);
            }

            // Seleccionar el valor en el combobox
            valorOption.selected = true;
        });

        actualizarTablaConCriterio();
    } catch (error) {
        console.error('Error al cargar el perfil deseado:', error);
    }
}