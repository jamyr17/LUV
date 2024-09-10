<?php

include '../data/wantedProfileData.php';

class WantedProfileBusiness{

    private $wantedProfileData;

    public function __construct(){
        $this->wantedProfileData = new WantedProfileData();
    }

    public function insertTbPerfilDeseado($criterio, $valor, $porcentaje, $usuarioId){
        return $this->wantedProfileData->insertTbPerfilDeseado($criterio, $valor, $porcentaje, $usuarioId);
    }

    public function updateTbPerfilDeseado($criterio, $valor, $porcentaje, $usuarioId){
        return $this->wantedProfileData->updateTbPerfilDeseado($criterio, $valor, $porcentaje, $usuarioId);
    }

    public function profileExists($usuarioId){
        return $this->wantedProfileData->profileExists($usuarioId);
    }

    public function getAllTbPerfiles() {
        return $this->wantedProfileData->getAllTbPerfiles();
    }

    public function perfilDeseadoByIdUsuario($usuarioId){
        return $this->wantedProfileData->perfilDeseadoByIdUsuario($usuarioId);
    }


}
