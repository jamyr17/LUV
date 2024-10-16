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

// Función para agregar un nuevo conjunto de criterios y valores con campos de texto
function addCriterion() {
    criteriaCount++;
    const criteriaSection = document.getElementById('criteriaSection');

    const newCriterion = document.createElement('div');
    newCriterion.className = 'criterion';

    // Contenido HTML que ahora incluye campos de texto en lugar de selects
    newCriterion.innerHTML = `
        <label for="criterion${criteriaCount}">Criterio:</label>
        <input type="text" name="criterion[]" id="criterion${criteriaCount}" placeholder="Especifique el criterio" oninput="actualizarTablaConCriterio()">

        <label for="value${criteriaCount}">Prefiero:</label>
        <input type="text" name="value[]" id="value${criteriaCount}" placeholder="Especifique el valor" oninput="actualizarTablaConCriterio()">
        
        <button type="button" onclick="removeCriterion(this)">Eliminar</button>
    `;

    criteriaSection.appendChild(newCriterion);

    guardarNuevoOrden();  // Guardar el nuevo orden
    actualizarTablaConCriterio();  // Actualizar la tabla con el nuevo criterio
}

function actualizarTablaConCriterio() {
    const tbody = document.querySelector('#sortableTable tbody');
    tbody.innerHTML = ''; // Limpiar la tabla antes de llenarla nuevamente

    const criteriaInputs = document.querySelectorAll('input[name="criterion[]"]');
    const valueInputs = document.querySelectorAll('input[name="value[]"]');

    criteriaInputs.forEach((input, index) => {
        const criterionValue = input.value || 'Sin criterio';
        const valueValue = valueInputs[index].value || 'Sin valor';

        const tr = document.createElement('tr');
        tr.dataset.id = input.value; // Usa el valor del campo de texto como ID
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

// Función para cargar los valores basados en el criterio seleccionado desde un archivo .dat
function loadValues(select, index) {
    const criterionId = select.value;
    const valueSelect = document.getElementById(`value${index}`);
    const otherCriterionField = document.getElementById(`otherCriterionField${index}`);


    if (!valueSelect) {
        console.error(`Elemento select de valores no encontrado para el índice ${index}`);
        return;
    }

    // Limpiar el select de valores
    valueSelect.innerHTML = '';

    // Si no hay criterio seleccionado, deshabilitar el select
    if (!criterionId) {
        valueSelect.disabled = true;
        return;
    }

    // Solicitar los valores del criterio seleccionado
    fetch(`../action/getCriteriosValoresAction.php?type=valores&criterio=${criterionId}`)
        .then(response => response.json())
        .then(valoresData => {
            // Si no hay valores, mostrar mensaje
            if (!valoresData || !Array.isArray(valoresData) || valoresData.length === 0) {
                const option = document.createElement('option');
                option.textContent = 'No hay valores disponibles';
                valueSelect.appendChild(option);
                valueSelect.disabled = true;
                return;
            }

            // Agregar valores al select
            valoresData.forEach(valor => {
                const option = document.createElement('option');
                option.value = valor;
                option.textContent = valor;
                valueSelect.appendChild(option);
            });

            // Agregar la opción "otro"
            const optionOtro = document.createElement('option');
            optionOtro.value = 'other';
            optionOtro.textContent = 'Otro';
            valueSelect.appendChild(optionOtro);

            // Seleccionar el primer valor automáticamente
            valueSelect.selectedIndex = 0;
            valueSelect.disabled = false;
        })
        .catch(error => {
            console.error('Error al cargar los valores:', error);
        });

        
    if (criterionId === 'other') {
        otherCriterionField.style.display = 'block';  // Mostrar el campo de texto para otro criterio
        otherField.style.display = 'block';  // Mostrar el campo de texto para otro valor
    } else {
        otherCriterionField.style.display = 'none';  // Ocultar el campo de texto
        otherField.style.display = 'none';  // Ocultar el campo de texto
    }


}

// Función para eliminar criterios
function removeCriterion(button) {
    const criterionToRemove = button.parentNode;
    const criteriaSection = document.getElementById('criteriaSection');
    
    if (criteriaSection.getElementsByClassName('criterion').length > 1) {
        criterionToRemove.remove();
        actualizarTablaConCriterio();
        guardarNuevoOrden();
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
        
        const criterio = document.getElementById('criterion1').value;  // Obtener el valor del criterio
        if (!criterio) {
            console.error("Criterio no definido");
            return;  // Salir si no hay criterio definido
        }

        // Hacer la solicitud fetch con el criterio seleccionado
        const response = await fetch(`../action/getCriteriosValoresAction.php?type=valores&criterio=${criterio}`);

        if (!response.ok) {
            console.error('Error en la respuesta del servidor:', response.statusText);
            return;
        }

        // Obtener los datos en formato JSON
        const valoresData = await response.json();

        // Comprobar si los valores recibidos son válidos
        if (!valoresData || !Array.isArray(valoresData) || valoresData.length === 0) {
            console.warn('No se recibieron valores válidos. Datos de la respuesta:', valoresData);
            return;
        }

        valores = valoresData;  // Asignar los valores recibidos
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

    // Añadir la opción "Otro" al final
    const otherOption = document.createElement('option');
    otherOption.value = 'other';
    otherOption.textContent = 'Otro';
    select.appendChild(otherOption);

    select.disabled = false;

    // Llamar a loadValues() directamente después de que los criterios estén cargados
    loadValues(select, selectId.replace('criterion', ''));
}

// Función para cargar los valores basados en el criterio seleccionado desde un archivo .dat
async function loadInitialValuesData() {
    try {

        const criterio = document.getElementById('criterion1').value;  // Asegúrate de obtener el criterio correcto
        const response = await fetch(`../action/getCriteriosValoresAction.php?type=valores&criterio=${criterio}`);


        if (!response.ok) {
            console.error('Error en la respuesta del servidor:', response.statusText);
            return;
        }

        const valoresData = await response.json();

        if (!valoresData || !Array.isArray(valoresData) || valoresData.length === 0) {
            console.warn('No se recibieron valores válidos. Datos de la respuesta:', valoresData);
            return;
        }

        valores = valoresData;  // Asigna los valores recibidos

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

// Modificar la función toggleOtherField para inicializar el autocompletado y manejar "Otro" en criterio y valor
function toggleOtherField(select, index) {
    const otherField = document.getElementById(`otherField${index}`);
    const criterionSelect = document.getElementById(`criterion${index}`);
    
    // Crear el campo de texto para el criterio si no existe
    let otherCriterionField = document.getElementById(`otherCriterionField${index}`);
    if (criterionSelect.value === 'other') {
        if (!otherCriterionField) {
            // Crear y agregar el input de "Otro" para criterio
            otherCriterionField = document.createElement('input');
            otherCriterionField.type = 'text';
            otherCriterionField.id = `otherCriterionField${index}`;
            otherCriterionField.name = `otherCriterionValue[]`;
            otherCriterionField.placeholder = 'Especifique otro criterio';
            criterionSelect.parentNode.insertBefore(otherCriterionField, criterionSelect.nextSibling);
        }
        otherCriterionField.style.display = 'block';  // Mostrar el campo de texto para el criterio
    } else if (otherCriterionField) {
        otherCriterionField.style.display = 'none';  // Ocultar el campo de texto si no es "Otro"
        otherCriterionField.value = '';  // Limpiar el valor del campo de texto
    }

    // Mostrar el campo de texto de valor si selecciona "Otro" en el valor
    if (select.value === 'other') {
        otherField.style.display = 'block';

        // Inicializar autocompletado solo si se ha seleccionado un criterio
        const criterionName = criterionSelect.selectedOptions[0]?.getAttribute('data-nombre');
        if (criterionName) {
            initializeAutocomplete(otherField, criterionName);
        }
    } else {
        otherField.style.display = 'none';
        otherField.value = '';  // Limpiar el valor del campo de texto
    }

    actualizarTablaConCriterio();
}


// Función para guardar los valores en el formulario
function submitForm() {
    const criteriaInputs = document.querySelectorAll('input[name="criterion[]"]');
    const valueInputs = document.querySelectorAll('input[name="value[]"]');

    let criteriaString = '';
    let valuesString = '';

    for (let i = 0; i < criteriaInputs.length; i++) {
        criteriaString += criteriaInputs[i].value || 'Sin criterio';
        valuesString += valueInputs[i].value || 'Sin valor';

        if (i < criteriaInputs.length - 1) {
            criteriaString += ',';
            valuesString += ',';
        }
    }

    document.getElementById('criteriaString').value = criteriaString;
    document.getElementById('valuesString').value = valuesString;

    return true;
}

document.addEventListener('DOMContentLoaded', async () => {
    const sortableTable = document.querySelector('#sortableTable tbody');

    // Configura SortableJS
    Sortable.create(sortableTable, {
        animation: 150,
        onEnd: function (evt) {
            guardarNuevoOrden();
        }
    });

    // Si es un perfil personal o deseado, cargar el perfil adecuado
    if (currentView === 'PersonalProfile'){
        await cargarPerfilPersonal();
    }else if (currentView === 'WantedProfile'){
        await cargarPerfilDeseado();
    }

    actualizarTablaConCriterio();  // Inicializa la tabla con los datos iniciales
    guardarNuevoOrden();  // Guardar el nuevo orden
});


// Nueva función para cargar el perfil personal del usuario
async function cargarPerfilPersonal() {
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
async function cargarCriteriosYValores() {
    try {
        const response = await fetch('../action/obtenerCriteriosYValores.php');  // Llama a un nuevo script que devuelve los criterios y valores
        const data = await response.json();

        if (data.error) {
            console.error('Error al cargar los criterios y valores:', data.error);
            return;
        }

        console.log('Criterios y valores:', data);
        return data; // Devolvemos los criterios y sus valores para su uso
    } catch (error) {
        console.error('Error al cargar los criterios y valores:', error);
    }
}

// Función para cargar el perfil deseado
async function cargarPerfilDeseado() {
    try {
        const criteriosYValores = await cargarCriteriosYValores(); // Obtener criterios y valores desde los archivos
        
        // Aquí se supone que 'criteriosYValores' será un objeto en el formato { criterio: [valores] }
        if (!criteriosYValores) {
            console.error('No se obtuvieron criterios y valores.');
            return;
        }

        Object.entries(criteriosYValores).forEach(([criterio, valores], index) => {
            if (index > 0) addCriterion(); // Agregar un nuevo criterio si es el segundo o más

            const criterioSelect = document.getElementById(`criterion${index + 1}`);
            const valorSelect = document.getElementById(`value${index + 1}`);

            // Seleccionar el criterio en el combobox
            const criterioOption = Array.from(criterioSelect.options).find(option => option.text === criterio);
            if (criterioOption) criterioOption.selected = true;

            // Limpiar los valores anteriores del combobox
            valorSelect.innerHTML = '';

            // Cargar los valores en el combobox correspondiente
            valores.forEach(valor => {
                let valorOption = new Option(valor, valor);
                valorSelect.add(valorOption);
            });

            // Seleccionar el valor en el combobox si es necesario
            // valorOption.selected = true; (Si tienes algún valor a seleccionar, puedes hacerlo aquí)
        });

        actualizarTablaConCriterio();
    } catch (error) {
        console.error('Error al cargar el perfil deseado:', error);
    }
}
