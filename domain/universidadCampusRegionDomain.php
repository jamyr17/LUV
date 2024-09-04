<?php

class UniversidadCampusRegion {
    private $tbUniversidadCampusRegionid;
    private $tbUniversidadCampusRegionnombre;
    private $tbUniversidadCampusRegiondescripcion;
    private $tbUniversidadCampusRegionestado;

    public function __construct($tbUniversidadCampusRegionid, $tbUniversidadCampusRegionnombre, $tbUniversidadCampusRegiondescripcion, $tbUniversidadCampusRegionestado) {
        $this->tbUniversidadCampusRegionid = $tbUniversidadCampusRegionid;
        $this->tbUniversidadCampusRegionnombre = $tbUniversidadCampusRegionnombre;
        $this->tbUniversidadCampusRegiondescripcion = $tbUniversidadCampusRegiondescripcion;
        $this->tbUniversidadCampusRegionestado = $tbUniversidadCampusRegionestado;
    }

    // Gets
    public function getTbUniversidadCampusRegionid() {
        return $this->tbUniversidadCampusRegionid;
    }

    public function getTbUniversidadCampusRegionnombre() {
        return $this->tbUniversidadCampusRegionnombre;
    }

    public function getTbUniversidadCampusRegiondescripcion() {
        return $this->tbUniversidadCampusRegiondescripcion;
    }

    public function getTbUniversidadCampusRegionestado() {
        return $this->tbUniversidadCampusRegionestado;
    }
    
    // Sets
    public function setTbUniversidadCampusRegionid($tbUniversidadCampusRegionid) {
        $this->tbUniversidadCampusRegionid = $tbUniversidadCampusRegionid;
    }

    public function setTbUniversidadCampusRegionnombre($tbUniversidadCampusRegionnombre) {
        $this->tbUniversidadCampusRegionnombre = $tbUniversidadCampusRegionnombre;
    }

    public function setTbUniversidadCampusRegiondescripcion($tbUniversidadCampusRegiondescripcion) {
        $this->tbUniversidadCampusRegiondescripcion = $tbUniversidadCampusRegiondescripcion;
    }

    public function setTbUniversidadCampusRegionestado($tbUniversidadCampusRegionestado) {
        $this->tbUniversidadCampusRegionestado = $tbUniversidadCampusRegionestado;
    }

}
?>
