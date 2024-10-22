<?php
include_once "../business/conexionesBusiness.php";
$conexionesBusiness = new ConexionesBusiness();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json'); 

$data = json_decode(file_get_contents('php://input'), true); 

if (isset($data['cargarPerfiles'])) {

   
    $allPerfiles = $conexionesBusiness->getAllTbPerfiles();
    
    if (empty($allPerfiles)) {
        echo json_encode(['success' => false, 'message' => 'No hay perfiles registrados']);
        exit();
    }

    $_SESSION['perfiles'] = $allPerfiles;
    echo json_encode(['success' => true, 'data' => $allPerfiles]); 
} else {
    echo json_encode(['success' => false, 'message' => 'Parámetro no válido']);
}
?>
