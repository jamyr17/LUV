<?php

include '../data/campusColectivoData.php';

class CampusColectivoBussiness {

    private $campusColectivoData;

    public function __construct() {
        $this->campusColectivoData = new CampusColectivoData();
    }

    public function insertTbCampusColectivo($campusColectivo) {
        return $this->campusColectivoData->insertTbCampusColectivo($campusColectivo);
    }

    public function updateTbCampusColectivo($campusColectivo) {
        return $this->campusColectivoData->updateTbCampusColectivo($campusColectivo);
    }

    public function deleteTbCampusColectivo($idCampusColectivo) {
        return $this->campusColectivoData->deleteTbCampusColectivo($idCampusColectivo);
    }

    public function getAllTbCampusColectivo() {
        return $this->campusColectivoData->getAllTbCampusColectivo();
    }

    public function getAllDeletedTbCampusColectivo() {
        return $this->campusColectivoData->getAllDeletedTbCampusColectivo();
    }

    public function exist($nombre) {
        return $this->campusColectivoData->exist($nombre);
    }

    public function nameExist($nombre, $idCampusColectivo) {
        return $this->campusColectivoData->nameExists($nombre);
    }

}
?>
