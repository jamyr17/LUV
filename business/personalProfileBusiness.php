<?php

include '../data/personalProfileData.php';

class PersonalProfileBusiness{

    private $personalProfileData;

    public function __construct(){
        $this->personalProfileData = new PersonalProfileData();
    }

    public function insertTbPerfilPersonal($criterio, $valor,  $areaConocimiento, $genero, $orientacionSexual, $universidad, $campus, $colectivosString, $usuarioId){
        return $this->personalProfileData->insertTbPerfilPersonal($criterio, $valor, $areaConocimiento, $genero, $orientacionSexual, $universidad, $campus, $colectivosString, $usuarioId);
    }

    public function profileExists($usuarioId){
        return $this->personalProfileData->profileExists($usuarioId);
    }

    public function updateTbPerfilPersonal($criterio, $valor,  $areaConocimiento, $genero, $orientacionSexual, $universidad, $campus, $colectivosString, $usuarioId){
        return $this->personalProfileData->updateTbPerfilPersonal($criterio, $valor,  $areaConocimiento, $genero, $orientacionSexual, $universidad, $campus, $colectivosString, $usuarioId);
    }

    public function perfilPersonalByIdUsuario($usuarioId){
        return $this->personalProfileData->perfilPersonalByIdUsuario($usuarioId);
    }

    public function puedeBuscarConexiones($usuarioId){
        return $this->personalProfileData->puedeBuscarConexiones($usuarioId);
    }

    public function getPerfilesPersonalesPorNombres($nombresUsuario){
        return $this->personalProfileData->getPerfilesPersonalesPorNombres($nombresUsuario);
    }
    
}
