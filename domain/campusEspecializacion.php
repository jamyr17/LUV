<?php

class CampusEspecializacion{
    private $tbCampusEspecializacionId;
    private $tbCampusEspecializacionNombre;
    private $tbCampusEspecializacionDescripcion;
    private $tbCampusEspecializacionEstado;

    public function __construct($tbCampusEspecializacionId, $tbCampusEspecializacionNombre, $tbCampusEspecializacionDescripcion, $tbCampusEspecializacionEstado){
        $this->tbCampusEspecializacionId = $tbCampusEspecializacionId;
        $this->tbCampusEspecializacionNombre = $tbCampusEspecializacionNombre;
        $this->tbCampusEspecializacionDescripcion = $tbCampusEspecializacionDescripcion;
        $this->tbCampusEspecializacionEstado = $tbCampusEspecializacionEstado;
    }

    public function getTbCampusEspecializacionId(){
        return $this->tbCampusEspecializacionId;
    }

    public function getTbCampusEspecializacionNombre(){
        return $this->tbCampusEspecializacionNombre;
    }

    public function getTbCampusEspecializacionDescripcion(){
        return $this->tbCampusEspecializacionDescripcion;
    }

    public function getTbCampusEspecializacionEstado(){
        return $this->tbCampusEspecializacionEstado;
    }

    public function setTbCampusEspecializacionId($tbCampusEspecializacionId){
        $this->tbCampusEspecializacionId = $tbCampusEspecializacionId;
    }

    public function setTbCampusEspecializacionNombre($tbCampusEspecializacionNombre){
        $this->tbCampusEspecializacionNombre = $tbCampusEspecializacionNombre;
    }

    public function setTbCampusEspecializacionDescripcion($tbCampusEspecializacionDescripcion){
        $this->tbCampusEspecializacionDescripcion = $tbCampusEspecializacionDescripcion;
    }

    public function setTbCampusEspecializacionEstado($tbCampusEspecializacionEstado){
        $this->tbCampusEspecializacionEstado = $tbCampusEspecializacionEstado;
    }

}
