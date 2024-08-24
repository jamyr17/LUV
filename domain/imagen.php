<?php

class Imagen {
    private $tbImagenId;
    private $tbImagenCrudId;
    private $tbImagenRegistroId;
    private $tbImagenNombre;
    private $tbImagenDirectorio;
    private $tbImagenEstado;

    public function __construct($tbImagenId, $tbImagenCrudId, $tbImagenRegistroId, $tbImagenNombre, $tbImagenDirectorio, $tbImagenEstado) {
        $this->tbImagenId = $tbImagenId;
        $this->tbImagenCrudId = $tbImagenCrudId;
        $this->tbImagenRegistroId = $tbImagenRegistroId;
        $this->tbImagenNombre = $tbImagenNombre;
        $this->tbImagenDirectorio = $tbImagenDirectorio;
        $this->tbImagenEstado = $tbImagenEstado;
    }

    public function getTbImagenId() {
        return $this->tbImagenId;
    }

    public function getTbImagenCrudId() {
        return $this->tbImagenCrudId;
    }

    public function getTbImagenRegistroId() {
        return $this->tbImagenRegistroId;
    }

    public function getTbImagenNombre() {
        return $this->tbImagenNombre;
    }

    public function getTbImagenDirectorio() {
        return $this->tbImagenDirectorio;
    }

    public function getTbImagenEstado() {
        return $this->tbImagenEstado;
    }
    
    public function setTbImagenId($tbImagenId) {
        $this->tbImagenId = $tbImagenId;
    }

    public function setTbImagenCrudId($tbImagenCrudId) {
        $this->tbImagenCrudId = $tbImagenCrudId;
    }

    public function setTbImagenRegistroId($tbImagenRegistroId) {
        $this->tbImagenRegistroId = $tbImagenRegistroId;
    }

    public function setTbImagenNombre($tbImagenNombre) {
        $this->tbImagenNombre = $tbImagenNombre;
    }

    public function setTbImagenDirectorio($tbImagenDirectorio) {
        $this->tbImagenDirectorio = $tbImagenDirectorio;
    }

    public function setTbImagenEstado($tbImagenEstado) {
        $this->tbImagenEstado = $tbImagenEstado;
    }

}
