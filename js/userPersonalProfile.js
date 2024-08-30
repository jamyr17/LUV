class PerfilPersonal {
    constructor() {
        this.criterios = [];
        this.valores = [];
        this.criterionCount = 0; // Contador para los criterios cargados
        this.init();
    }

    // Inicializa la carga de datos
    async init() {
        this.showLoading(true);
        try {
            await this.loadInitialCriteriaData(); // Cargar criterios desde la base de datos
            await this.loadInitialValuesData(); // Cargar valores desde la base de datos
            this.addCriterion(); // Agregar el primer criterio al iniciar
        } catch (error) {
            console.error('Error al cargar los datos:', error);
        } finally {
            this.showLoading(false);
        }
    }

    // Mostrar o ocultar el indicador de carga
    showLoading(show) {
        const loadingDiv = document.getElementById('loading');
        loadingDiv.style.display = show ? 'block' : 'none';
    }

    // Función para hacer fetch con reintento
    async fetchDataWithRetry(url, retries = 3) {
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

    // Función para cargar criterios
    async loadInitialCriteriaData() {
        try {
            this.criterios = await this.fetchDataWithRetry('../data/getData.php?type=6');
        } catch (error) {
            console.error('Error al cargar criterios:', error);
            alert('No se pudieron cargar los criterios. Por favor, inténtelo más tarde.');
        }
    }

    // Función para cargar valores
    async loadInitialValuesData() {
        try {
            this.valores = await this.fetchDataWithRetry('../data/getData.php?type=7');
        } catch (error) {
            console.error('Error al cargar valores:', error);
            alert('No se pudieron cargar los valores. Por favor, inténtelo más tarde.');
        }
    }

    // Función para agregar un nuevo criterio
    addCriterion() {
        this.criterionCount++;
        const criteriaSection = document.getElementById('criteriaSection');

        const criterio = this.criterios[this.criterionCount - 1];
        if (!criterio) {
            console.warn('No hay más criterios para agregar.');
            return;
        }

        const newCriterion = document.createElement('div');
        newCriterion.className = 'criterion';
        newCriterion.id = `criterion${this.criterionCount}`;
        newCriterion.innerHTML = `
            <label for="value${this.criterionCount}">${criterio.name}:</label>
            <select name="value[]" id="value${this.criterionCount}" onchange="perfilPersonal.toggleOtherField(this, ${this.criterionCount})">
                <!-- Las opciones de valores se cargarán dinámicamente -->
            </select>
            <input type="text" id="otherField${this.criterionCount}" name="otherValue[]" style="display: none;" placeholder="Especifique otro valor">
        `;
        criteriaSection.appendChild(newCriterion);

        // Cargar valores para el nuevo criterio
        this.loadValues(document.getElementById(`value${this.criterionCount}`), this.criterionCount);
    }

    // Función para cargar los valores en los select correspondientes
    loadValues(select, index) {
        const criterionId = this.criterios[index - 1].id;
        const valueSelect = select;
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

    // Para que el usuario pueda agregar un valor personalizado
    toggleOtherField(select, index) {
        const otherField = document.getElementById(`otherField${index}`);
        if (select.value === 'other') {
            otherField.style.display = 'block';
        } else {
            otherField.style.display = 'none';
            otherField.value = ''; // Limpiar el campo de texto si se oculta
        }
    }

    // Función para validar el formulario antes de enviarlo
    submitForm() {
        const criteria = this.criterios.map(c => c.name);
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
}

// Inicializar la clase PerfilPersonal
const perfilPersonal = new PerfilPersonal();
