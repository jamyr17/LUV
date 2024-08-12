<?php

include '../data/campusData.php';

class CampusBusiness {

    private $campusData;

    public function __construct() {
        $this->campusData = new CampusData();
    }

    public function insertTbCampus($universidad) {
        return $this->campusData->insertTbCampus($universidad);
    }

    public function updateTbCampus($universidad) {
        return $this->campusData->updateTbCampus($universidad);
    }

    public function deleteTbCampus($idUniversidad) {
        return $this->campusData->deleteTbCampus($idUniversidad);
    }

    public function getAllTbCampus() {
        return $this->campusData->getAllTbCampus();
    }

    public function getAllTbCampusByUniversidad($idU) {
        return $this->campusData->getAllTbCampusByUniversidad($idU);
    }

    public function getAllDeletedTbCampus() {
        return $this->campusData->getAllDeletedTbCampus();
    }

    public function exist($nombre) {
        return $this->campusData->exist($nombre);
    }

}
?>
