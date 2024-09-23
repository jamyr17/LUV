<?php

class Actividad {
    private $tbActividadId;
    private $tbActividadTitulo;
    private $tbActividadDescripcion;
    private $tbActividadFecha;
    private $tbActividadDuracion;
    private $tbActividadDireccion;
    private $tbActividadLatitud;
    private $tbActividadLongitud; 
    private $tbActividadEstado;
    private $tbActividadAnonimo;
    private $tbActividadColectivos;

    public function __construct($tbActividadId, $tbActividadTitulo, $tbActividadDescripcion, $tbActividadFecha, $tbActividadDuracion, $tbActividadDireccion, $tbActividadLatitud, $tbActividadLongitud, $tbActividadEstado, $tbActividadAnonimo, $tbActividadColectivos) {
        $this->tbActividadId = $tbActividadId;
        $this->tbActividadTitulo = $tbActividadTitulo;
        $this->tbActividadDescripcion = $tbActividadDescripcion;
        $this->tbActividadFecha = $tbActividadFecha;
        $this->tbActividadDuracion = $tbActividadDuracion;
        $this->tbActividadDireccion = $tbActividadDireccion;
        $this->tbActividadLatitud = $tbActividadLatitud;
        $this->tbActividadLongitud = $tbActividadLongitud;
        $this->tbActividadEstado = $tbActividadEstado;
        $this->tbActividadAnonimo = $tbActividadAnonimo;
        $this->tbActividadColectivos = $tbActividadColectivos;
    }    

    public function getTbActividadId() {
        return $this->tbActividadId;
    }

    public function getTbActividadTitulo() {
        return $this->tbActividadTitulo;
    }

    public function getTbActividadDescripcion() {
        return $this->tbActividadDescripcion;
    }

    public function getTbActividadFecha() {
        return $this->tbActividadFecha;
    }

    public function getTbActividadDuracion() {
        return $this->tbActividadDuracion;
    }

    public function getTbActividadDireccion() {
        return $this->tbActividadDireccion;
    }

    public function getTbActividadLatitud() {
        return $this->tbActividadLatitud;
    }

    public function getTbActividadLongitud() {
        return $this->tbActividadLongitud;
    }

    public function getTbActividadEstado() {
        return $this->tbActividadEstado;
    }

    public function getTbActividadAnonimo() {
        return $this->tbActividadAnonimo;
    }

    public function getTbActividadColectivos() {
        return $this->tbActividadColectivos;
    }

    public function setTbActividadId($tbActividadId) {
        $this->tbActividadId = $tbActividadId;
    }

    public function setTbActividadTitulo($tbActividadTitulo) {
        $this->tbActividadTitulo = $tbActividadTitulo;
    }

    public function setTbActividadDescripcion($tbActividadDescripcion) {
        $this->tbActividadDescripcion = $tbActividadDescripcion;
    }

    public function setTbActividadFecha($tbActividadFecha) {
        $this->tbActividadFecha = $tbActividadFecha;
    }

    public function setTbActividadDuracion($tbActividadDuracion) {
        $this->tbActividadDuracion = $tbActividadDuracion;
    }

    public function setTbActividadDireccion($tbActividadDireccion) {
        $this->tbActividadDireccion = $tbActividadDireccion;
    }

    public function setTbActividadLatitud($tbActividadLatitud) {
        $this->tbActividadLatitud = $tbActividadLatitud;
    }

    public function setTbActividadLongitud($tbActividadLongitud) {
        $this->tbActividadLongitud = $tbActividadLongitud;
    }

    public function setTbActividadEstado($tbActividadEstado) {
        $this->tbActividadEstado = $tbActividadEstado;
    }

    public function setTbActividadAnonimo($tbActividadAnonimo) {
        $this->tbActividadAnonimo = $tbActividadAnonimo;
    }

    public function setTbActividadColectivos($tbActividadColectivos) {
        $this->tbActividadColectivos = $tbActividadColectivos;
    }
}
