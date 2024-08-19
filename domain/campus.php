<?php

class Campus {
    private $tbCampusId;
    private $tbCampusUniversidadId;
    private $tbCampusRegionId;
    private $tbCampusNombre;
    private $tbCampusDireccion;
    private $tbCampusLatitud;
    private $tbCampusLongitud;
    private $tbCampusEstado;

<<<<<<< HEAD
    public function __construct($tbCampusId, $tbCampusUniversidadId, $tbCampusNombre, $tbCampusDireccion, $tbCampusLatitud, $tbCampusLongitud, $tbCampusEstado) {
=======
    public function __construct($tbCampusId, $tbCampusUniversidadId, $tbCampusRegionId,$tbCampusNombre, $tbCampusDireccion, $tbCampusEstado) {
>>>>>>> 9189827d68541c3fd16267407bb2cc042458f095
        $this->tbCampusId = $tbCampusId;
        $this->tbCampusUniversidadId = $tbCampusUniversidadId;
        $this->tbCampusRegionId = $tbCampusRegionId;
        $this->tbCampusNombre = $tbCampusNombre;
        $this->tbCampusDireccion = $tbCampusDireccion;
        $this->tbCampusLatitud = $tbCampusLatitud;
        $this->tbCampusLongitud = $tbCampusLongitud;
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

    public function getTbCampusRegionId() {
        return $this->tbCampusRegionId;
    }

    public function setTbCampusRegionId($tbCampusRegionId) {
        $this->tbCampusRegionId = $tbCampusRegionId;
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

    public function getTbCampusLatitud() {
        return $this->tbCampusLatitud;
    }

    public function setTbCampusLatitud($tbCampusLatitud) {
        $this->tbCampusLatitud = $tbCampusLatitud;
    }

    public function getTbCampusLongitud() {
        return $this->tbCampusLongitud;
    }

    public function setTbCampusLongitud($tbCampusLongitud) {
        $this->tbCampusLongitud = $tbCampusLongitud;
    }

    public function getTbCampusEstado() {
        return $this->tbCampusEstado;
    }

    public function setTbCampusEstado($tbCampusEstado) {
        $this->tbCampusEstado = $tbCampusEstado;
    }
}

