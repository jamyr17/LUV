<?php
include '../business/universidadBusiness.php';
include '../business/campusBusiness.php';
include '../business/areaConocimientoBusiness.php';
include '../business/generoBusiness.php';
include '../business/orientacionSexualBusiness.php';
include '../business/criterioBusiness.php';
include '../business/valorBusiness.php';

header('Content-Type: application/json');

if(isset($_GET['type'])){
    $universidadBusiness = new UniversidadBusiness();
    $campusBusiness = new CampusBusiness();
    $areaConocimientoBusiness = new AreaConocimientoBusiness();
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
}else if(isset($_GET['criterion'])){
    $criterion = $_GET['criterion'];
    $filePath = "../resources/criterios/{$criterion}.dat";

    if (file_exists($filePath)) {
        $data = file_get_contents($filePath);
        $suggestions = explode(',', $data);  
        echo json_encode($suggestions);
    } else {
        echo json_encode([]); 
    }
} else {
    echo json_encode([]);  
}
