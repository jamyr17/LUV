<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Código para enviar el correo
    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = 0; 
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'luvprojectuna@gmail.com';
        $mail->Password = 'zesm bwge ddye oaoq';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Remitente y destinatarios
        $mail->setFrom('luvprojectuna@gmail.com', 'Your Name');
        $mail->addAddress('luvprojectuna@gmail.com'); 

        // Contenido del correo
        $mail->isHTML(true);
        if(isset($_POST['request-universidadNombre'])){
            $mail->Subject = 'Nueva Solicitud de Universidad';
            $mail->Body    = 'Se ha solicitado agregar la siguiente universidad: <b>' . htmlspecialchars($_POST['request-universidadNombre']) . '</b>.';
            $mail->AltBody = 'Se ha solicitado agregar la siguiente universidad: ' . htmlspecialchars($_POST['request-universidadNombre']) . '.';
        }else if (isset($_POST['request-campusNombre'])) {
            $mail->Subject = 'Nueva Solicitud de Campus';
            $mail->Body    = 'Se ha solicitado agregar el siguiente nombre: <b>' . htmlspecialchars($_POST['request-campusNombre']) . '</b>.';
            $mail->AltBody = 'Se ha solicitado agregar el siguiente nombre: ' . htmlspecialchars($_POST['request-campusNombre']) . '.';
        }else if (isset($_POST['request-generoNombre'])) {
            $mail->Subject = 'Nueva Solicitud de Género';
            $mail->Body    = 'Se ha solicitado agregar el siguiente género: <b>' . htmlspecialchars($_POST['request-generoNombre']) . '</b>.';
            $mail->AltBody = 'Se ha solicitado agregar el siguiente género: ' . htmlspecialchars($_POST['request-generoNombre']) . '.';
        }else if (isset($_POST['request-orientacionSexualNombre'])) {
            $mail->Subject = 'Nueva Solicitud de Orientación Sexual';
            $mail->Body    = 'Se ha solicitado agregar la siguiente orientación sexual: <b>' . htmlspecialchars($_POST['request-orientacionSexualNombre']) . '</b>.';
            $mail->AltBody = 'Se ha solicitado agregar la siguiente orientación sexual: ' . htmlspecialchars($_POST['request-orientacionSexualNombre']) . '.';
        }
        // Enviar el correo
        $mail->send();
        echo json_encode(['message' => 'Correo enviado exitosamente.']);
    } catch (Exception $e) {
        echo json_encode(['message' => "El correo no pudo enviarse. Mailer Error: {$mail->ErrorInfo}"]);
    }
} else {
    echo json_encode(['message' => 'Acceso no permitido.']);
}
