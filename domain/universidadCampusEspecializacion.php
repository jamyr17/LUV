<?php

class universidadCampusEspecializacion {
    private $tbUniversidadCampusEspecializacionId;
    private $tbUniversidadCampusEspecializacionNombre;
    private $tbUniversidadCampusEspecializacionDescripcion;
    private $tbUniversidadCampusEspecializacionEstado;

    public function __construct($tbUniversidadCampusEspecializacionId, $tbUniversidadCampusEspecializacionNombre, $tbUniversidadCampusEspecializacionDescripcion, $tbUniversidadCampusEspecializacionEstado) {
        $this->tbUniversidadCampusEspecializacionId = $tbUniversidadCampusEspecializacionId;
        $this->tbUniversidadCampusEspecializacionNombre = $tbUniversidadCampusEspecializacionNombre;
        $this->tbUniversidadCampusEspecializacionDescripcion = $tbUniversidadCampusEspecializacionDescripcion;
        $this->tbUniversidadCampusEspecializacionEstado = $tbUniversidadCampusEspecializacionEstado;
    }

    public function getTbUniversidadCampusEspecializacionId() {
        return $this->tbUniversidadCampusEspecializacionId;
    }

    public function getTbUniversidadCampusEspecializacionNombre() {
        return $this->tbUniversidadCampusEspecializacionNombre;
    }

    public function getTbUniversidadCampusEspecializacionDescripcion() {
        return $this->tbUniversidadCampusEspecializacionDescripcion;
    }

    public function getTbUniversidadCampusEspecializacionEstado() {
        return $this->tbUniversidadCampusEspecializacionEstado;
    }

    public function setTbUniversidadCampusEspecializacionId($tbUniversidadCampusEspecializacionId) {
        $this->tbUniversidadCampusEspecializacionId = $tbUniversidadCampusEspecializacionId;
    }

    public function setTbUniversidadCampusEspecializacionNombre($tbUniversidadCampusEspecializacionNombre) {
        $this->tbUniversidadCampusEspecializacionNombre = $tbUniversidadCampusEspecializacionNombre;
    }

    public function setTbUniversidadCampusEspecializacionDescripcion($tbUniversidadCampusEspecializacionDescripcion) {
        $this->tbUniversidadCampusEspecializacionDescripcion = $tbUniversidadCampusEspecializacionDescripcion;
    }

    public function setTbUniversidadCampusEspecializacionEstado($tbUniversidadCampusEspecializacionEstado) {
        $this->tbUniversidadCampusEspecializacionEstado = $tbUniversidadCampusEspecializacionEstado;
    }
}

?>
