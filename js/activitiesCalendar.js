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

            const isCreator = validateLoggedUser(event.event.extendedProps.usuarioId);
            const imageURL = event.event.extendedProps.imagen;
            let modalId,imageContainerId2;

            if (isCreator) {
                imageContainerId = "imagenActual";
                modalId = "#actividadActualizarModalView";
                setupCreatorModal(event);
            } else {
                askRegisteredAttendance(event.event.id).then(isRegistered => {
                    if (isRegistered) {
                        imageContainerId2 = "imagenRegistered";
                        modalId = "#verDetallesActividadRegistered";
                        setupRegisteredUserModal(event);
                    } else {
                        imageContainerId2 = "imagenDetail";
                        modalId = "#verDetallesActividad";
                        setupVisitorModal(event);
                    }
                    setupModalContent(imageContainerId2, imageURL, event.event.title);
                    $(modalId).modal();
                }).catch(error => console.error("Error al verificar la asistencia:", error));
            }

            setupModalContent(imageContainerId, imageURL, event.event.title);
            $(modalId).modal();

            if (isCreator) {
                // Cargar detalles en el modal de actualización
                loadEventDetails(event);
                $("#actividadActualizarModalView").modal();
            } else {
                askRegisteredAttendance(event.event.id).then(isRegistered => {
                    if (isRegistered) {
                        showRegisteredEventDetails(event);
                        $("#verDetallesActividadRegistered").modal();
                    } else {
                        showVisitorEventDetails(event);
                        $("#verDetallesActividad").modal();
                    }
                }).catch(error => {
                    console.error("Error al verificar la asistencia:", error);
                });
            }
        

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
                        const divImagen2 = document.getElementById('imagenRegistered');
        
                        detalleTitle.innerHTML = '<h4><strong>' + event.event.title + '</strong></h4>';
                        detalleDireccion.innerHTML = event.event.extendedProps.direction;
                        detalleFechaInicio.innerHTML = event.event.start;
                        inputIdActividad.value = event.event.id;

                        if(event.event.extendedProps.imagen!='' || event.event.extendedProps.imagen!=null){
                            divImagen2.innerHTML = '<img src="' + event.event.extendedProps.imagen + '" alt="Imagen de "' +  event.event.title + '" style="width: 100%; max-width: 200px; height: auto; object-fit: cover; display: block; margin: 10px auto; border-radius: 5px;"/>';
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
                        const divImagen3 = document.getElementById('imagenDetail');
        
                        detalleTitle.innerHTML = '<h4><strong>' + event.event.title + '</strong></h4>';
                        detalleDireccion.innerHTML = event.event.extendedProps.direction;
                        detalleFechaInicio.innerHTML = event.event.start;
                        inputIdActividad.value = event.event.id;

                        if(event.event.extendedProps.imagen!='' || event.event.extendedProps.imagen!=null){
                            divImagen3.innerHTML = '<img src="' + event.event.extendedProps.imagen + '" alt="Imagen de "' +  event.event.title + '" style="width: 100%; max-width: 200px; height: auto; object-fit: cover; display: block; margin: 10px auto; border-radius: 5px;"/>';
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

function getEventDetailsContainer(eventId) {
    // Asegúrate de que el contenedor tenga un ID válido
    let containerId = `event-details-container-${eventId}`;
    let container = document.getElementById(containerId);

    // Si el contenedor no existe, podrías crear uno dinámicamente
    if (!container) {
        console.warn(`El contenedor con ID ${containerId} no existe. Creando uno nuevo.`);
        container = document.createElement('div');
        container.id = containerId;
        document.body.appendChild(container); // O agrégalo a donde corresponda en el DOM
    }

    return containerId; // Devuelve el ID del contenedor
}

function setupModalContent(imageContainerId, imageURL, altText) {
    const containerId = getEventDetailsContainer(imageContainerId); // Asegúrate de tener un ID válido
    const container = document.getElementById(containerId);

    if (container) {
        container.innerHTML = `
            <img id="${containerId}-image" src="${imageURL}" alt="Imagen de ${altText}" 
            style="width: 100%; max-width: 200px; height: auto; object-fit: cover; display: block; margin: 10px auto; border-radius: 5px;"/>
        `;
        const imgElement = document.getElementById(`${containerId}-image`);
        applyImageTracking(imgElement);
    } else {
        console.error(`No se pudo encontrar o crear el contenedor con ID: ${containerId}`);
    }
}


function trackImageMouseMove(imageElement) {
    if (imageElement && !imageElement.dataset.trackingApplied) {
        console.log("Tracking mouse movement on image", imageElement.id);
        setupImageTracking(imageElement);
        imageElement.dataset.trackingApplied = true;
    }
}

function setupImageTracking(imageElement) {
    let startTime = null;
    let activeRegion = null;
    let zoomScale = 1;

    imageElement.addEventListener('mousemove', function (event) {
        const { offsetWidth: imageWidth, offsetHeight: imageHeight } = imageElement;
        const { offsetX: mouseX, offsetY: mouseY } = event;

        const regionX = Math.min(3, Math.max(1, Math.floor(mouseX / (imageWidth / 3)) + 1));
        const regionY = Math.min(3, Math.max(1, Math.floor(mouseY / (imageHeight / 3)) + 1));
        const currentRegion = `${regionY},${regionX}`;

        if (activeRegion !== currentRegion) {
            if (startTime && activeRegion) {
                const duration = Date.now() - startTime;
                sendSegmentTracking(activeRegion, duration, zoomScale, imageElement.src);
            }
            activeRegion = currentRegion;
            startTime = Date.now();
        }
    });

    imageElement.addEventListener('mouseleave', function () {
        if (startTime && activeRegion) {
            const duration = Date.now() - startTime;
            sendSegmentTracking(activeRegion, duration, zoomScale, imageElement.src);
            startTime = null;
            activeRegion = null;
        }
    });

    imageElement.addEventListener('wheel', function (event) {
        event.preventDefault();
        zoomScale = Math.max(1, zoomScale + (event.deltaY < 0 ? 0.1 : -0.1));
        imageElement.style.transform = `scale(${zoomScale})`;
    });
}

function sendSegmentTracking(region, duration, zoomScale, imageURL) {
    if (!imageURL) return;

    fetch('../action/userAffinityAction.php', {
        method: 'POST',
        body: JSON.stringify({ region, duration, zoomScale, imageURL }),
        headers: { 'Content-Type': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            console.log("Segment tracking saved:", data.message);
        } else {
            console.error("Error saving segment tracking:", data.message);
        }
    })
    .catch(error => console.error('Network error:', error));
}
function setupCreatorModal(event) {
    // Lógica para configurar el modal del creador del evento
    const inputIdActividad = document.getElementById('idActividad');
    const inputTitulo = document.getElementById('titulo');
    const inputDescripcion = document.getElementById('descripcion');
    const inputFechaInicio = document.getElementById('fechaInicioInput');
    const inputFechaTermina = document.getElementById('fechaTerminaInput');
    const inputDireccion = document.getElementById('direccion');
    const inputAnonimo = document.getElementById('anonimo');
    const divImagen = document.getElementById('imagenActual');

    inputIdActividad.value = event.event.id;
    inputTitulo.value = event.event.title;
    inputDescripcion.value = event.event.extendedProps.description;
    inputFechaInicio.value = toDateTimeLocalFormat(event.event.start);
    inputFechaTermina.value = toDateTimeLocalFormat(event.event.end);
    inputDireccion.value = event.event.extendedProps.direction;
    inputAnonimo.checked = (parseInt(event.event.extendedProps.anonymn) === 1);

    if (event.event.extendedProps.imagen != '' || event.event.extendedProps.imagen != null) {
        divImagen.innerHTML = `
            Imagen actual <br/> 
            <img src="${event.event.extendedProps.imagen}" alt="Imagen de ${event.event.title}" 
            style="width: 100%; max-width: 200px; height: auto; object-fit: cover; display: block; margin: 10px auto; border-radius: 5px;"/>
        `;
    }

    $("#actividadActualizarModalView").modal();
}

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

