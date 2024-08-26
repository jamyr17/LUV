<?php

class UniversidadCampusColectivo {
    private $tbUniversidadCampusColectivoId;
    private $tbUniversidadCampusColectivoNombre;
    private $tbUniversidadCampusColectivoDescripcion;
    private $tbUniversidadCampusColectivoEstado;

    public function __construct($tbUniversidadCampusColectivoId, $tbUniversidadCampusColectivoNombre, $tbUniversidadCampusColectivoDescripcion, $tbUniversidadCampusColectivoEstado) {
        $this->tbUniversidadCampusColectivoId = $tbUniversidadCampusColectivoId;
        $this->tbUniversidadCampusColectivoNombre = $tbUniversidadCampusColectivoNombre;
        $this->tbUniversidadCampusColectivoDescripcion = $tbUniversidadCampusColectivoDescripcion;
        $this->tbUniversidadCampusColectivoEstado = $tbUniversidadCampusColectivoEstado;
    }

    public function getTbUniversidadCampusColectivoId() {
        return $this->tbUniversidadCampusColectivoId;
    }

    public function getTbUniversidadCampusColectivoNombre() {
        return $this->tbUniversidadCampusColectivoNombre;
    }

    public function getTbUniversidadCampusColectivoDescripcion() {
        return $this->tbUniversidadCampusColectivoDescripcion;
    }

    public function getTbUniversidadCampusColectivoEstado() {
        return $this->tbUniversidadCampusColectivoEstado;
    }

    public function setTbUniversidadCampusColectivoId($tbUniversidadCampusColectivoId) {
        $this->tbUniversidadCampusColectivoId = $tbUniversidadCampusColectivoId;
    }

    public function setTbUniversidadCampusColectivoNombre($tbUniversidadCampusColectivoNombre) {
        $this->tbUniversidadCampusColectivoNombre = $tbUniversidadCampusColectivoNombre;
    }

    public function setTbUniversidadCampusColectivoDescripcion($tbUniversidadCampusColectivoDescripcion) {
        $this->tbUniversidadCampusColectivoDescripcion = $tbUniversidadCampusColectivoDescripcion;
    }

    public function setTbUniversidadCampusColectivoEstado($tbUniversidadCampusColectivoEstado) {
        $this->tbUniversidadCampusColectivoEstado = $tbUniversidadCampusColectivoEstado;
    }
}
?>
