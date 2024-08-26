<?php

include '../data/usuarioData.php';

class UsuarioBusiness{

    private $usuarioData;

    public function __construct(){
        $this->usuarioData = new UsuarioData();
    }

    public function loginValidation($nombreUsuario, $contrasena){
        return $this->usuarioData->validation($nombreUsuario, $contrasena);
    }

    public function getIdByName($nombreUsuario){
        return $this->usuarioData->getUsuarioId($nombreUsuario);
    }

}