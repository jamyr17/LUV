<?php
include '../bussiness/universidadBussiness.php';
include '../bussiness/orientacionSexualBussiness.php';
include '../bussiness/generoBusiness.php';
include '../bussiness/campusBussiness.php';

header('Content-Type: application/json');

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['request-universidadNombre'])) {
            $nombre = trim($_POST['request-universidadNombre']);
            if (strlen($nombre) > 0 && !is_numeric($nombre)) {
                $universidadBusiness = new UniversidadBusiness();
                $resultExist = $universidadBusiness->exist($nombre);
                $response['message'] = $resultExist == 1 
                    ? 'La universidad solicitada ya existe.' 
                    : ($universidadBusiness->insertRequestTbUniversidad(new Universidad(0, $nombre, 1)) == 1 
                        ? 'Universidad solicitada correctamente.' 
                        : 'Error al procesar la transacción.');
            } else {
                $response['message'] = is_numeric($nombre) ? 'Ingreso de valores numéricos no permitido.' : 'Campo(s) vacío(s).';
            }
        } else if (isset($_POST['request-campusNombre'])) {
            $nombre = trim($_POST['request-campusNombre']);
            $idUniversidad = isset($_POST['idUniversidad']) ? intval($_POST['idUniversidad']) : 0;
            if (strlen($nombre) > 0 && !is_numeric($nombre)) {
                $campusBusiness = new CampusBusiness();
                $resultExist = $campusBusiness->exist($nombre);
                $response['message'] = $resultExist == 1 
                    ? 'El campus solicitado ya existe.' 
                    : ($campusBusiness->insertRequestTbCampus(new Campus(0, $idUniversidad, $nombre, 2, 1)) == 1 
                        ? 'Campus solicitado correctamente.' 
                        : 'Error al procesar la transacción.');
            } else {
                $response['message'] = is_numeric($nombre) ? 'Ingreso de valores numéricos no permitido.' : 'Campo(s) vacío(s).';
            }
        } else if (isset($_POST['request-generoNombre'])) {
            $nombre = trim($_POST['request-generoNombre']);
            if (strlen($nombre) > 0 && !is_numeric($nombre)) {
                $generoBusiness = new GeneroBusiness();
                $resultExist = $generoBusiness->exist($nombre);
                $response['message'] = $resultExist == 1 
                    ? 'El género solicitado ya existe.' 
                    : ($generoBusiness->insertRequestTbGenero(new Genero(0, $nombre, '', 1)) == 1 
                        ? 'Género solicitado correctamente.' 
                        : 'Error al procesar la transacción.');
            } else {
                $response['message'] = is_numeric($nombre) ? 'Ingreso de valores numéricos no permitido.' : 'Campo(s) vacío(s).';
            }
        } else if (isset($_POST['request-orientacionSexualNombre'])) {
            $nombre = trim($_POST['request-orientacionSexualNombre']);
            if (strlen($nombre) > 0 && !is_numeric($nombre)) {
                $orientacionSexualBusiness = new OrientacionSexualBusiness();
                $resultExist = $orientacionSexualBusiness->exist($nombre);
                $response['message'] = $resultExist == 1 
                    ? 'La orientación sexual solicitada ya existe.' 
                    : ($orientacionSexualBusiness->insertRequestTbOrientacionSexual(new OrientacionSexual(0, $nombre, '', 1)) == 1 
                        ? 'Orientación sexual solicitada correctamente.' 
                        : 'Error al procesar la transacción.');
            } else {
                $response['message'] = is_numeric($nombre) ? 'Ingreso de valores numéricos no permitido.' : 'Campo(s) vacío(s).';
            }
        } else {
            $response['message'] = 'Problema inesperado.';
        }
    } catch (Exception $e) {
        $response['message'] = 'Error inesperado: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'Método no permitido.';
}

echo json_encode($response);