<?php

include '../data/loginData.php';

class UsuarioBussiness{

    private $loginData;

    public function __construct(){
        $this->loginData = new LoginData();
    }

    public function loginValidation($nombreUsuario, $contrasena){
        return $this->loginData->validation($nombreUsuario, $contrasena);
    }

}