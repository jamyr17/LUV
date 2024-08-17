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

    public function getAllDeletedTbOrientacionSexual() {
        return $this->orientacionSexualData->getAllDeletedTbOrientacionSexual();
    }

    public function exist($nombre) {
        return $this->orientacionSexualData->exist($nombre);
    }
    public function insertRequestTbOrientacionSexual($orientacionSexual) {
        return $this->orientacionSexualData->insertRequestTbOrientacionSexual($orientacionSexual);
    }


}
?>
