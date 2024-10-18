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
// Función principal que procesa la imagen
function procesarImagen($urlImagen) {

    // Enviar la imagen al hilo
    $mensaje = enviarMensaje($urlImagen, $token, $threadId);
    
    // Guardar el mensaje literal en el log
    file_put_contents('debug.log', "Mensaje enviado:\n" . print_r($mensaje, true), FILE_APPEND);

    if (!isset($mensaje['id'])) {
        file_put_contents('debug.log', "Error: No se pudo enviar el mensaje.\n", FILE_APPEND);
        return "Error: No se pudo enviar el mensaje.";
    }

    // Crear un run para la imagen enviada
    $run = crearRun($threadId, $assistantId, $token);
    
    // Guardar el run literal en el log
    file_put_contents('debug.log', "Run creado:\n" . print_r($run, true), FILE_APPEND);

    if (!isset($run['id'])) {
        file_put_contents('debug.log', "Error: No se pudo crear el run.\n", FILE_APPEND);
        return "Error: No se pudo crear el run.";
    }

    $runId = $run['id'];

    // Verificar el estado del run hasta que se complete
    $runCompletado = false;
    $maxRetries = 10;
    for ($i = 0; $i < $maxRetries; $i++) {
        $runStatus = verificarEstadoRun($threadId, $runId, $token);
        
        // Guardar el estado del run en el log
        file_put_contents('debug.log', "Estado del run en intento $i:\n" . print_r($runStatus, true), FILE_APPEND);

        if ($runStatus['status'] === 'completed') {
            $runCompletado = true;
            break;
        }
        sleep(3); // Espera 3 segundos antes de verificar de nuevo
    }

    if (!$runCompletado) {
        file_put_contents('debug.log', "Error: El run no se completó.\n", FILE_APPEND);
        return "Error: El run no se completó.";
    }

    // Obtener los mensajes con los resultados
    $mensajes = obtenerMensajesDelThread($threadId, $token);

    // Guardar los mensajes completos en el log
    file_put_contents('debug.log', "Mensajes del thread:\n" . print_r($mensajes, true), FILE_APPEND);

    if (!$mensajes) {
        file_put_contents('debug.log', "Error: No se encontraron mensajes.\n", FILE_APPEND);
        return "Error: No se encontraron mensajes.";
    }

    // Buscar el mensaje que corresponde al run completado
    foreach ($mensajes['data'] as $mensaje) {
        if (isset($mensaje['run_id']) && $mensaje['run_id'] === $runId) {
            // Guardar la respuesta literal de la IA en el log
            file_put_contents('debug.log', "Respuesta literal de la IA:\n" . print_r($mensaje['content'][0]['text']['value'], true), FILE_APPEND);
            return $mensaje['content'][0]['text']['value']; // Retorna los resultados de la IA
        }
    }

    file_put_contents('debug.log', "Error: No se encontraron resultados para el run.\n", FILE_APPEND);
    return "Error: No se encontraron resultados para el run.";
}

// Función para transformar la respuesta de la IA a un array asociativo de criterios
function obtenerCriterios($respuestaIA) {
    $criterios = [];

    // Guardar la respuesta literal de la IA en el log antes de procesarla
    file_put_contents('debug.log', "Respuesta IA sin procesar:\n" . $respuestaIA . "\n", FILE_APPEND);

    // Decodificar la respuesta de la IA
    $decodedRespuesta = json_decode($respuestaIA, true);

    // Verificar si la respuesta fue decodificada correctamente
    if (json_last_error() === JSON_ERROR_NONE) {
        file_put_contents('debug.log', "Respuesta IA decodificada correctamente:\n" . print_r($decodedRespuesta, true), FILE_APPEND);

        // Recorrer los elementos devueltos por la IA
        foreach ($decodedRespuesta as $elemento) {
            $fila = $elemento['coordenadas'][0];
            $columna = $elemento['coordenadas'][1];
            $criterio = $elemento['criterio'];

            // Formatear la región como "fila,columna"
            $region = "$fila,$columna";

            // Agregar el criterio al array de criterios
            $criterios[$region] = $criterio;
        }
    } else {
        // Error al decodificar el JSON, agregar al log para depuración
        file_put_contents('debug.log', "Error al decodificar JSON: " . json_last_error_msg() . "\n", FILE_APPEND);
    }

    // Depuración: Verificar los criterios extraídos
    file_put_contents('debug.log', "Criterios Extraídos:\n" . print_r($criterios, true), FILE_APPEND);

    return $criterios;
}


?>