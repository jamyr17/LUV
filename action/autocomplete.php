<?php

// Incluye el archivo de configuración o inicialización de la base de datos si es necesario
include '../business/universidadBusiness.php'; // Incluye los archivos de business necesarios
include '../business/campusBusiness.php';
include '../business/universidadCampusColectivoBusiness.php';
include '../business/universidadCampusRegionBusiness.php';
include '../business/universidadCampusEspecializacionBusiness.php';
include '../business/areaConocimientoBusiness.php';
include '../business/orientacionSexualBusiness.php';
include '../business/generoBusiness.php';
include '../business/criterioBusiness.php';
include '../business/valorBusiness.php';

// Obtiene el término de búsqueda y el tipo de objeto
$term = isset($_GET['term']) ? $_GET['term'] : '';
$type = isset($_GET['type']) ? $_GET['type'] : '';

// Inicializa el array de sugerencias
$suggestions = [];

// Procesa el tipo de objeto y obtiene las sugerencias correspondientes
switch ($type) {
    case "universidad":
        $universidadBusiness = new UniversidadBusiness();
        $suggestions = $universidadBusiness->autocomplete($term);
        break;
        // Puedes agregar más casos aquí para otros tipos de objetos
    case "campus":
        $campusBusiness = new CampusBusiness();
        $suggestions = $campusBusiness->autocomplete($term);
        break;
    case "campusColectivo":
        $universidadCampusColectivoBusiness = new UniversidadCampusColectivoBusiness();
        $suggestions = $universidadCampusColectivoBusiness->autocomplete($term);
        break;
    case "campusRegion":
        $universidadCampusRegionBusiness = new UniversidadCampusRegionBusiness();
        $suggestions = $universidadCampusRegionBusiness->autocomplete($term);
        break;
    case "campusEspecializacion":
        $universidadCampusEspecializacionBusiness = new UniversidadCampusEspecializacionBusiness();
        $suggestions = $universidadCampusEspecializacionBusiness->autocomplete($term);
        break;
    case "areaConocimiento":
        $areaConocimientoBusiness = new AreaConocimientoBusiness();
        $suggestions = $areaConocimientoBusiness->autocomplete($term);
        break;
    case "orientacionSexual":
        $orientacionSexualBusiness = new OrientacionSexualBusiness();
        $suggestions = $orientacionSexualBusiness->autocomplete($term);
        break;
    case "genero":
        $generoBusiness = new GeneroBusiness();
        $suggestions = $generoBusiness->autocomplete($term);
        break;
    case "criterio":
        $criterioBusiness = new CriterioBusiness();
        $suggestions = $criterioBusiness->autocomplete($term);
        break;
    case "valor":
        $valorBusiness = new ValorBusiness();
        $suggestions = $valorBusiness->autocomplete($term);
        break;
    default:
        // Manejo del caso en que el tipo no existe
        $suggestions = ["error" => "El tipo especificado no existe"];
        break;
}

// Establece el tipo de contenido de la respuesta a JSON
header('Content-Type: application/json');

// Envía las sugerencias como respuesta JSON
echo json_encode($suggestions);
