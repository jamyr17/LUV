<?php

include '../data/universidadData.php';

class UniversidadBusiness {

    private $universidadData;

    public function __construct() {
        $this->universidadData = new UniversidadData();
    }

    public function insertTbUniversidad($universidad) {
        return $this->universidadData->insertTbUniversidad($universidad);
    }

    public function updateTbUniversidad($universidad) {
        return $this->universidadData->updateTbUniversidad($universidad);
    }

    public function deleteTbUniversidad($idUniversidad) {
        return $this->universidadData->deleteTbUniversidad($idUniversidad);
    }

    public function getAllTbUniversidad() {
        return $this->universidadData->getAllTbUniversidad();
    }

    public function getAllDeletedTbUniversidad() {
        return $this->universidadData->getAllDeletedTbUniversidad();
    }

    public function exist($nombre) {
        return $this->universidadData->exist($nombre);
    }

}
?>
