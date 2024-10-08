<?php

require '../vendor/autoload.php';
include_once '../business/universidadBusiness.php';
include_once '../business/orientacionSexualBusiness.php';
include_once '../business/generoBusiness.php';
include_once '../business/campusBusiness.php';
include_once '../business/areaConocimientoBusiness.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['request-universidadNombre'])) {

            $nombre = trim($_POST['request-universidadNombre']);
            if (strlen($nombre) > 0 && !is_numeric($nombre)) {
                $universidadBusiness = new UniversidadBusiness();
                $resultExist = $universidadBusiness->exist($nombre);
                if ($resultExist == 1) {
                    $response['message'] = 'La universidad solicitada ya existe.';
                } else {
                    $insertResult = $universidadBusiness->insertRequestTbUniversidad(new Universidad(0, $nombre, 1));
                    $response['message'] = ($insertResult == 1) ? 'Universidad solicitada correctamente.' : 'Error al procesar la transacción.';
                    sendEmail();
                }
            } else {
                $response['message'] = is_numeric($nombre) ? 'Ingreso de valores numéricos no permitido.' : 'Campo(s) vacío(s).';
            }
        } else if (isset($_POST['request-campusNombre'])) {

            $nombre = trim($_POST['request-campusNombre']);
            $idUniversidad = isset($_POST['idUniversidad']) ? intval($_POST['idUniversidad']) : 0;
            if (strlen($nombre) > 0 && !is_numeric($nombre)) {
                $campusBusiness = new CampusBusiness();
                $resultExist = $campusBusiness->exist($nombre);

                if ($resultExist == 1) {
                    $response['message'] = 'El campus solicitado ya existe.';
                } else {
                    $insertResult = $campusBusiness->insertRequestTbCampus(new Campus(0, $idUniversidad, 0, $nombre, 2, 1, '', '', ''));
                    $response['message'] = ($insertResult == 1) ? 'Campus solicitado correctamente.' : 'Error al procesar la transacción.';
                    sendEmail();
                }
            } else {
                $response['message'] = is_numeric($nombre) ? 'Ingreso de valores numéricos no permitido.' : 'Campo(s) vacío(s).';
            }
        } else if (isset($_POST['request-generoNombre'])) {

            $nombre = trim($_POST['request-generoNombre']);
            if (strlen($nombre) > 0 && !is_numeric($nombre)) {
                $generoBusiness = new GeneroBusiness();
                $resultExist = $generoBusiness->exist($nombre);

                if ($resultExist == 1) {
                    $response['message'] = 'El género solicitado ya existe.';
                } else {
                    $insertResult = $generoBusiness->insertRequestTbGenero(new Genero(0, $nombre, '', 1));
                    $response['message'] = ($insertResult == 1) ? 'Género solicitado correctamente.' : 'Error al procesar la transacción.';
                    sendEmail();
                }
            } else {
                $response['message'] = is_numeric($nombre) ? 'Ingreso de valores numéricos no permitido.' : 'Campo(s) vacío(s).';
            }
        } else if (isset($_POST['request-orientacionSexualNombre'])) {

            $nombre = trim($_POST['request-orientacionSexualNombre']);
            if (strlen($nombre) > 0 && !is_numeric($nombre)) {
                $orientacionSexualBusiness = new OrientacionSexualBusiness();
                $resultExist = $orientacionSexualBusiness->exist($nombre);

                if ($resultExist == 1) {
                    $response['message'] = 'La orientación sexual solicitada ya existe.';
                } else {
                    $insertResult = $orientacionSexualBusiness->insertRequestTbOrientacionSexual(new OrientacionSexual(0, $nombre, '', 1));
                    $response['message'] = ($insertResult == 1) ? 'Orientación sexual solicitada correctamente.' : 'Error al procesar la transacción.';
                    sendEmail();
                }
            } else {
                $response['message'] = is_numeric($nombre) ? 'Ingreso de valores numéricos no permitido.' : 'Campo(s) vacío(s).';
            }
        } else if (isset($_POST['request-areaConocimientoNombre'])) {

            $nombre = trim($_POST['request-areaConocimientoNombre']);

            if (strlen($nombre) > 0 && !is_numeric($nombre)) {
                $areaConocimientoBusiness = new AreaConocimientoBusiness();
                $resultExist = $areaConocimientoBusiness->exist($nombre);

                if ($resultExist == 1) {
                    $response['message'] = 'El área de conocimiento solicitada ya existe.';
                } else {
                    $insertResult = $orientacionSexualBusiness->insertRequestTbOrientacionSexual(new AreaConocimiento(0, $nombre, '', 1));
                    $response['message'] = ($insertResult == 1) ? 'Área de conocimiento solicitada correctamente.' : 'Error al procesar la transacción.';
                    sendEmail();
                }
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

function sendEmail()
{
    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'jeycobbarrientosgarcia@gmail.com'; // luvprojectuna
        $mail->Password = 'ecxz onek xqgn vyaa'; // zesm bwge ddye oaoq | la mía ecxz onek xqgn vyaa
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Remitente y destinatarios
        $mail->setFrom('jeycobbarrientosgarcia@gmail.com', 'Your Name');
        $mail->addAddress('jeycobbarrientosgarcia@gmail.com');

        // Contenido del correo
        $mail->isHTML(true);
        if (isset($_POST['request-universidadNombre'])) {
            $mail->Subject = 'Nueva Solicitud de Universidad';
            $mail->Body    = 'Se ha solicitado agregar la siguiente universidad: <b>' . htmlspecialchars($_POST['request-universidadNombre']) . '</b>.';
            $mail->AltBody = 'Se ha solicitado agregar la siguiente universidad: ' . htmlspecialchars($_POST['request-universidadNombre']) . '.';
        } else if (isset($_POST['request-campusNombre'])) {
            $mail->Subject = 'Nueva Solicitud de Campus';
            $mail->Body    = 'Se ha solicitado agregar el siguiente nombre: <b>' . htmlspecialchars($_POST['request-campusNombre']) . '</b>.';
            $mail->AltBody = 'Se ha solicitado agregar el siguiente nombre: ' . htmlspecialchars($_POST['request-campusNombre']) . '.';
        } else if (isset($_POST['request-generoNombre'])) {
            $mail->Subject = 'Nueva Solicitud de Género';
            $mail->Body    = 'Se ha solicitado agregar el siguiente género: <b>' . htmlspecialchars($_POST['request-generoNombre']) . '</b>.';
            $mail->AltBody = 'Se ha solicitado agregar el siguiente género: ' . htmlspecialchars($_POST['request-generoNombre']) . '.';
        } else if (isset($_POST['request-orientacionSexualNombre'])) {
            $mail->Subject = 'Nueva Solicitud de Orientación Sexual';
            $mail->Body    = 'Se ha solicitado agregar la siguiente orientación sexual: <b>' . htmlspecialchars($_POST['request-orientacionSexualNombre']) . '</b>.';
            $mail->AltBody = 'Se ha solicitado agregar la siguiente orientación sexual: ' . htmlspecialchars($_POST['request-orientacionSexualNombre']) . '.';
        } else if (isset($_POST['request-areaConocimientoNombre'])) {
            $mail->Subject = 'Nueva Solicitud de Área de Conocimiento';
            $mail->Body    = 'Se ha solicitado agregar la siguiente área de conocimiento: <b>' . htmlspecialchars($_POST['request-areaConocimientoNombre']) . '</b>.';
            $mail->AltBody = 'Se ha solicitado agregar la siguiente área de conocimiento: ' . htmlspecialchars($_POST['request-areaConocimientoNombre']) . '.';
        }
        // Enviar el correo
        $mail->send();
    } catch (Exception $e) {
        $response['message'] = "El correo no pudo enviarse. Mailer Error: {$mail->ErrorInfo}";
    }
}
