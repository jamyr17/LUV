<?php
// Claves de la API de Sirv
define("SIRV_CLIENT_ID", "PQ8c4NqBFog9Qk8tYOdXpjgVqMJ");
define("SIRV_CLIENT_SECRET", "hxSvLjJGQQsGlPnrGE2DD8ETTs3Yhtrr0MONK+lJQlaJvBK6D33tjM4WpP2iKEL4bXkbpsvSIR0kH1RXhVMHvw==");

/**
 * Función para obtener el token de Sirv
 */
function obtenerTokenSirv() {
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.sirv.com/v2/token",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
        CURLOPT_POSTFIELDS => json_encode([
            "clientId" => SIRV_CLIENT_ID,
            "clientSecret" => SIRV_CLIENT_SECRET
        ])
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
        return "Error de cURL: " . $err;
    }

    $data = json_decode($response, true);
    return $data['token'] ?? null;
}

/**
 * Función para subir una imagen a Sirv
 */
function subirImagenASirv($rutaImagen, $nombreOriginal) {
    $token = obtenerTokenSirv();
    if (!$token) {
        return "Error al obtener el token de Sirv.";
    }

    $nombreImagen = basename($nombreOriginal); // nombre real del archivo
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.sirv.com/v2/files/upload?filename=/images/$nombreImagen",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $token",
            "Content-Type: application/json"
        ],
        CURLOPT_POSTFIELDS => [
            "file" => new CURLFile($rutaImagen)
        ]
    ]);

    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $curlError = curl_error($curl);
    curl_close($curl);

    $data = json_decode($response, true);

    if ($httpCode !== 200 || isset($data['error'])) {
        return "Error al subir la imagen: " . ($data['error']['message'] ?? "desconocido");
    }

    // Retorna la URL de la imagen subida
    return "https://your-account-name.sirv.com/images/$nombreImagen"; // Reemplaza "your-account-name"
}
