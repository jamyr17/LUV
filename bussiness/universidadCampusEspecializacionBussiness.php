<?php

include '../data/universidadCampusEspecializacionData.php';

class UniversidadCampusEspecializacionBussiness{

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

    public function getAllDeletedTbUniversidadCampusEspecializacion() {
        return $this->universidadCampusEspecializacionData->getAllDeletedTbUniversidadCampusEspecializacion();
    }

    public function exist($nombre) {
        return $this->universidadCampusEspecializacionData->exist($nombre);
    }

    public function nameExist($nombre, $idUniversidadCampusEspecializacion) {
        return $this->universidadCampusEspecializacionData->nameExists($nombre, $idUniversidadCampusEspecializacion);
    }

}
?>
