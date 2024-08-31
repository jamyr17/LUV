<?php

include '../data/personalProfileData.php';

class PersonalProfileBusiness{

    private $personalProfileData;

    public function __construct(){
        $this->personalProfileData = new PersonalProfileData();
    }

    public function insertTbPerfilPersonal($criterio, $valor, $usuarioId){
        return $this->personalProfileData->insertTbPerfilPersonal($criterio, $valor, $usuarioId);
    }

    public function profileExists($usuarioId){
        return $this->personalProfileData->profileExists($usuarioId);
    }

    public function updateTbPerfilPersonal($criterio, $valor, $usuarioId){
        return $this->personalProfileData->updateTbPerfilPersonal($criterio, $valor, $usuarioId);
    }

}
