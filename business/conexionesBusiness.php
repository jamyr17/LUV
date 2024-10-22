<?php

include '../data/conexionesData.php';

class ConexionesBusiness{

    private $conexionesData;

    public function __construct(){
        $this->conexionesData = new ConexionesData();
    }

    public function getAllTbPerfiles() {
        return $this->conexionesData->getAllTbPerfiles();
    }
}