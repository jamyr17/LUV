<?php

include '../data/universidadCampusColectivoData.php';

class universidadCampusColectivoBusiness {

    private $universidadCampusColectivoData;

    public function __construct() {
        $this->universidadCampusColectivoData = new universidadCampusColectivoData();
    }

    public function insertTbUniversidadCampusColectivo($universidadCampusColectivo) {
        return $this->universidadCampusColectivoData->insertTbUniversidadCampusColectivo($universidadCampusColectivo);
    }

    public function updateTbUniversidadCampusColectivo($universidadCampusColectivo) {
        return $this->universidadCampusColectivoData->updateTbUniversidadCampusColectivo($universidadCampusColectivo);
    }

    public function deleteTbUniversidadCampusColectivo($idUniversidadCampusColectivo) {
        return $this->universidadCampusColectivoData->deleteTbUniversidadCampusColectivo($idUniversidadCampusColectivo);
    }

    public function getAllTbUniversidadCampusColectivo() {
        return $this->universidadCampusColectivoData->getAllTbUniversidadCampusColectivo();
    }

    public function getAllDeletedTbUniversidadCampusColectivo() {
        return $this->universidadCampusColectivoData->getAllDeletedTbUniversidadCampusColectivo();
    }

    public function exist($nombre) {
        return $this->universidadCampusColectivoData->exist($nombre);
    }

    public function nameExist($nombre, $idUniversidadCampusColectivo) {
        return $this->universidadCampusColectivoData->nameExists($nombre, $idUniversidadCampusColectivo);
    }

}
