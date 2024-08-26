<?php

include '../data/usuarioData.php';

class UsuarioBusiness{

    private $usuarioData;

    public function __construct(){
        $this->usuarioData = new UsuarioData();
    }

    public function insertTbUsuario($cedula, $primerNombre, $primerApellido, $nombreUsuario, $contrasena){
        return $this->usuarioData->insertTbUsuario($cedula, $primerNombre, $primerApellido, $nombreUsuario, $contrasena);
    }

    public function loginValidation($nombreUsuario, $contrasena){
        return $this->usuarioData->validation($nombreUsuario, $contrasena);
    }

    public function getIdByName($nombreUsuario){
        return $this->usuarioData->getUsuarioId($nombreUsuario);
    }

}