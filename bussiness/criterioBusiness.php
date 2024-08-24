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

    public function deleteTbCriterio($idCriterio) {
        return $this->criterioData->deleteTbCriterio($idCriterio);
    }

    public function deleteForeverTbCriterio($idCriterio) {
        return $this->criterioData->deleteForeverTbCriterio($idCriterio);
    }

    public function getAllTbCriterio() {
        return $this->criterioData->getAllTbCriterio();
    }

    public function getAllDeletedTbCriterio() {
        return $this->criterioData->getAllDeletedTbCriterio();
    }

    public function exist($nombre) {
        return $this->criterioData->exist($nombre);
    }

    public function insertRequestTbCriterio($criterio){
        return $this->criterioData->insertRequestTbCriterio($criterio);
    }

}