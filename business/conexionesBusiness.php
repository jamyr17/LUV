<?php

include '../data/conexionesData.php';

class ConexionesBusiness{

    private $conexionesData;

    public function __construct(){
        $this->conexionesData = new ConexionesData();
    }

    public function getAllTbPerfilesPorID($usuariosID) {
        return $this->conexionesData->getAllTbPerfilesPorID($usuariosID);
    }
}