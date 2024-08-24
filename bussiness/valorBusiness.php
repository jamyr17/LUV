<?php

include '../data/valorData.php';

class ValorBusiness {

    private $valorData;

    public function __construct() {
        $this->valorData = new ValorData();
    }

    public function insertTbValor($valor) {
        return $this->valorData->insertTbValor($valor);
    }

    public function updateTbValor($valor) {
        return $this->valorData->updateTbValor($valor);
    }

    public function deleteTbValor($idValor) {
        return $this->valorData->deleteTbValor($idValor);
    }

    public function deleteForeverTbValor($idValor) {
        return $this->valorData->deleteForeverTbValor($idValor);
    }

    public function getAllTbValor() {
        return $this->valorData->getAllTbValor();
    }

    public function getAllTbValorByCriterioId($criterioId) {
        return $this->valorData->getAllTbValorByCriterioId($criterioId);
    }

    public function getAllDeletedTbValor() {
        return $this->valorData->getAllTbValorDeleted();
    }

    public function exist($nombre) {
        return $this->valorData->exist($nombre);
    }

}
?>
