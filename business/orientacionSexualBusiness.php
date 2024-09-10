<?php

include '../data/orientacionSexualData.php';

class OrientacionSexualBusiness {

    private $orientacionSexualData;

    public function __construct() {
        $this->orientacionSexualData = new OrientacionSexualData();
    }

    public function insertTbOrientacionSexual($orientacionSexual) {
        return $this->orientacionSexualData->insertTbOrientacionSexual($orientacionSexual);
    }

    public function updateTbOrientacionSexual($orientacionSexual) {
        return $this->orientacionSexualData->updateTbOrientacionSexual($orientacionSexual);
    }

    public function deleteTbOrientacionSexual($idOrientacionSexual) {
        return $this->orientacionSexualData->deleteTbOrientacionSexual($idOrientacionSexual);
    }

    public function getAllTbOrientacionSexual() {
        return $this->orientacionSexualData->getAllTbOrientacionSexual();
    }

    public function getAllTbOrientacionSexualNombres() {
        return $this->orientacionSexualData->getAllTbOrientacionSexualNombres();
    }

    public function getAllDeletedTbOrientacionSexual() {
        return $this->orientacionSexualData->getAllDeletedTbOrientacionSexual();
    }

    public function restoreTbCampusOrientacionSexual($idOrientacionSexual) {
        $orientacionSexualData = new OrientacionSexualData();
        return $orientacionSexualData->restoreTbCampusOrientacionSexual($idOrientacionSexual);
    }

    public function exist($nombre) {
        return $this->orientacionSexualData->exist($nombre);
    }
    public function insertRequestTbOrientacionSexual($orientacionSexual) {
        return $this->orientacionSexualData->insertRequestTbOrientacionSexual($orientacionSexual);
    }

    public function nameExist($nombre, $idOrientacionSexual) {
        return $this->orientacionSexualData->nameExists($nombre, $idOrientacionSexual);
    }

    public function autocomplete($term) {
        return $this->orientacionSexualData->autocomplete($term);
    }

}
