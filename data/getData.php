<?php
include '../bussiness/universidadBussiness.php';
include '../bussiness/campusBussiness.php';
include '../bussiness/areaConocimientoBussiness.php';
include '../bussiness/generoBusiness.php';
include '../bussiness/orientacionSexualBussiness.php';
include '../bussiness/criterioBusiness.php';
include '../bussiness/valorBusiness.php';

header('Content-Type: application/json');

$universidadBusiness = new UniversidadBusiness();
$campusBusiness = new CampusBusiness();
$areaConocimientoBusiness = new AreaConocimientoBussiness();
$generoBusiness = new GeneroBusiness();
$orientacionSexualBusiness = new OrientacionSexualBusiness();
$criterioBusiness = new CriterioBusiness();
$valorBusiness = new ValorBusiness();

$type = $_GET['type'];
$data = [];

switch ($type) {
    case "1":
        $result = $universidadBusiness->getAllTbUniversidad();
        foreach ($result as $item) {
            $data[] = [
                'id' => $item->getTbUniversidadId(),
                'name' => $item->getTbUniversidadNombre()
            ];
        }
        break;
    case "2":
        $result = $areaConocimientoBusiness->getAllTbAreaConocimiento();
        foreach ($result as $item) {
            $data[] = [
                'id' => $item->getTbAreaConocimientoId(),
                'name' => $item->getTbAreaConocimientoNombre()
            ];
        }
        break;
    case "3":
        $result = $generoBusiness->getAllTbGenero();
        foreach ($result as $item) {
            $data[] = [
                'id' => $item->getTbGeneroId(),
                'name' => $item->getTbGeneroNombre()
            ];
        }
        break;
    case "4":
        $result = $orientacionSexualBusiness->getAllTbOrientacionSexual();
        foreach ($result as $item) {
            $data[] = [
                'id' => $item->getTbOrientacionSexualId(),
                'name' => $item->getTbOrientacionSexualNombre()
            ];
        }
        break;
    case "5":
        $result = $campusBusiness->getAllTbCampus();
        foreach ($result as $item) {
            $data[] = [
                'id' => $item->getTbCampusId(),
                'name' => $item->getTbCampusNombre()
            ];
        }
        break;
    case "6":
        $result = $criterioBusiness->getAllTbCriterio();
        foreach ($result as $item) {
            $data[] = [
                'id' => $item->getTbCriterioId(),
                'name' => $item->getTbCriterioNombre()
            ];
        }
        break;
    case "7":
        $result = $valorBusiness->getAllTbValor();
        foreach ($result as $item) {
            $data[] = [
                'id' => $item->getTbValorId(),
                'name' => $item->getTbValorNombre(),
                'idCriterio' => $item->getTbCriterioId()
            ];
        }
        break;
}

echo json_encode($data);
