<?php 

function postRequest($url, $data) {
    $jsonData = json_encode($data);

    // Iniciar cURL
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',  
        'Content-Length: ' . strlen($jsonData) 
    ]);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $respuesta = curl_exec($ch);

    if(curl_errno($ch)) {
        echo 'Error en cURL: ' . curl_error($ch);
    }

    curl_close($ch);

    return $respuesta;
}
