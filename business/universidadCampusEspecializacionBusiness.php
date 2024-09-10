<?php

include '../data/universidadCampusEspecializacionData.php';

class universidadCampusEspecializacionBusiness{

    private $universidadCampusEspecializacionData;

    public function __construct() {
        $this->universidadCampusEspecializacionData = new universidadCampusEspecializacionData();
    }

    public function insertTbUniversidadCampusEspecializacion($universidadCampusEspecializacion) {
        return $this->universidadCampusEspecializacionData->insertTbUniversidadCampusEspecializacion($universidadCampusEspecializacion);
    }

    public function updateTbUniversidadCampusEspecializacion($universidadCampusEspecializacion) {
        return $this->universidadCampusEspecializacionData->updateTbUniversidadCampusEspecializacion($universidadCampusEspecializacion);
    }

    public function deleteTbUniversidadCampusEspecializacion($universidadCampusEspecializacionId) {
        return $this->universidadCampusEspecializacionData->deleteTbUniversidadCampusEspecializacion($universidadCampusEspecializacionId);
    }

    public function getAllTbUniversidadCampusEspecializacion() {
        return $this->universidadCampusEspecializacionData->getAllTbUniversidadCampusEspecializacion();
    }

    public function getAllTbUniversidadCampusEspecializacionNombres() {
        return $this->universidadCampusEspecializacionData->getAllTbUniversidadCampusEspecializacionNombres();
    }

    public function getAllDeletedTbUniversidadCampusEspecializacion() {
        return $this->universidadCampusEspecializacionData->getAllDeletedTbUniversidadCampusEspecializacion();
    }

    public function restoreTbCampusEspecializacion($universidadCampusEspecializacionId) {
        $universidadCampusEspecializacionData = new universidadCampusEspecializacionData();
        return $universidadCampusEspecializacionData->restoreTbCampusEspecializacion($universidadCampusEspecializacionId);
    }

    public function exist($nombre) {
        return $this->universidadCampusEspecializacionData->exist($nombre);
    }

    public function nameExist($nombre, $idUniversidadCampusEspecializacion) {
        return $this->universidadCampusEspecializacionData->nameExists($nombre, $idUniversidadCampusEspecializacion);
    }

    public function autocomplete($term) {
        return $this->universidadCampusEspecializacionData->autocomplete($term);
    }
}
