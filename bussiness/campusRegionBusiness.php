<?php

include '../data/campusRegion.php';

class CampusRegionBusiness {

    private $campusRegionData;

    public function __construct() {
        $this->campusRegionData = new CampusRegionData();
    }

    public function insertTbCampusRegion($campusRegion) {
        return $this->campusRegionData->insertTbCampusRegion($campusRegion);
    }

    public function updateTbCampusRegion($campusRegion) {
        return $this->campusRegionData->updateTbCampusRegion($campusRegion);
    }

    public function deleteTbCampusRegion($idCampusRegion) {
        return $this->campusRegionData->deleteTbCampusRegion($idCampusRegion);
    }

    public function deleteForeverTbCampusRegion($idCampusRegion) {
        return $this->campusRegionData->deleteForeverTbCampusRegion($idCampusRegion);
    }

    public function getAllTbCampusRegion() {
        return $this->campusRegionData->getAllTbCampusRegion();
    }

    public function getAllDeletedTbCampusRegion() {
        return $this->campusRegionData->getAllDeletedTbCampusRegion();
    }

    public function exist($nombre) {
        return $this->campusRegionData->exist($nombre);
    }

    public function nameExist($nombre, $idCampusRegion) {
        return $this->campusRegionData->nameExists($nombre);
    }

}
?>
