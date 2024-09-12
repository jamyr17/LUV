<?php

include '../data/criterioData.php';

class CriterioBusiness {

    private $criterioData;

    public function __construct() {
        $this->criterioData = new CriterioData();
    }

    public function insertTbCriterio($criterio) {
        return $this->criterioData->insertTbCriterio($criterio);
    }

    public function updateTbCriterio($criterio) {
        return $this->criterioData->updateTbCriterio($criterio);
    }
/*
    public function deleteTbCriterio($idCriterio) {
        return $this->criterioData->deleteTbCriterio($idCriterio);
    }
*/  
    public function checkAssociatedValues($criterioId) {
        return $this->criterioData->checkAssociatedValues($criterioId);
    }

    public function deleteCriterioById($criterioId) {
        return $this->criterioData->deleteCriterioById($criterioId);
    }

    public function deleteForeverTbCriterio($idCriterio) {
        return $this->criterioData->deleteForeverTbCriterio($idCriterio);
    }

    public function getAllTbCriterio() {
        return $this->criterioData->getAllTbCriterio();
    }

    public function getAllTbCriterioNombres() {
        return $this->criterioData->getAllTbCriterioNombres();
    }

    public function getAllDeletedTbCriterio() {
        return $this->criterioData->getAllDeletedTbCriterio();
    }

    public function restoreTbCriterio($idCriterio) {
        $criterioData = new CriterioData();
        return $criterioData->restoreTbCriterio($idCriterio);
    }

    public function exist($nombre) {
        return $this->criterioData->exist($nombre);
    }

    public function insertRequestTbCriterio($criterio){
        return $this->criterioData->insertRequestTbCriterio($criterio);
    }

    public function getCriterioNombreById($idCriterio) {
        return $this->criterioData->getCriterioNombreById($idCriterio);
    }

    public function autocomplete($term) {
        return $this->criterioData->autocomplete($term);
    }
}
