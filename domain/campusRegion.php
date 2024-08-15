<?php

class CampusRegion {
    private $tbcampusregionid;
    private $tbcampusregionnombre;
    private $tbcampusregiondescripcion;
    private $tbcampusregionestado;

    public function __construct($tbcampusregionid, $tbcampusregionnombre, $tbcampusregiondescripcion, $tbcampusregionestado) {
        $this->tbcampusregionid = $tbcampusregionid;
        $this->tbcampusregionnombre = $tbcampusregionnombre;
        $this->tbcampusregiondescripcion = $tbcampusregiondescripcion;
        $this->tbcampusregionestado = $tbcampusregionestado;
    }

    // Gets
    public function getTbcampusregionid() {
        return $this->tbcampusregionid;
    }

    public function getTbcampusregionnombre() {
        return $this->tbcampusregionnombre;
    }

    public function getTbcampusregiondescripcion() {
        return $this->tbcampusregiondescripcion;
    }

    public function getTbcampusregionestado() {
        return $this->tbcampusregionestado;
    }
    
    // Sets
    public function setTbcampusregionid($tbcampusregionid) {
        $this->tbcampusregionid = $tbcampusregionid;
    }

    public function setTbcampusregionnombre($tbcampusregionnombre) {
        $this->tbcampusregionnombre = $tbcampusregionnombre;
    }

    public function setTbcampusregiondescripcion($tbcampusregiondescripcion) {
        $this->tbcampusregiondescripcion = $tbcampusregiondescripcion;
    }

    public function setTbcampusregionestado($tbcampusregionestado) {
        $this->tbcampusregionestado = $tbcampusregionestado;
    }

}
?>
