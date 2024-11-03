document.addEventListener('DOMContentLoaded', function() {
    // Dirección para recuperar todas las actividades de la base de datos:
    let requestActivities = '../data/getActivitiesData.php';    
    
    // Iniciar el objeto calendario
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        editable: false,
        dragScroll: true,
        dayMaxEvents: 10,
        eventResizableFromStart: true,
        customButtons: {
            sidebarToggle: {
                text: 'Sidebar'
            }
        },
        headerToolbar: {
            start: 'prev,next, title',
            end: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
        },
        locale: 'es',

        events:function(info, successCallback, failureCallback){ // Recuperar las actividades
            fetch(requestActivities)
            .then(function(response){
                return response.json()
            }).then(function(data){
                let events = data.map(function(event){ // Formatear los objetos de actividades
                    return {
                        id: event.id,
                        usuarioId: event.usuarioId,
                        title: event.title,
                        description: event.description,
                        direction: event.direction,
                        imagen: event.imagen,
                        start: new Date(event.dateStart),
                        end: new Date(event.dateEnd),
                        timeStart: '8:00',
                        timeEnd: '10:00',
                        anonymn: event.anonymn,
                        editable: true,
                        colectivos: event.colectivos
                    }
                })
                
                console.log(events)
                successCallback(events)
            })
            .catch(function(error){
                console.log(error)
                failureCallback(events)
            });
        },
        
        eventContent: function(info){
            return { 
                html: `
                    <div 
                        style="overflow: hidden; font-size: 12px; position: relative; cursor: pointer; font-family: 'Inter', sans-serif;">
                        <div>
                            <strong>${info.event.title}</strong>
                        </div>
                        <div>
                            <strong> Dirección: </strong> ${info.event.extendedProps.direction}
                        </div>
                        <div>
                            <strong> Hora: </strong> ${info.event.extendedProps.timeStart}
                        </div>
                    </div> 
                    `
            }
        },

        eventClick: function(event) {
            console.log(event);

            // Se debe validar que esto solo sea posible si el usuario que está logeado es el creador del evento, si no lo es, validar si es evento anonimo no y si no lo es se puede apuntar a la asistencia
            if(validateLoggedUser(event.event.extendedProps.usuarioId)){
                var inputIdActividad = document.getElementById('idActividad');
                var inputTitulo = document.getElementById('titulo');
                var inputDescripcion = document.getElementById('descripcion');
                var inputFechaInicio = document.getElementById('fechaInicioInput');
                var inputFechaTermina = document.getElementById('fechaTerminaInput');
                var inputDireccion = document.getElementById('direccion');
                var inputAnonimo = document.getElementById('anonimo');
                var inputColectivos = document.getElementById('colectivos');
                const divImagen = document.getElementById('imagenActual');
            
                inputIdActividad.value = event.event.id;
                inputTitulo.value = event.event.title;
                inputDescripcion.value = event.event.extendedProps.description;
                inputFechaInicio.value = toDateTimeLocalFormat(event.event.start);
                inputFechaTermina.value = toDateTimeLocalFormat(event.event.end);
                inputDireccion.value = event.event.extendedProps.direction;
                inputAnonimo.checked = (parseInt(event.event.extendedProps.anonymn) === 1);

                if(event.event.extendedProps.imagen!='' || event.event.extendedProps.imagen!=null){
                    divImagen.innerHTML = 'Imagen actual <br/> <img src="' + event.event.extendedProps.imagen + '" alt="Imagen de "' +  event.event.title + '" style="width: 100%; max-width: 200px; height: auto; object-fit: cover; display: block; margin: 10px auto; border-radius: 5px;"/>';
                }
            
                // Manejar los colectivos seleccionados
                var colectivosSeleccionados = event.event.extendedProps.colectivos || [];
                for (var i = 0; i < inputColectivos.options.length; i++) {
                    var option = inputColectivos.options[i];
                    option.selected = colectivosSeleccionados.includes(parseInt(option.value));
                }

                showAttendanceList(event.event.id, 'listAttendanceDivOwner');
            
                $("#actividadActualizarModalView").modal();
            } // Si no es el creador del evento, debe ver los detalles y también si quiere apuntarse o no a la asistencia, además de ver los asistentes si no es anónimo el evento
            else{

                askRegisteredAttendance(event.event.id).then(isRegistered => {
                    if (isRegistered) {
                        const detalleTitle = document.getElementById('activityTitleRegistered');
                        const detalleDireccion = document.getElementById('activityDirectionRegistered');
                        const detalleFechaInicio = document.getElementById('activityStartDateRegistered');
                        const inputIdActividad = document.getElementById('idActividadDelAttendance');
                        const divImagen = document.getElementById('imagenRegistered');
        
                        detalleTitle.innerHTML = '<h4><strong>' + event.event.title + '</strong></h4>';
                        detalleDireccion.innerHTML = event.event.extendedProps.direction;
                        detalleFechaInicio.innerHTML = event.event.start;
                        inputIdActividad.value = event.event.id;

                        if(event.event.extendedProps.imagen!='' || event.event.extendedProps.imagen!=null){
                            divImagen.innerHTML = '<img src="' + event.event.extendedProps.imagen + '" alt="Imagen de "' +  event.event.title + '" style="width: 100%; max-width: 200px; height: auto; object-fit: cover; display: block; margin: 10px auto; border-radius: 5px;"/>';
                        }

                        if (parseInt(event.event.extendedProps.anonymn) !== 1) {
                            showAttendanceList(event.event.id, 'listAttendanceDivRegistered');
                        }
        
                        $("#verDetallesActividadRegistered").modal();
                    } else {
                        const detalleTitle = document.getElementById('activityTitle');
                        const detalleDireccion = document.getElementById('activityDirection');
                        const detalleFechaInicio = document.getElementById('activityStartDate');
                        const inputIdActividad = document.getElementById('idActividadAttendance');
                        const divImagen = document.getElementById('imagenDetail');
        
                        detalleTitle.innerHTML = '<h4><strong>' + event.event.title + '</strong></h4>';
                        detalleDireccion.innerHTML = event.event.extendedProps.direction;
                        detalleFechaInicio.innerHTML = event.event.start;
                        inputIdActividad.value = event.event.id;

                        if(event.event.extendedProps.imagen!='' || event.event.extendedProps.imagen!=null){
                            divImagen.innerHTML = '<img src="' + event.event.extendedProps.imagen + '" alt="Imagen de "' +  event.event.title + '" style="width: 100%; max-width: 200px; height: auto; object-fit: cover; display: block; margin: 10px auto; border-radius: 5px;"/>';
                        }

                        if (parseInt(event.event.extendedProps.anonymn) !== 1) {
                            showAttendanceList(event.event.id, 'listAttendanceDivDetails');
                        }
                        
                        $("#verDetallesActividad").modal();
                    }
                }).catch(error => {
                    console.error("Error al verificar la asistencia:", error);
                });

            }

        }
        
    });
    calendar.render();
    
    // Abrir el modal al hacer clic en el botón "Agregar Evento"
    document.getElementById('add-event-button').addEventListener('click', function () {
        $('#actividadCrearModal').modal();
    });
});

