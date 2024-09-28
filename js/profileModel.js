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
<<<<<<< Updated upstream
        <select name="criterion[]" id="criterion${criteriaCount}" onchange="loadValues(this, ${criteriaCount})" disabled>
=======
        <select name="criterion[]" id="criterion${criteriaCount}" onchange="loadValues(this, ${criteriaCount})">
>>>>>>> Stashed changes
            <!-- Las opciones de criterios se cargarán dinámicamente -->
        </select>

        <label for="value${criteriaCount}">Prefiero:</label>
        <select name="value[]" id="value${criteriaCount}" onchange="toggleOtherField(this, ${criteriaCount})" disabled>
            <!-- Las opciones de valores se cargarán dinámicamente -->
        </select>
        <input type="text" id="otherField${criteriaCount}" name="otherValue[]" style="display: none;" placeholder="Especifique otro valor" oninput="actualizarTablaConCriterio()">
<<<<<<< Updated upstream
        
        ${currentView === 'PersonalProfile' ? '' : `
        <!-- <label for="percent${criteriaCount}">Porcentaje:</label>-->
        <!--<input type="number" id="percent${criteriaCount}" name="percentage[]" min="0" max="100" oninput="updateTotalPercentage()">
        -->`}
=======

>>>>>>> Stashed changes
        <button type="button" onclick="removeCriterion(this)">Eliminar</button>
    `;

    criteriaSection.appendChild(newCriterion);

    // Llamar a la función para cargar los criterios en el nuevo select
    populateCriteria(`criterion${criteriaCount}`);

    // Cargar automáticamente los valores del primer criterio
    const nuevoCriterioSelect = document.getElementById(`criterion${criteriaCount}`);
    
    if (nuevoCriterioSelect && nuevoCriterioSelect.options.length > 0) {
        // Asigna el primer criterio disponible y carga los valores correspondientes
        nuevoCriterioSelect.selectedIndex = 0;
        loadValues(nuevoCriterioSelect, criteriaCount);  // Cargar valores automáticamente para el criterio seleccionado
    } else {
        console.error(`Error: No se encontró el select para el criterio ${criteriaCount}`);
    }
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

<<<<<<< Updated upstream
=======
// Función para cargar los valores basados en el criterio seleccionado desde un archivo .dat
function loadValues(select, index) {
    const criterionId = select.value;  // Obtén el criterio seleccionado en el combobox
    const valueSelect = document.getElementById(`value${index}`);  // El select de valores correspondiente

    console.log(`Cargando valores para el criterio: ${criterionId}, en el combobox de valores: value${index}`);

    if (!valueSelect) {
        console.error(`Elemento select de valores no encontrado para el índice ${index}`);
        return;
    }

    // Limpiar el contenido del select antes de agregar nuevos valores
    valueSelect.innerHTML = '';

    // Si no hay criterio seleccionado, deshabilitar el select
    if (!criterionId) {
        valueSelect.disabled = true;
        return;
    }

    // Realizar la solicitud para obtener los valores del criterio seleccionado
    fetch(`../action/getCriteriosValoresAction.php?type=valores&criterio=${criterionId}`)
        .then(response => response.json())
        .then(valoresData => {
            console.log(`Valores recibidos para el criterio ${criterionId}:`, valoresData);

            // Comprobar si los valores están disponibles
            if (!valoresData || !Array.isArray(valoresData) || valoresData.length === 0) {
                const option = document.createElement('option');
                option.textContent = 'No hay valores disponibles';
                valueSelect.appendChild(option);
                valueSelect.disabled = true;
                return;
            }

            // Poblar el select de valores con los datos recibidos
            valoresData.forEach(valor => {
                const option = document.createElement('option');
                option.value = valor;
                option.textContent = valor;
                valueSelect.appendChild(option);
            });

            // Seleccionar automáticamente el primer valor
            valueSelect.selectedIndex = 0;
            valueSelect.disabled = false;  // Habilitar el select de valores
        })
        .catch(error => {
            console.error('Error al cargar los valores:', error);
        });
}

>>>>>>> Stashed changes
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

/// Función para cargar criterios desde archivos .dat a través de PHP
async function loadInitialCriteriaData() {
    try {
        const response = await fetch('../action/getCriteriosValoresAction.php?type=criterios');
        if (!response.ok) throw new Error('Error en la respuesta del servidor');

        const criteriosData = await response.json();
        if (criteriosData.length > 0) {
            criterios = criteriosData;  // Asignar los criterios obtenidos
            populateCriteria('criterion1');  // Poblar el primer criterio

            // Cargar los valores del primer criterio automáticamente al cargar los criterios
            const primerCriterio = document.getElementById('criterion1');
            if (primerCriterio) {
                loadValues(primerCriterio, 1);  // Llamar a la función para cargar los valores
            }
        } else {
            console.warn('No se recibieron criterios.');
        }
    } catch (error) {
        console.error('Error al cargar datos iniciales de criterios:', error);
    }
}


async function loadInitialValuesData() {
    try {
        console.log("Iniciando la solicitud para obtener los valores...");  // Depuración
        
        const criterio = document.getElementById('criterion1').value;  // Obtener el valor del criterio
        if (!criterio) {
            console.error("Criterio no definido");
            return;  // Salir si no hay criterio definido
        }

        // Hacer la solicitud fetch con el criterio seleccionado
        const response = await fetch(`../action/getCriteriosValoresAction.php?type=valores&criterio=${criterio}`);
        console.log('Respuesta del servidor obtenida:', response);  // Verificar si la solicitud fetch devuelve algo

        if (!response.ok) {
            console.error('Error en la respuesta del servidor:', response.statusText);
            return;
        }

        // Obtener los datos en formato JSON
        const valoresData = await response.json();
        console.log('Datos JSON obtenidos del servidor:', valoresData);  // Verificar los datos

        // Comprobar si los valores recibidos son válidos
        if (!valoresData || !Array.isArray(valoresData) || valoresData.length === 0) {
            console.warn('No se recibieron valores válidos. Datos de la respuesta:', valoresData);
            return;
        }

        valores = valoresData;  // Asignar los valores recibidos
        console.log('Valores cargados correctamente:', valores);
    } catch (error) {
        console.error('Error al cargar datos iniciales de valores:', error);
    }
}

// Función para cargar los criterios en el select
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

    // Añadir criterios al select
    criterios.forEach(criterio => {
        const option = document.createElement('option');
        option.value = criterio;
        option.textContent = criterio;
        select.appendChild(option);
    });

<<<<<<< Updated upstream
=======
    // Añadir la opción "Otro" al final
    const otherOption = document.createElement('option');
    otherOption.value = 'other';
    otherOption.textContent = 'Otro';
    select.appendChild(otherOption);

>>>>>>> Stashed changes
    select.disabled = false;

    // Llamar a loadValues() directamente después de que los criterios estén cargados
    loadValues(select, selectId.replace('criterion', ''));
}

<<<<<<< Updated upstream
// Función para cargar los valores basados en el criterio seleccionado
function loadValues(select, index) {
    const criterionId = select.value;
=======
// Función para cargar los valores basados en el criterio seleccionado desde un archivo .dat
async function loadInitialValuesData() {
    try {
        console.log("Iniciando la solicitud para obtener los valores...");

        const criterio = document.getElementById('criterion1').value;  // Asegúrate de obtener el criterio correcto
        const response = await fetch(`../action/getCriteriosValoresAction.php?type=valores&criterio=${criterio}`);
>>>>>>> Stashed changes

        console.log('Respuesta del servidor obtenida:', response);

        if (!response.ok) {
            console.error('Error en la respuesta del servidor:', response.statusText);
            return;
        }

        const valoresData = await response.json();
        console.log('Datos JSON obtenidos del servidor:', valoresData);

        if (!valoresData || !Array.isArray(valoresData) || valoresData.length === 0) {
            console.warn('No se recibieron valores válidos. Datos de la respuesta:', valoresData);
            return;
        }

        valores = valoresData;  // Asigna los valores recibidos
        console.log('Valores cargados correctamente:', valores);

        // Aquí llamas a loadValues para cargar los valores en el combobox
        loadValues(document.getElementById('criterion1'), 1);

    } catch (error) {
        console.error('Error al cargar datos iniciales de valores:', error);
    }
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
    }else{
        await cargarPerfilDeseado();
    }
    

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