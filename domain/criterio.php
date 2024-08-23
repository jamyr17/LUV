<?php

class Criterio {
    private $tbCriterioId;
    private $tbCriterioNombre;
    private $tbCriterioEstado;

    public function __construct($tbCriterioId, $tbCriterioNombre, $tbCriterioEstado) {
        $this->tbCriterioId = $tbCriterioId;
        $this->tbCriterioNombre = $tbCriterioNombre;
        $this->tbCriterioEstado = $tbCriterioEstado;
    }

    // Gets

    public function getTbCriterioId() {
        return $this->tbCriterioId;
    }

    public function getTbCriterioNombre() {
        return $this->tbCriterioNombre;
    }

    public function getTbCriterioEstado() {
        return $this->tbCriterioEstado;
    }
    
    // Sets

    public function setTbCriterioId($tbCriterioId) {
        $this->tbCriterioId = $tbCriterioId;
    }

    public function setTbCriterioNombre($tbCriterioNombre) {
        $this->tbCrNombre = $tbCriterioNombre;
    }

    public function setTbCriterioEstado($tbCriterioEstado) {
        $this->tbCriterioEstado = $tbCriterioEstado;
    }

}
