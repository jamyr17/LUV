<?php

// Incluye el archivo de configuración o inicialización de la base de datos si es necesario
include '../business/universidadBusiness.php'; // Incluye los archivos de business necesarios

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
    default:
        // Manejo del caso en que el tipo no existe
        $suggestions = ["error" => "El tipo especificado no existe"];
        break;
}

// Establece el tipo de contenido de la respuesta a JSON
header('Content-Type: application/json');

// Envía las sugerencias como respuesta JSON
echo json_encode($suggestions);
