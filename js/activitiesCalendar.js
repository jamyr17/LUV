document.addEventListener('DOMContentLoaded', function() {
    // Dirección para recuperar todas las actividades de la base de datos:
    let requestActivities = '../data/getActivitiesData.php';    
    
    // Iniciar el objeto calendario
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        headerToolbar: {
            left: 'prev,next',
            center: 'title',
            right: 'timeGridWeek,timeGridDay'
        },
      
        locale: 'es',
        navLinks: true, 
        editable: true,
        selectable: true,

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
                            Dirección: ${info.event.extendedProps.direction}
                        </div>
                        <div>
                            Hora: ${info.event.extendedProps.timeStart}
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
            
                inputIdActividad.value = event.event.id;
                inputTitulo.value = event.event.title;
                inputDescripcion.value = event.event.extendedProps.description;
                inputFechaInicio.value = toDateTimeLocalFormat(event.event.start);
                inputFechaTermina.value = toDateTimeLocalFormat(event.event.end);
                inputDireccion.value = event.event.extendedProps.direction;
                inputAnonimo.checked = (parseInt(event.event.extendedProps.anonymn) === 1);
            
                // Manejar los colectivos seleccionados
                var colectivosSeleccionados = event.event.extendedProps.colectivos || [];
                for (var i = 0; i < inputColectivos.options.length; i++) {
                    var option = inputColectivos.options[i];
                    option.selected = colectivosSeleccionados.includes(parseInt(option.value));
                }
            
                $("#actividadActualizarModalView").modal();
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
