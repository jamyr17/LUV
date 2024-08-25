<?php

include '../data/WantedProfileData.php';

class WantedProfileBussiness{

    private $wantedProfileData;

    public function __construct(){
        $this->wantedProfileData = new WantedProfileData();
    }

    public function insertTbPerfilDeseado($criterio, $valor, $porcentaje){
        return $this->wantedProfileData->insertTbPerfilDeseado($criterio, $valor, $porcentaje);
    }

    public function getAllTbPerfiles() {
        return $this->wantedProfileData->getAllTbPerfiles();
    }

}

?>