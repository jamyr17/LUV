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
        $campusData = new CampusData();
        return $campusData->getAllDeletedTbCampus();
    }

    public function restoreTbCampus($idCampus) {
        $campusData = new CampusData();
        return $campusData->restoreTbCampus($idCampus);
    }

    public function exist($nombre) {
        return $this->campusData->exist($nombre);
    }

    public function nameExist($nombre, $idCampus){
        return $this->campusData->nameExists($nombre, $idCampus);
    }

    public function insertRequestTbCampus($campus){
        return $this->campusData->insertRequestTbCampus($campus);
    }

    public function autocomplete($term) {
        return $this->campusData->autocomplete($term);
    }

}
