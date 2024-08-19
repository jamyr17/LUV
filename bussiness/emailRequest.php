<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Aquí puedes procesar la solicitud de la universidad (insertar en base de datos, etc.)
    // $nombreUniversidad = $_POST['request-universidadNombre'];

    // Código para enviar el correo
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->SMTPDebug = 0;  // Cambiar a 0 para desactivar la depuración
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'luvprojectuna@gmail.com';
        $mail->Password = 'zesm bwge ddye oaoq';  // Considera usar variables de entorno para esta información
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Remitente y destinatarios
        $mail->setFrom('luvprojectuna@gmail.com', 'Your Name');
        $mail->addAddress('luvprojectuna@gmail.com');  // Cambia este correo si es necesario

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Nueva Solicitud de Universidad';
        $mail->Body    = 'Se ha solicitado agregar la siguiente universidad: <b>' . htmlspecialchars($_POST['request-universidadNombre']) . '</b>.';
        $mail->AltBody = 'Se ha solicitado agregar la siguiente universidad: ' . htmlspecialchars($_POST['request-universidadNombre']) . '.';

        // Enviar el correo
        $mail->send();
        echo json_encode(['message' => 'Solicitud enviada y correo enviado exitosamente.']);
    } catch (Exception $e) {
        echo json_encode(['message' => "El correo no pudo enviarse. Mailer Error: {$mail->ErrorInfo}"]);
    }
} else {
    echo json_encode(['message' => 'Acceso no permitido.']);
}
?>
