<?php

class Genero {
    private $tbGeneroId;
    private $tbGeneroNombre;
    private $tbGeneroDescripcion;
    private $tbGeneroEstado;

    public function __construct($tbGeneroId, $tbGeneroNombre, $tbGeneroDescripcion, $tbGeneroEstado) {
        $this->tbGeneroId = $tbGeneroId;
        $this->tbGeneroNombre = $tbGeneroNombre;
        $this->tbGeneroDescripcion = $tbGeneroDescripcion;
        $this->tbGeneroEstado = $tbGeneroEstado;
    }
    // Gets
    public function getTbGeneroId() {
        return $this->tbGeneroId;
    }

    public function getTbGeneroNombre() {
        return $this->tbGeneroNombre;
    }

    public function getTbGeneroDescripcion() {
        return $this->tbGeneroDescripcion;
    }

    public function getTbGeneroEstado() {
        return $this->tbGeneroEstado;
    }
    
    // Sets
    public function setTbGeneroId($tbGeneroId) {
        $this->tbGeneroId = $tbGeneroId;
    }

    public function setTbGeneroNombre($tbGeneroNombre) {
        $this->tbGeneroNombre = $tbGeneroNombre;
    }

    public function setTbGeneroDescripcion($tbGeneroDescripcion) {
        $this->tbGeneroDescripcion = $tbGeneroDescripcion;
    }

    public function setTbGeneroEstado($tbGeneroEstado) {
        $this->tbGeneroEstado = $tbGeneroEstado;
    }

}
