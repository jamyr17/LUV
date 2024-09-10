<?php

include '../data/universidadData.php';

class UniversidadBusiness {

    private $universidadData;

    public function __construct() {
        $this->universidadData = new UniversidadData();
    }

    public function insertTbUniversidad($universidad) {
        return $this->universidadData->insertTbUniversidad($universidad);
    }

    public function updateTbUniversidad($universidad) {
        return $this->universidadData->updateTbUniversidad($universidad);
    }
/*
    public function deleteTbUniversidad($idUniversidad) {
        return $this->universidadData->deleteTbUniversidad($idUniversidad);
    }
*/
    public function checkAssociatedCampus($universidadId) {
        return $this->universidadData->checkAssociatedCampus($universidadId);
    }

    public function deleteUniversityById($universidadId) {
        return $this->universidadData->deleteUniversityById($universidadId);
    }

    public function getAllTbUniversidad() {
        return $this->universidadData->getAllTbUniversidad();
    }

    public function getAllTbUniversidadNombres() {
        return $this->universidadData->getAllTbUniversidadNombres();
    }

    public function getAllDeletedTbUniversidad() {
        return $this->universidadData->getAllDeletedTbUniversidad();
    }
    
    public function exist($nombre) {
        return $this->universidadData->exist($nombre);
    }

    public function getTbUniversidadById($idUniversidad) {
        return $this->universidadData->getTbUniversidadById($idUniversidad);
    }

    public function insertRequestTbUniversidad($universidad) {
        return $this->universidadData->insertRequestTbUniversidad($universidad);
    }

    public function restoreTbUniversidad($idUniversidad) {
        return $this->universidadData->restoreTbUniversidad($idUniversidad);
    }
    
    public function autocomplete($term) {
        return $this->universidadData->autocomplete($term);
    }
}
