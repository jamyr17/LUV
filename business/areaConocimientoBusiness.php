<?php

include '../data/areaConocimientoData.php';

class AreaConocimientoBusiness {

    private $areaConocimientoData;

    public function __construct() {
        $this->areaConocimientoData = new AreaConocimientoData();
    }

    public function insertTbAreaConocimiento($areaConocimiento) {
        return $this->areaConocimientoData->insertTbAreaConocimiento($areaConocimiento);
    }

    public function insertRequestTbAreaConocimiento($areaConocimiento) {
        return $this->areaConocimientoData->insertRequestTbAreaConocimiento($areaConocimiento);
    }

    public function updateTbAreaConocimiento($areaConocimiento) {
        return $this->areaConocimientoData->updateTbAreaConocimiento($areaConocimiento);
    }

    public function deleteTbAreaConocimiento($idAreaConocimiento) {
        return $this->areaConocimientoData->deleteTbAreaConocimiento($idAreaConocimiento);
    }

    public function getAllTbAreaConocimiento() {
        return $this->areaConocimientoData->getAllTbAreaConocimiento();
    }

    public function getAllTbAreaConocimientoNombres() {
        return $this->areaConocimientoData->getAllTbAreaConocimientoNombres();
    }

    public function getAllDeletedTbAreaConocimiento() {
        return $this->areaConocimientoData->getAllDeletedTbAreaConocimiento();
    }

    public function restoreTbCampusAreaConocimiento($idAreaConocimiento) {
        $areaConocimientoData = new AreaConocimientoData();
        return $areaConocimientoData->restoreTbCampusAreaConocimiento($idAreaConocimiento);
    }

    public function exist($nombre) {
        return $this->areaConocimientoData->exist($nombre);
    }

    public function nameExist($nombre, $idAreaConocimiento){
        return $this->areaConocimientoData->nameExists($nombre, $idAreaConocimiento);
    }

    public function autocomplete($term) {
        return $this->areaConocimientoData->autocomplete($term);
    }
}
