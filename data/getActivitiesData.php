<?php
    include_once('../business/actividadBusiness.php');
    $actividadBusiness = new ActividadBusiness();
    $actividades = $actividadBusiness->getTbActividad();
    $data = [];

    header('Content-Type: application/json');

    foreach($actividades as $actividad){
        $data[] = [
            'id' => $actividad->getTbActividadId(),
            'title' => $actividad->getTbActividadTitulo(),
            'description' => $actividad->getTbActividadDescripcion(),
            'dateStart' => $actividad->getTbActividadFechaInicio(),
            'dateEnd' => $actividad->getTbActividadFechaTermina(),
            'direction' => $actividad->getTbActividadDireccion(),
            'anonymn' => $actividad->getTbActividadAnonimo(),
            'colectivos' => $actividad->getTbActividadColectivos()
        ];
    }

    echo json_encode($data);