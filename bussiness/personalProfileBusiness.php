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


}