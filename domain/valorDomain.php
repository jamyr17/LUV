<?php

class Valor {
    private $tbValorId;
    private $tbValorNombre;
    private $tbCriterioId;
    private $tbValorEstado;

    // Constructor
    public function __construct($tbValorId, $tbValorNombre, $tbCriterioId, $tbValorEstado) {
        $this->tbValorId = $tbValorId;
        $this->tbValorNombre = $tbValorNombre;
        $this->tbCriterioId = $tbCriterioId;
        $this->tbValorEstado = $tbValorEstado;
    }
    
    public function getTbValorId() {
        return $this->tbValorId;
    }

    public function setTbValorId($tbValorId) {
        $this->tbValorId = $tbValorId;
    }

    public function getTbValorNombre() {
        return $this->tbValorNombre;
    }

    public function setTbValorNombre($tbValorNombre) {
        $this->tbValorNombre = $tbValorNombre;
    }

    public function getTbCriterioId() {
        return $this->tbCriterioId;
    }

    public function setTbCriterioId($tbCriterioId) {
        $this->tbCriterioId = $tbCriterioId;
    }

    public function getTbValorEstado() {
        return $this->tbValorEstado;
    }

    public function setTbValorEstado($tbValorEstado) {
        $this->tbValorEstado = $tbValorEstado;
    }
}

?>
