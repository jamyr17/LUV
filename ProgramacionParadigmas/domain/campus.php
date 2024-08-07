<?php

class Campus {
    private $tbCampusId;
    private $tbCampusUniversidadId;
    private $tbCampusNombre;
    private $tbCampusDireccion;
    private $tbCampusEstado;

    public function __construct($tbCampusId, $tbCampusUniversidadId, $tbCampusNombre, $tbCampusDireccion, $tbCampusEstado) {
        $this->tbCampusId = $tbCampusId;
        $this->tbCampusUniversidadId = $tbCampusUniversidadId;
        $this->tbCampusNombre = $tbCampusNombre;
        $this->tbCampusDireccion = $tbCampusDireccion;
        $this->tbCampusEstado = $tbCampusEstado;
    }

    public function getTbCampusId() {
        return $this->tbCampusId;
    }

    public function setTbCampusId($tbCampusId) {
        $this->tbCampusId = $tbCampusId;
    }

    public function getTbCampusUniversidadId() {
        return $this->tbCampusUniversidadId;
    }

    public function setTbCampusUniversidadId($tbCampusUniversidadId) {
        $this->tbCampusUniversidadId = $tbCampusUniversidadId;
    }

    public function getTbCampusNombre() {
        return $this->tbCampusNombre;
    }

    public function setTbCampusNombre($tbCampusNombre) {
        $this->tbCampusNombre = $tbCampusNombre;
    }

    public function getTbCampusDireccion() {
        return $this->tbCampusDireccion;
    }

    public function setTbCampusDireccion($tbCampusDireccion) {
        $this->tbCampusDireccion = $tbCampusDireccion;
    }

    public function getTbCampusEstado() {
        return $this->tbCampusEstado;
    }

    public function setTbCampusEstado($tbCampusEstado) {
        $this->tbCampusEstado = $tbCampusEstado;
    }
}

