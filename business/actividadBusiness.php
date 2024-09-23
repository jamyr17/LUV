<?php

include '../data/actividadData.php';

class ActividadBusiness {

    private $actividadData;

    public function __construct() {
        $this->actividadData = new ActividadData();
    }

    public function insertTbActividad($actividad) {
        return $this->actividadData->insertTbactividad($actividad);
    }

    public function getTbActividad() {
        return $this->actividadData->getTbActividad();
    }

    public function updateTbActividad($actividad) {
        return $this->actividadData->updateTbActividad($actividad);
    }

    public function deleteTbActividad($idActividad) {
        return $this->actividadData->deleteTbActividad($idActividad);
    }

    public function deleteForeverTbActividad($idActividad) {
        return $this->actividadData->deleteForeverTbActividad($idActividad);
    }

    public function exist($nombre) {
        return $this->actividadData->exist($nombre);
    }

    public function nameExist($nombre, $idActividad) {
        return $this->actividadData->nameExists($nombre, $idActividad);
    }

    public function getAllTbActividadTitulos() {
        return $this->actividadData->getAllTbActividadTitulos();
    }
    
    public function autocomplete($term) {
        return $this->actividadData->autocomplete($term);
    }

    public function restoreTbActividad($idActividad) {
        return $this->actividadData->restoreTbActividad($idActividad);
    }

}