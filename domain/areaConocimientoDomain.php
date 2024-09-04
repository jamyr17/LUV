<?php

class AreaConocimiento {
    private $tbAreaConocimientoId;
    private $tbAreaConocimientoNombre;
    private $tbAreaConocimientoDescripcion;
    private $tbAreaConocimientoEstado;

    public function __construct($tbAreaConocimientoId, $tbAreaConocimientoNombre, $tbAreaConocimientoDescripcion, $tbAreaConocimientoEstado) {
        $this->tbAreaConocimientoId = $tbAreaConocimientoId;
        $this->tbAreaConocimientoNombre = $tbAreaConocimientoNombre;
        $this->tbAreaConocimientoDescripcion = $tbAreaConocimientoDescripcion;
        $this->tbAreaConocimientoEstado = $tbAreaConocimientoEstado;
    }

    public function getTbAreaConocimientoId() {
        return $this->tbAreaConocimientoId;
    }

    public function getTbAreaConocimientoNombre() {
        return $this->tbAreaConocimientoNombre;
    }

    public function getTbAreaConocimientoDescripcion() {
        return $this->tbAreaConocimientoDescripcion;
    }

    public function getTbAreaConocimientoEstado() {
        return $this->tbAreaConocimientoEstado;
    }

    public function setTbAreaConocimientoId($tbAreaConocimientoId) {
        $this->tbAreaConocimientoId = $tbAreaConocimientoId;
    }

    public function setTbAreaConocimientoNombre($tbAreaConocimientoNombre) {
        $this->tbAreaConocimientoNombre = $tbAreaConocimientoNombre;
    }

    public function setTbAreaConocimientoDescripcion($tbAreaConocimientoDescripcion) {
        $this->tbAreaConocimientoDescripcion = $tbAreaConocimientoDescripcion;
    }

    public function setTbAreaConocimientoEstado($tbAreaConocimientoEstado) {
        $this->tbAreaConocimientoEstado = $tbAreaConocimientoEstado;
    }
}
?>
