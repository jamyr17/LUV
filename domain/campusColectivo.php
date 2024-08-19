<?php

class CampusColectivo {
    private $tbCampusColectivoId;
    private $tbCampusColectivoNombre;
    private $tbCampusColectivoDescripcion;
    private $tbCampusColectivoEstado;

    public function __construct($tbCampusColectivoId, $tbCampusColectivoNombre, $tbCampusColectivoDescripcion, $tbCampusColectivoEstado) {
        $this->tbCampusColectivoId = $tbCampusColectivoId;
        $this->tbCampusColectivoNombre = $tbCampusColectivoNombre;
        $this->tbCampusColectivoDescripcion = $tbCampusColectivoDescripcion;
        $this->tbCampusColectivoEstado = $tbCampusColectivoEstado;
    }

    public function getTbCampusColectivoId() {
        return $this->tbCampusColectivoId;
    }

    public function getTbCampusColectivoNombre() {
        return $this->tbCampusColectivoNombre;
    }

    public function getTbCampusColectivoDescripcion() {
        return $this->tbCampusColectivoDescripcion;
    }

    public function getTbCampusColectivoEstado() {
        return $this->tbCampusColectivoEstado;
    }

    public function setTbCampusColectivoId($tbCampusColectivoId) {
        $this->tbCampusColectivoId = $tbCampusColectivoId;
    }

    public function setTbCampusColectivoNombre($tbCampusColectivoNombre) {
        $this->tbCampusColectivoNombre = $tbCampusColectivoNombre;
    }

    public function setTbCampusColectivoDescripcion($tbCampusColectivoDescripcion) {
        $this->tbCampusColectivoDescripcion = $tbCampusColectivoDescripcion;
    }

    public function setTbCampusColectivoEstado($tbCampusColectivoEstado) {
        $this->tbCampusColectivoEstado = $tbCampusColectivoEstado;
    }
}
?>
