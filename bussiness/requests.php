<?php
include '../bussiness/universidadBussiness.php';

header('Content-Type: application/json'); 
$response = array(); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['request-universidadNombre'])) {
        $nombre = trim($_POST['request-universidadNombre']);

        if (strlen($nombre) > 0) {
            if (!is_numeric($nombre)) {
                $universidadBusiness = new UniversidadBusiness();

                $resultExist = $universidadBusiness->exist($nombre);

                if ($resultExist == 1) {
                    $response['message'] = 'Ocurrió un error debido a que la universidad solicitada ya existe.';
                } else {
                    $universidad = new Universidad(0, $nombre, 1);
                    $result = $universidadBusiness->insertRequestTbUniversidad($universidad);

                    if ($result == 1) {
                        $response['message'] = 'Universidad solicitada correctamente.';
                    } else {
                        $response['message'] = 'Ocurrió un error debido a un problema al procesar la transacción.';
                    }
                }
            } else {
                $response['message'] = 'Ocurrió un error debido a ingreso de valores númericos.';
            }
        } else {
            $response['message'] = 'Ocurrió un error debido a campo(s) vacío(s).';
        }
    } else {
        $response['message'] = 'Ocurrió un error debido a un problema inesperado.';
    }
}

echo json_encode($response);