// El formato de las fechas en el objeto se guardan de una forma que es necesaria reformatear
function toDateTimeLocalFormat(dateStr) {
    const date = new Date(dateStr);
    if (isNaN(date.getTime())) {
        console.error("Fecha no válida: ", dateStr);
        return ''; // Devuelve cadena vacía si no es válida
    }
    const offset = date.getTimezoneOffset() * 60000;
    const localISOTime = new Date(date - offset).toISOString().slice(0, 16);
    return localISOTime;
}

function validateLoggedUser(eventUsuarioId){
    const usuarioId = document.getElementById('idUsuarioLogeado').value;

    if(usuarioId===null){
        return false;
    }else{
        return (eventUsuarioId===usuarioId);
    }

}

function askRegisteredAttendance(idActividad) {
    return new Promise((resolve, reject) => {
        const idUsuario = document.getElementById('idUsuarioLogeado').value; 

        $.ajax({
            url: '../action/actividadAction.php', 
            method: 'POST',
            data: {
                checkAttendance: true, 
                idActividad: idActividad,
                idUsuario: idUsuario
            },
            dataType: 'json',
            success: function(response) {
                resolve(response); 
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud AJAX:", error);
                reject(error); 
            }
        });
    });
}

function getListAttendance(idActividad) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: '../action/actividadAction.php', 
            method: 'POST',
            data: {
                getListAttendance: true, 
                idActividad: idActividad
            },
            dataType: 'json',
            success: function(response) {
                resolve(response); 
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud AJAX:", error);
                reject(error); 
            }
        });
    });
}

function showAttendanceList(idActividad, nombreDiv) {
    getListAttendance(idActividad)
        .then(response => {
            const usuarioId = document.getElementById('idUsuarioLogeado').value;
            let listAttendanceDiv = $(nombreDiv.startsWith('#') ? nombreDiv : '#' + nombreDiv);
            listAttendanceDiv.empty(); // Limpiar contenido anterior

            if (response.length > 0) {
                let listHtml = '<p><strong>Asistentes de la actividad</strong></p>'
                listHtml += '<ul class="list-group">';
                response.forEach(user => {
                    if(user.id!=usuarioId){
                        if(user.imagen==''){
                            listHtml += `
                                <li class="list-group-item">
                                    <img src="../resources/img/profile/no-pfp.png" alt="no imagen" style="width: 30px; height: 30px; border-radius: 50%;">
                                    ${user.nombreUsuario}
                                </li>
                            `;
                        }else{
                            listHtml += `
                                <li class="list-group-item">
                                    <img src="${user.imagen}" alt="imagen de ${user.nombreUsuario}" style="width: 30px; height: 30px; border-radius: 50%;">
                                    ${user.nombreUsuario}
                                </li>
                            `;
                        }
                    }
                });
                listHtml += '</ul>';
                listAttendanceDiv.append(listHtml);
            } else {
                listAttendanceDiv.append('<p>Nadie ha registrado su asistencia aún.</p>');
            }
        })
        .catch(error => {
            console.error("Error al cargar la lista de asistencia:", error);
        });
}

