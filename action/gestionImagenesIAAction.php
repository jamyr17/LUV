<?php

// Función para enviar el mensaje con la URL de la imagen a la API
function enviarMensaje($urlImagen, $token, $threadId) {
    $url = 'https://api.openai.com/v1/threads/' . $threadId . '/messages';
    $headers = [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json',
        'OpenAI-Beta: assistants=v2'
    ];

    // Crear el mensaje con la URL de la imagen
    $postData = json_encode([
        'role' => 'user',
        'content' => [
            [
                'type' => 'image_url',
                'image_url' => ['url' => $urlImagen]
            ]
        ]
    ]);

    // Inicializar cURL para enviar el mensaje
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

    // Ejecutar la solicitud
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        $error_msg = curl_error($ch);
        curl_close($ch);
        return "Error de cURL: " . $error_msg;
    }
    curl_close($ch);

    // Decodificar la respuesta
    return json_decode($response, true);
}

// Función para crear un run y procesar la imagen en el hilo
function crearRun($threadId, $assistantId, $token) {
    $url = 'https://api.openai.com/v1/threads/' . $threadId . '/runs';
    $headers = [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json',
        'OpenAI-Beta: assistants=v2'
    ];

    // Datos para crear el run
    $postData = json_encode([
        'assistant_id' => $assistantId
    ]);

    // Inicializar cURL para crear el run
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

    // Ejecutar la solicitud
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        $error_msg = curl_error($ch);
        curl_close($ch);
        return "Error de cURL: " . $error_msg;
    }
    curl_close($ch);

    // Decodificar la respuesta
    return json_decode($response, true);
}

// Función para verificar el estado del run
function verificarEstadoRun($threadId, $runId, $token) {
    $url = 'https://api.openai.com/v1/threads/' . $threadId . '/runs/' . $runId;
    $headers = [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json',
        'OpenAI-Beta: assistants=v2'
    ];

    // Inicializar cURL para verificar el estado del run
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Ejecutar la solicitud
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        $error_msg = curl_error($ch);
        curl_close($ch);
        return "Error de cURL: " . $error_msg;
    }
    curl_close($ch);

    // Decodificar la respuesta
    return json_decode($response, true);
}

// Función para obtener los mensajes del hilo
function obtenerMensajesDelThread($threadId, $token) {
    $url = 'https://api.openai.com/v1/threads/' . $threadId . '/messages';
    $headers = [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json',
        'OpenAI-Beta: assistants=v2'
    ];

    // Inicializar cURL para obtener los mensajes del thread
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Ejecutar la solicitud
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        $error_msg = curl_error($ch);
        curl_close($ch);
        return "Error de cURL: " . $error_msg;
    }
    curl_close($ch);

    // Decodificar la respuesta
    return json_decode($response, true);
}

// Función principal que procesa la imagen
function procesarImagen($urlImagen) {

    // Configuración inicial


    
    // Enviar la imagen al hilo
    $mensaje = enviarMensaje($urlImagen, $token, $threadId);
    if (!isset($mensaje['id'])) {
        return "Error: No se pudo enviar el mensaje.";
    }

    // Crear un run para la imagen enviada
    $run = crearRun($threadId, $assistantId, $token);
    if (!isset($run['id'])) {
        return "Error: No se pudo crear el run.";
    }

    $runId = $run['id'];

    // Verificar el estado del run hasta que se complete
    $runCompletado = false;
    $maxRetries = 10;
    for ($i = 0; $i < $maxRetries; $i++) {
        $runStatus = verificarEstadoRun($threadId, $runId, $token);
        if ($runStatus['status'] === 'completed') {
            $runCompletado = true;
            break;
        }
        sleep(2); // Espera 2 segundos antes de verificar de nuevo
    }

    if (!$runCompletado) {
        return "Error: El run no se completó.";
    }

    // Obtener los mensajes con los resultados
    $mensajes = obtenerMensajesDelThread($threadId, $token);
    if (!$mensajes) {
        return "Error: No se encontraron mensajes.";
    }

    // Buscar el mensaje que corresponde al run completado
    foreach ($mensajes['data'] as $mensaje) {
        if (isset($mensaje['run_id']) && $mensaje['run_id'] === $runId) {
            return $mensaje['content'][0]['text']['value']; // Retorna los resultados de la IA
        }
    }

    return "Error: No se encontraron resultados para el run.";
}

// Función para transformar la respuesta de la IA a un array asociativo de criterios
function obtenerCriterios($respuestaIA) {
    $criterios = [];

    // Usar una expresión regular para capturar (fila, columna) y el criterio
    if (preg_match_all('/\((\d+,\d+)\),\s*([^,]+)/', $respuestaIA, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $match) {
            $region = $match[1];
            $criterio = trim($match[2]);
            $criterios[$region] = $criterio;
        }
    }

    // Depuración: Verificar los criterios extraídos
    file_put_contents('debug.log', "Criterios Extraídos:\n" . print_r($criterios, true), FILE_APPEND);
    
    return $criterios;
}
?>
