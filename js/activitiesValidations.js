function actionConfirmation(mensaje, idCriterio) {
    if (confirm(mensaje)) {
        return true;
    }
    else{
        return false;
    }
}

function actionConfirmationRestore(mensaje) {
    return confirm(mensaje);
}

function showMessage(mensaje) {
    alert(mensaje);
}

function toggleDeletedActivities() {
    var section = document.getElementById("table-deleted");
    if (section.style.display === "none") {
        section.style.display = "block";
    } else {
        section.style.display = "none";
    }
}

function validateForm(formId) {
    var form = document.getElementById(formId);

    // Obtener los elementos del formulario por su nombre o ID, según corresponda
    var titulo = form.querySelector("#titulo").value;
    var descripcion = form.querySelector("#descripcion").value;
    var direccion = form.querySelector("#direccion").value;
    var fechaInicioInput = form.querySelector("#fechaInicioInput").value;
    var fechaTerminaInput = form.querySelector("#fechaTerminaInput").value;

    // Validación de caracteres
    if (titulo.length > 63) {
        alert("El título no puede tener más de 63 caracteres.");
        return false;
    }
    if (descripcion.length > 255) {
        alert("La descripción no puede tener más de 255 caracteres.");
        return false;
    }
    if (direccion.length > 255) {
        alert("La dirección no puede tener más de 255 caracteres.");
        return false;
    }

    // Validación de las fechas
    if (!validateDates(fechaInicioInput, fechaTerminaInput)) {
        return false;
    }

    return true;
}

function validateDates(fechaInicio, fechaTermina) {
    var startDate = new Date(fechaInicio);
    var endDate = new Date(fechaTermina);

    if (startDate >= endDate) {
        alert("La fecha y hora de inicio debe ser anterior a la fecha y hora de término.");
        return false;
    }

    return true;
}

document.addEventListener('DOMContentLoaded', function() {
    // Manejo de fecha de inicio
    const fechaInicioInput = document.getElementById('fechaInicioInput');
    const valueFechaInicio = fechaInicioInput.value;

    const today = new Date();
    const formattedToday = today.toISOString().split('T')[0]; 
    fechaInicioInput.value = formattedToday;

    const minDate = formattedToday; 
    const maxDate = new Date();
    maxDate.setFullYear(today.getFullYear() + 50);
    const formattedMaxDate = maxDate.toISOString().split('T')[0]; 

    fechaInicioInput.min = minDate; 
    fechaInicioInput.max = formattedMaxDate; 

    if (valueFechaInicio !== null && valueFechaInicio !== "") {
        fechaInicioInput.value = valueFechaInicio;
    }

    function updateDisplayedFechaInicio() {
        const selectedDate = new Date(fechaInicioInput.value);
        const day = String(selectedDate.getDate()).padStart(2, '0');
        const month = String(selectedDate.getMonth() + 1).padStart(2, '0');
        const year = selectedDate.getFullYear();
        document.getElementById('fechaInicioMostrar').textContent = `${day}/${month}/${year}`;
    }

    updateDisplayedFechaInicio();
    fechaInicioInput.addEventListener('input', updateDisplayedFechaInicio);

    // Manejo de fecha de final
    const fechaTerminaInput = document.getElementById('fechaTerminaInput');
    const valueFechaTermina = fechaTerminaInput.value;

    fechaTerminaInput.min = formattedToday; 
    fechaTerminaInput.max = formattedMaxDate; 

    if (valueFechaTermina !== null && valueFechaTermina !== "") {
        fechaTerminaInput.value = valueFechaTermina;
    }

    function updateDisplayedFechaTermina() {
        const selectedDate = new Date(fechaTerminaInput.value);
        const day = String(selectedDate.getDate()).padStart(2, '0');
        const month = String(selectedDate.getMonth() + 1).padStart(2, '0');
        const year = selectedDate.getFullYear();
        document.getElementById('fechaTerminaMostrar').textContent = `${day}/${month}/${year}`;
    }

    updateDisplayedFechaTermina();
    fechaTerminaInput.addEventListener('input', updateDisplayedFechaTermina);

});