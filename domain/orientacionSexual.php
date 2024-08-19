<?php

class OrientacionSexual {
    private $tbOrientacionSexualId;
    private $tbOrientacionSexualNombre;
    private $tbOrientacionSexualDescripcion;
    private $tbOrientacionSexualEstado;

    public function __construct($tbOrientacionSexualId, $tbOrientacionSexualNombre, $tbOrientacionSexualDescripcion, $tbOrientacionSexualEstado) {
        $this->tbOrientacionSexualId = $tbOrientacionSexualId;
        $this->tbOrientacionSexualNombre = $tbOrientacionSexualNombre;
        $this->tbOrientacionSexualDescripcion = $tbOrientacionSexualDescripcion;
        $this->tbOrientacionSexualEstado = $tbOrientacionSexualEstado;
    }

    public function getTbOrientacionSexualId() {
        return $this->tbOrientacionSexualId;
    }

    public function getTbOrientacionSexualNombre() {
        return $this->tbOrientacionSexualNombre;
    }

    public function getTbOrientacionSexualDescripcion() {
        return $this->tbOrientacionSexualDescripcion;
    }

    public function getTbOrientacionSexualEstado() {
        return $this->tbOrientacionSexualEstado;
    }

    public function setTbOrientacionSexualId($tbOrientacionSexualId) {
        $this->tbOrientacionSexualId = $tbOrientacionSexualId;
    }

    public function setTbOrientacionSexualNombre($tbOrientacionSexualNombre) {
        $this->tbOrientacionSexualNombre = $tbOrientacionSexualNombre;
    }

    public function setTbOrientacionSexualDescripcion($tbOrientacionSexualDescripcion) {
        $this->tbOrientacionSexualDescripcion = $tbOrientacionSexualDescripcion;
    }

    public function setTbOrientacionSexualEstado($tbOrientacionSexualEstado) {
        $this->tbOrientacionSexualEstado = $tbOrientacionSexualEstado;
    }
}
?>
