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

    // Depuración usando error_log() para ver el tipo de solicitud
    error_log("Tipo de solicitud: " . $type);

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
            $result = $criterioBusiness->getAllTbCriterioDat(); // Cambiado para obtener desde archivos .dat
            foreach ($result as $item) {
                $data[] = [
                    'id' => $item,
                    'name' => $item
                ];
            }
            break;
        case "7":
            if (isset($_GET['criterion'])) {
                $criterion = $_GET['criterion'];

                // Depuración usando error_log() para el criterio recibido
                error_log("Criterio recibido: " . $criterion);

                $filePath = "../resources/criterios/{$criterion}.dat";
                error_log("Ruta del archivo: " . $filePath);  // Depuración de la ruta

                if (file_exists($filePath)) {
                    $data = file_get_contents($filePath);
                    $valores = explode(',', $data);
                    $response = array_map('trim', $valores);

                    // Depuración usando error_log() para los valores leídos
                    error_log("Valores leídos: " . print_r($response, true));

                    echo json_encode($response);
                } else {
                    // Depuración en caso de que el archivo no exista
                    error_log("Archivo no encontrado: " . $filePath);
                    echo json_encode([]);
                }
            }
            break;
    }

    echo json_encode($data);
} else if (isset($_GET['criterion'])) {
    $criterion = $_GET['criterion'];
    $filePath = "../resources/criterios/{$criterion}.dat";

    // Depuración usando error_log() para el criterio y la ruta del archivo
    error_log("Criterio recibido: " . $criterion);
    error_log("Ruta del archivo: " . $filePath);

    if (file_exists($filePath)) {
        $data = file_get_contents($filePath);
        $valores = explode(',', $data);
        $response = array_map('trim', $valores);

        // Depuración usando error_log() para los valores leídos
        error_log("Valores leídos: " . print_r($response, true));

        echo json_encode($response);
    } else {
        // Depuración en caso de que el archivo no exista
        error_log("Archivo no encontrado: " . $filePath);
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
