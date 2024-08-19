<?php

include '../data/campusEspecializacionData.php';

class CampusEspecializacionBussiness{

    private $campusEspecializacionData;

    public function __construct() {
        $this->campusEspecializacionData = new CampusEspecializacionData();
    }

    public function insertTbCampusEspecializacion($campusEspecializacion) {
        return $this->campusEspecializacionData->insertTbCampusEspecializacion($campusEspecializacion);
    }

    public function updateTbCampusEspecializacion($campusEspecializacion) {
        return $this->campusEspecializacionData->updateTbCampusEspecializacion($campusEspecializacion);
    }

    public function deleteTbCampusEspecializacion($campusEspecializacionId) {
        return $this->campusEspecializacionData->deleteTbCampusEspecializacion($campusEspecializacionId);
    }

    public function getAllTbCampusEspecializacion() {
        return $this->campusEspecializacionData->getAllTbCampusEspecializacion();
    }

    public function getAllDeletedTbCampusEspecializacion() {
        return $this->campusEspecializacionData->getAllDeletedTbCampusEspecializacion();
    }

    public function exist($nombre) {
        return $this->campusEspecializacionData->exist($nombre);
    }

}
