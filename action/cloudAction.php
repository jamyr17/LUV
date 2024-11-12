<?php
require '../vendor/autoload.php';

use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;
// Configuración de Cloudinary
Configuration::instance([
    'cloud' => [       
        'cloud_name' => 'dwhwrxgud',
        'api_key'    => '375843476832478',
        'api_secret' => 'rN5A_8Sa0dsYjAvWV2smFyz7W9I'
    ],
    'url' => [
        'secure' => true
    ]
]);

function subirImagenACloudinary($rutaImagen, $nombreImagen) {
    try {
        $resultado = (new UploadApi())->upload($rutaImagen, [
            'public_id' => $nombreImagen,
            'folder'    => 'images/'
        ]);

        // Retorna la URL segura de la imagen subida
        return $resultado['secure_url'];
    } catch (Exception $e) {
        return "Error al subir la imagen: " . $e->getMessage();
    }
}
?>
