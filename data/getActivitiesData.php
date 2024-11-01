<?php
    include_once('../business/actividadBusiness.php');
    include_once '../business/universidadCampusColectivoBusiness.php';
    
    $campusColectivoBusiness = new UniversidadCampusColectivoBusiness();
    $actividadBusiness = new ActividadBusiness();
    $actividades = $actividadBusiness->getTbActividad();
    $data = [];
    
    header('Content-Type: application/json');
    
    foreach ($actividades as $actividad) {
        // Obtener los colectivos relacionados a esta actividad
        $colectivos = $campusColectivoBusiness->getColectivosByActividadId($actividad->getTbActividadId());
        
        // Mapear los colectivos a un formato más simple (por ejemplo, IDs o nombres)
        $colectivosArray = array_map(function ($colectivo) {
            return $colectivo->getTbUniversidadCampusColectivoId(); // O el nombre si lo prefieres
        }, $colectivos);
        
        $data[] = [
            'id' => $actividad->getTbActividadId(),
            'usuarioId' => $actividad->getTbUsuarioId(),
            'title' => $actividad->getTbActividadTitulo(),
            'description' => $actividad->getTbActividadDescripcion(),
            'imagen' => $actividad->getTbActividadImagen(),
            'dateStart' => $actividad->getTbActividadFechaInicio(),
            'dateEnd' => $actividad->getTbActividadFechaTermina(),
            'direction' => $actividad->getTbActividadDireccion(),
            'anonymn' => $actividad->getTbActividadAnonimo(),
            'colectivos' => $colectivosArray // Usa el nuevo array de colectivos
        ];
    }
    
    // Puedes descomentar esto para ver los datos finales en formato de array
    echo json_encode($data);
    
    