<?php

include '../data/generoData.php';

class GeneroBusiness {

    private $generoData;

    public function __construct() {
        $this->generoData = new GeneroData();
    }

    public function insertTbGenero($genero) {
        return $this->generoData->insertTbGenero($genero);
    }

    public function updateTbGenero($genero) {
        return $this->generoData->updateTbGenero($genero);
    }

    public function deleteTbGenero($idGenero) {
        return $this->generoData->deleteTbGenero($idGenero);
    }

    public function deleteForeverTbGenero($idGenero) {
        return $this->generoData->deleteForeverTbGenero($idGenero);
    }

    public function getAllTbGenero() {
        return $this->generoData->getAllTbGenero();
    }

    public function getAllDeletedTbGenero() {
        return $this->generoData->getAllDeletedTbGenero();
    }

    public function restoreTbGenero($idGenero) {
        $generoData = new GeneroData();
        return $generoData->restoreTbGenero($idGenero);
    }

    public function exist($nombre) {
        return $this->generoData->exist($nombre);
    }

    public function insertRequestTbGenero($genero){
        return $this->generoData->insertRequestTbGenero($genero);
    }

    public function nameExist($nombre, $idGenero) {
        return $this->generoData->nameExists($nombre);
    }

    public function autocomplete($term) {
        return $this->generoData->autocomplete($term);
    }

}
