<?php

class Imagen {
    private $tbImagenId;
    private $tbImagenCrudId;
    private $tbImagenRegistroId;
    private $tbImagenNombre;
    private $tbImagenDirectorio;
    private $tbImagenEstado;

    public function __construct($tbImagenId, $tbImagenCrudId, $tbImagenRegistroId, $tbImagenNombre, $tbImagenDirectorio, $tbImagenEstado) {
        $this->tbImagenId = $tbImagenId;
        $this->tbImagenCrudId = $tbImagenCrudId;
        $this->tbImagenRegistroId = $tbImagenRegistroId;
        $this->tbImagenNombre = $tbImagenNombre;
        $this->tbImagenDirectorio = $tbImagenDirectorio;
        $this->tbImagenEstado = $tbImagenEstado;
    }

    public function gettbImagenId() {
        return $this->tbImagenId;
    }

    public function gettbImagenCrudId() {
        return $this->tbImagenCrudId;
    }

    public function gettbImagenRegistroId() {
        return $this->tbImagenRegistroId;
    }

    public function gettbImagenNombre() {
        return $this->tbImagenNombre;
    }

    public function gettbImagenDirectorio() {
        return $this->tbImagenDirectorio;
    }

    public function gettbImagenEstado() {
        return $this->tbImagenEstado;
    }
    
    public function settbImagenId($tbImagenId) {
        $this->tbImagenId = $tbImagenId;
    }

    public function settbImagenCrudId($tbImagenCrudId) {
        $this->tbImagenCrudId = $tbImagenCrudId;
    }

    public function settbImagenRegistroId($tbImagenRegistroId) {
        $this->tbImagenRegistroId = $tbImagenRegistroId;
    }

    public function settbImagenNombre($tbImagenNombre) {
        $this->tbImagenNombre = $tbImagenNombre;
    }

    public function settbImagenDirectorio($tbImagenDirectorio) {
        $this->tbImagenDirectorio = $tbImagenDirectorio;
    }

    public function settbImagenEstado($tbImagenEstado) {
        $this->tbImagenEstado = $tbImagenEstado;
    }

}
