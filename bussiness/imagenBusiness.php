<?php

include '../data/imagenData.php';

class ImagenBusiness {

    private $imagenData;

    public function __construct() {
        $this->imagenData = new imagenData();
    }

    public function insertTbimagen($imagen) {
        return $this->imagenData->insertTbimagen($imagen);
    }

    public function updateTbimagen($imagen) {
        return $this->imagenData->updateTbimagen($imagen);
    }

    public function deleteTbimagen($idimagen) {
        return $this->imagenData->deleteTbimagen($idimagen);
    }

    public function getAllTbImagen() {
        return $this->imagenData->getAllTbImagen();
    }

    public function exist($nombre) {
        return $this->imagenData->exist($nombre);
    }
}
