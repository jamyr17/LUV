document.addEventListener('DOMContentLoaded', function() {
    // Dirección para recuperar todas las actividades de la base de datos:
    let requestActivies = '../data/getActivitiesData.php';    
    
    // Iniciar el objeto calendario
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        header: {
            left: "prev, next today",
            center: "title",
            right: "month,agendaWeek,agendaDay"
          },
      
          locale: 'es',
      
          defaultView: "month",
          navLinks: true, 
          editable: true,
          eventLimit: true, 
          selectable: true,
          selectHelper: false,

        events:function(info, successCallback, failureCallback){ // Recuperar las actividades
            fetch(requestActivies)
            .then(function(response){
                return response.json()
            }).then(function(data){
                let events = data.map(function(event){ // Formatear los objetos de actividades
                    return {
                        id: event.id,
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

        // Se debe validar que esto solo sea posible si el usuario que está logeado es el creado del evento
        eventClick:function(event){
            console.log(event);
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

            $("#actividadActualizarModalView").modal();
        }

    });
    calendar.render();
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

