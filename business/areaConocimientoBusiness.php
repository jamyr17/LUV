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

    public function updateTbAreaConocimiento($areaConocimiento) {
        return $this->areaConocimientoData->updateTbAreaConocimiento($areaConocimiento);
    }

    public function deleteTbAreaConocimiento($idAreaConocimiento) {
        return $this->areaConocimientoData->deleteTbAreaConocimiento($idAreaConocimiento);
    }

    public function getAllTbAreaConocimiento() {
        return $this->areaConocimientoData->getAllTbAreaConocimiento();
    }

    public function getAllDeletedTbAreaConocimiento() {
        return $this->areaConocimientoData->getAllDeletedTbAreaConocimiento();
    }

    public function exist($nombre) {
        return $this->areaConocimientoData->exist($nombre);
    }

    public function nameExist($nombre, $idAreaConocimiento){
        return $this->areaConocimientoData->nameExists($nombre);
    }

}
