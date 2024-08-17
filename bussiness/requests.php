<?php
include '../bussiness/universidadBussiness.php';
include '../bussiness/orientacionSexualBussiness.php';

header('Content-Type: application/json'); 

$response = array(); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['request-universidadNombre'])) {
        $nombre = trim($_POST['request-universidadNombre']);
        
        if (strlen($nombre) > 0 && !is_numeric($nombre)) {
            $universidadBusiness = new UniversidadBusiness();
            $resultExist = $universidadBusiness->exist($nombre);
            if ($resultExist == 1) {
                $response['message'] = 'La universidad solicitada ya existe.';
            } else {
                $universidad = new Universidad(0, $nombre, 1);
                $result = $universidadBusiness->insertRequestTbUniversidad($universidad);
                $response['message'] = $result == 1 ? 'Universidad solicitada correctamente.' : 'Error al procesar la transacción.';
            }
        } else {
            $response['message'] = is_numeric($nombre) ? 'Ingreso de valores numéricos no permitido.' : 'Campo(s) vacío(s).';
        }
    } else if (isset($_POST['request-orientacionSexualNombre'])) {
        $nombre = trim($_POST['request-orientacionSexualNombre']);

        if (strlen($nombre) > 0 && !is_numeric($nombre)) {
            $orientacionSexualBusiness = new OrientacionSexualBusiness();
            $resultExist = $orientacionSexualBusiness->existSolicitud($nombre);
            if ($resultExist == 1) {
                $response['message'] = 'La orientación sexual solicitada ya existe.';
            } else {
                $orientacionSexualSolicitud = new orientacionSexual(0, $nombre, 1);
                $result = $orientacionSexualBusiness->insertRequestTbOrientacionSexual($orientacionSexualSolicitud);
                $response['message'] = $result == 1 ? 'Orientación sexual solicitada correctamente.' : 'Error al procesar la transacción.';
            }
        } else {
            $response['message'] = is_numeric($nombre) ? 'Ingreso de valores numéricos no permitido.' : 'Campo(s) vacío(s).';
        }
    } else {
        $response['message'] = 'Problema inesperado.';
    }
} else {
    $response['message'] = 'Método no permitido.';
}

echo json_encode($response);
