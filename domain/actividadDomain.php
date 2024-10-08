<?php

class Actividad {
    private $tbActividadId;
    private $tbActividadTitulo;
    private $tbActividadDescripcion;
    private $tbActividadFechaInicio;
    private $tbActividadFechaTermina;
    private $tbActividadDireccion;
    private $tbActividadLatitud;
    private $tbActividadLongitud; 
    private $tbActividadEstado;
    private $tbActividadAnonimo;
    private $tbActividadColectivos;

    public function __construct($tbActividadId, $tbActividadTitulo, $tbActividadDescripcion, $tbActividadFechaInicio, $tbActividadFechaTermina, $tbActividadDireccion, $tbActividadLatitud, $tbActividadLongitud, $tbActividadEstado, $tbActividadAnonimo, $tbActividadColectivos) {
        $this->tbActividadId = $tbActividadId;
        $this->tbActividadTitulo = $tbActividadTitulo;
        $this->tbActividadDescripcion = $tbActividadDescripcion;
        $this->tbActividadFechaInicio = $tbActividadFechaInicio;
        $this->tbActividadFechaTermina = $tbActividadFechaTermina;
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

    public function getTbActividadFechaInicio() {
        return $this->tbActividadFechaInicio;
    }

    public function getTbActividadFechaTermina() {
        return $this->tbActividadFechaTermina;
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

    public function setTbActividadFechaInicio($tbActividadFechaInicio) {
        $this->tbActividadFechaInicio = $tbActividadFechaInicio;
    }

    public function setTbActividadFechaTermina($tbActividadFechaTermina) {
        $this->tbActividadFechaTermina = $tbActividadFechaTermina;
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
