<?php

class Universidad {
    private $tbUniversidadId;
    private $tbUniversidadNombre;
    private $tbUniversidadEstado;

    public function __construct($tbUniversidadId, $tbUniversidadNombre, $tbUniversidadEstado) {
        $this->tbUniversidadId = $tbUniversidadId;
        $this->tbUniversidadNombre = $tbUniversidadNombre;
        $this->tbUniversidadEstado = $tbUniversidadEstado;
    }

    public function getTbUniversidadId() {
        return $this->tbUniversidadId;
    }

    public function getTbUniversidadNombre() {
        return $this->tbUniversidadNombre;
    }

    public function getTbUniversidadEstado() {
        return $this->tbUniversidadEstado;
    }

    public function setTbUniversidadId($tbUniversidadId) {
        $this->tbUniversidadId = $tbUniversidadId;
    }

    public function setTbUniversidadNombre($tbUniversidadNombre) {
        $this->tbUniversidadNombre = $tbUniversidadNombre;
    }

    public function setTbUniversidadEstado($tbUniversidadEstado) {
        $this->tbUniversidadEstado = $tbUniversidadEstado;
    }

}
