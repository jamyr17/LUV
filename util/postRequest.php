<?php 

function postRequest($url, $datos){
    // Iniciar cURL
    $ch = curl_init($url);

    // Configurar la solicitud POST
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($datos));

    // Configurar para recibir la respuesta como string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Ejecutar la solicitud
    $respuesta = curl_exec($ch);

    // Cerrar la conexión cURL
    curl_close($ch);

    // Imprimir la respuesta (que será el JSON con las afinidades)
    return $respuesta;
}