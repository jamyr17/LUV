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

    public function exist($nombre) {
        return $this->generoData->exist($nombre);
    }
}
?>
