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

    public function getAllTbValorNombres() {
        return $this->valorData->getAllTbValorNombres();
    }

    public function getAllDeletedTbValor() {
        return $this->valorData->getAllDeletedTbValor();
    }

    public function restoreTbValor($idValor) {
        $valorData = new ValorData();
        return $valorData->restoreTbValor($idValor);
    }

    public function exist($nombre) {
        return $this->valorData->exist($nombre);
    }

    public function nameExist($nombre, $idValor){
        return $this->valorData->nameExists($nombre, $idValor);
    }

    public function autocomplete($term) {
        return $this->valorData->autocomplete($term);
    }
}
