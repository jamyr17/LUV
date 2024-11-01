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

    public function getAllDeletedTbActividad() {
        return $this->actividadData->getAllDeletedTbActividad();
    }

    public function restoreTbActividad($actividadId) {
        $actividadData = new ActividadData();
        return $actividadData->restoreTbActividad($actividadId);
    }

    public function insertAttendance($idActividad, $idUsuario) {
        return $this->actividadData->insertAttendance($idActividad, $idUsuario);
    }

    public function askAttendance($idActividad, $idUsuario) {
        return $this->actividadData->askAttendance($idActividad, $idUsuario);
    }

    public function cancelAttendance($idActividad, $idUsuario) {
        return $this->actividadData->cancelAttendance($idActividad, $idUsuario);
    }

    public function getListAttendance($idActividad) {
        return $this->actividadData->getListAttendance($idActividad);
    }

}