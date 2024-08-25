class PerfilModelador {
    constructor() {
        this.criterios = [];
        this.valores = [];
        this.loadInitialCriteriaData();
        this.loadInitialValuesData();
    }

    // Función para cargar criterios y valores una sola vez
    loadInitialCriteriaData() {
        fetch('../data/getData.php?type=6')
            .then(response => response.json())
            .then(data => {
                this.criterios = data;
                // Cargar todos los criterios en el formulario
                this.populateAllCriteria();
            })
            .catch(error => console.error('Error al cargar datos iniciales:', error));
    }

    loadInitialValuesData() {
        fetch('../data/getData.php?type=7')
            .then(response => response.json())
            .then(data => {
                this.valores = data;
                // Cargar valores para el primer criterio
                const firstCriterioId = this.criterios.length > 0 ? this.criterios[0].id : null;
                if (firstCriterioId) {
                    this.loadValues(firstCriterioId, 1);
                }
            })
            .catch(error => console.error('Error al cargar datos iniciales:', error));
    }

    // Función para popular todos los criterios
    populateAllCriteria() {
        const criteriaSection = document.getElementById('criteriaSection');

        this.criterios.forEach((criterio, index) => {
            const criterionIndex = index + 1;
            const criterionDiv = document.createElement('div');
            criterionDiv.className = 'criterion';
            criterionDiv.innerHTML = `
                <label for="criterion${criterionIndex}">Criterio: </label>
                <label id="criterion${criterionIndex}" class="criterio-label" data-criterio-id="${criterio.id}">${criterio.name}</label>

                <label for="value${criterionIndex}"> Prefiero:</label>
                <select name="value[]" id="value${criterionIndex}" required>
                    <!-- Las opciones de valores se cargarán dinámicamente -->
                </select>
            `;
            criteriaSection.appendChild(criterionDiv);

            // Cargar valores para el criterio actual
            this.loadValues(criterio.id, criterionIndex);
        });
    }

    // Función para cargar los valores correspondientes a un criterio seleccionado
    loadValues(criterionId, index) {
        const valueSelect = document.getElementById(`value${index}`);
        if (!valueSelect) {
            console.error(`Elemento select de valores no encontrado para el índice ${index}`);
            return;
        }

        valueSelect.innerHTML = '';  // Limpiar opciones actuales

        const filteredValues = this.valores.filter(valor => valor.idCriterio == criterionId);

        if (filteredValues.length === 0) {
            const option = document.createElement('option');
            option.textContent = 'No hay valores disponibles';
            valueSelect.appendChild(option);
            return;
        }

        filteredValues.forEach(valor => {
            const option = document.createElement('option');
            option.value = valor.name;  // Usamos el nombre en lugar del ID
            option.textContent = valor.name;
            valueSelect.appendChild(option);
        });
    }

    // Función para validar el formulario antes de enviarlo
    submitForm() {
        const criteriaLabels = document.querySelectorAll('.criterio-label');
        const values = document.querySelectorAll('select[name="value[]"]');

        let criteriaString = '';
        let valuesString = '';
        let valid = true;

        criteriaLabels.forEach((label, i) => {
            const criterionName = label.textContent;
            const valueSelect = values[i];
            const selectedValue = valueSelect.value;

            if (!selectedValue) {
                valid = false;
            }

            criteriaString += criterionName;
            valuesString += selectedValue;

            if (i < criteriaLabels.length - 1) {
                criteriaString += ',';
                valuesString += ',';
            }
        });

        if (!valid) {
            alert('Por favor, seleccione un valor para cada criterio.');
            return false;
        }

        document.getElementById('criteriaString').value = criteriaString;
        document.getElementById('valuesString').value = valuesString;

        return true;
    }
}

// Instancia de la clase que será usada en todo el documento
const perfilModelador = new PerfilModelador();
