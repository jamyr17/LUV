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

    public function deleteForeverTbimagen($idimagen) {
        return $this->imagenData->deleteForeverTbimagen($idimagen);
    }

    public function getAllTbimagen() {
        return $this->imagenData->getAllTbimagen();
    }

    public function getAllDeletedTbimagen() {
        return $this->imagenData->getAllDeletedTbimagen();
    }

    public function exist($nombre) {
        return $this->imagenData->exist($nombre);
    }

    public function insertRequestTbimagen($imagen){
        return $this->imagenData->insertRequestTbimagen($imagen);
    }

}
