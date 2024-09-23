<?php
include_once '../business/universidadBusiness.php';
include_once '../business/campusBusiness.php';
include_once '../business/areaConocimientoBusiness.php';
include_once '../business/generoBusiness.php';
include_once '../business/orientacionSexualBusiness.php';
include_once '../business/criterioBusiness.php';
include_once '../business/valorBusiness.php';
include_once '../business/universidadCampusColectivoBusiness.php';

header('Content-Type: application/json');

if (isset($_GET['type'])) {
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
} else if (isset($_GET['criterion'])) {
    $criterion = $_GET['criterion'];
    $filePath = "../resources/criterios/{$criterion}.dat";

    if (file_exists($filePath)) {
        $data = file_get_contents($filePath);
        $suggestions = explode(',', $data);
        echo json_encode($suggestions);
    } else {
        echo json_encode([]);
    }
} else if (isset($_GET['universidadNombre'])) {

    $universidadNombre = $_GET['universidadNombre']; 

    $campusBusiness = new CampusBusiness();
    $campus = $campusBusiness->getAllTbCampusByUniversidadByNombre($universidadNombre); // Implementa este método en tu clase

    $response = [];
    foreach ($campus as $camp) {
        $response[] = [
            'id' => htmlspecialchars($camp->getTbCampusId()),
            'nombre' => htmlspecialchars($camp->getTbCampusNombre())
        ];
    }

    echo json_encode($response);
} else if (isset($_GET['campusId'])) {

    $campusId = $_GET['campusId']; 

    $campusColectivoBusiness = new UniversidadCampusColectivoBusiness();
    $colectivos = $campusColectivoBusiness->getColectivosByCampusId($campusId);

    $response = [];
    foreach ($colectivos as $colectivo) {
        $response[] = [
            'id' => htmlspecialchars($colectivo->getTbUniversidadCampusColectivoId()),
            'nombre' => htmlspecialchars($colectivo->getTbUniversidadCampusColectivoNombre())
        ];
    }

    echo json_encode($response);
} else {
    echo json_encode([]);
}