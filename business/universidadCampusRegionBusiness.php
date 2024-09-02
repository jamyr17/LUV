<?php

include '../data/universidadCampusRegionData.php';

class UniversidadCampusRegionBusiness {

    private $universidadCampusRegionData;

    public function __construct() {
        $this->universidadCampusRegionData = new UniversidadCampusRegionData();
    }

    public function insertTbUniversidadCampusRegion($campusRegion) {
        return $this->universidadCampusRegionData->insertTbUniversidadCampusRegion($campusRegion);
    }

    public function updateTbUniversidadCampusRegion($campusRegion) {
        return $this->universidadCampusRegionData->updateTbUniversidadCampusRegion($campusRegion);
    }

    public function deleteTbUniversidadCampusRegion($idCampusRegion) {
        return $this->universidadCampusRegionData->deleteTbUniversidadCampusRegion($idCampusRegion);
    }

    public function deleteForeverTbUniversidadCampusRegion($idCampusRegion) {
        return $this->universidadCampusRegionData->deleteForeverTbUniversidadCampusRegion($idCampusRegion);
    }

    public function getAllTbUniversidadCampusRegion() {
        return $this->universidadCampusRegionData->getAllTbUniversidadCampusRegion();
    }

    public function getAllDeletedTbUniversidadCampusRegion() {
        return $this->universidadCampusRegionData->getAllDeletedTbUniversidadCampusRegion();
    }

    public function exist($nombre) {
        return $this->universidadCampusRegionData->exist($nombre);
    }

    public function nameExist($nombre, $idCampusRegion) {
        return $this->universidadCampusRegionData->nameExists($nombre, $idCampusRegion);
    }

}
