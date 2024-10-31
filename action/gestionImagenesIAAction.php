<?php

function enviarMensaje($urlImagen, $token, $threadId) {
    $url = 'https://api.openai.com/v1/threads/' . $threadId . '/messages';
    $headers = [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json',
        'OpenAI-Beta: assistants=v2'
    ];

    $postData = json_encode([
        'role' => 'user',
        'content' => [
            [
                'type' => 'image_url',
                'image_url' => ['url' => $urlImagen]
            ]
        ]
    ]);

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


function obtenerCriteriosCloud($respuestaIA) {
    // Limpieza del JSON: elimina cualquier coma final en listas u objetos
    $respuestaLimpia = preg_replace('/,(\s*[\]}])/m', '$1', $respuestaIA);
    //file_put_contents('debug.log', "Respuesta IA limpia: " . $respuestaLimpia . "\n", FILE_APPEND);

    $regiones = [];
    $criteriosTextos = [];
    $decodedRespuesta = json_decode($respuestaLimpia, true);

    if (json_last_error() === JSON_ERROR_NONE && is_array($decodedRespuesta)) {
        foreach ($decodedRespuesta as $elemento) {
            $fila = $elemento['coordenadas'][0];
            $columna = $elemento['coordenadas'][1];
            $criterio = isset($elemento['criterio']) ? $elemento['criterio'] : "Sin criterio";

            $region = "$fila,$columna";
            $regiones[] = $region;
            $criteriosTextos[] = $criterio;
        }
    } else {
        //file_put_contents('debug.log', "Error al decodificar JSON en obtenerCriteriosCloud: " . json_last_error_msg() . "\n", FILE_APPEND);
        return "Error: JSON no válido recibido en obtenerCriteriosCloud.";
    }

    // Formatear la línea final en el formato deseado
    $lineaArchivo = "Region: " . implode(";", $regiones) . " | Criterio: " . implode(",", $criteriosTextos);
    return $lineaArchivo;
}

function procesarImagenIA($urlImagen) {

    $token = ''; // Tu API Key
    $assistantId = '';
    $threadId = '';

    $mensaje = enviarMensaje($urlImagen, $token, $threadId);

    if (!isset($mensaje['id'])) {
       // file_put_contents('debug.log', "Error: No se pudo enviar el mensaje.\n", FILE_APPEND);
        return json_encode(["error" => "No se pudo enviar el mensaje"]);
    }

    $run = crearRun($threadId, $assistantId, $token);

    if (!isset($run['id'])) {
       // file_put_contents('debug.log', "Error: No se pudo crear el run.\n", FILE_APPEND);
        return json_encode(["error" => "No se pudo crear el run"]);
    }

    $runId = $run['id'];
    $runCompletado = false;
    $maxRetries = 10;
    for ($i = 0; $i < $maxRetries; $i++) {
        $runStatus = verificarEstadoRun($threadId, $runId, $token);

        if ($runStatus['status'] === 'completed') {
            $runCompletado = true;
            break;
        }
        sleep(3);
    }

    if (!$runCompletado) {
        //file_put_contents('debug.log', "Error: El run no se completó.\n", FILE_APPEND);
        return json_encode(["error" => "El run no se completó"]);
    }

    $mensajes = obtenerMensajesDelThread($threadId, $token);
    if (!$mensajes) {
       // file_put_contents('debug.log', "Error: No se encontraron mensajes.\n", FILE_APPEND);
        return json_encode(["error" => "No se encontraron mensajes"]);
    }

    foreach ($mensajes['data'] as $mensaje) {
        if (isset($mensaje['run_id']) && $mensaje['run_id'] === $runId) {
            if (isset($mensaje['content'][0]['text']['value'])) {
                return $mensaje['content'][0]['text']['value'];
            } else {
                file_put_contents('debug.log', "Error: No se encontraron resultados en el contenido.\n", FILE_APPEND);
                return json_encode(["error" => "No se encontraron resultados para el run"]);
            }
        }
    }

    //file_put_contents('debug.log', "Error: El contenido del mensaje está vacío o en un formato inesperado.\n", FILE_APPEND);
    return json_encode(["error" => "Contenido de mensaje vacío o inesperado"]);
}

function obtenerCriterios($respuestaIA) {
    $criterios = [];
    $decodedRespuesta = json_decode($respuestaIA, true);

    if (json_last_error() === JSON_ERROR_NONE && is_array($decodedRespuesta)) {
        foreach ($decodedRespuesta as $elemento) {
            $fila = $elemento['coordenadas'][0];
            $columna = $elemento['coordenadas'][1];
            $criterio = $elemento['criterio'];

            $region = "$fila,$columna";
            $criterios[$region] = $criterio;
        }
    } else {
        //file_put_contents('debug.log', "Error al decodificar JSON en obtenerCriterios: " . json_last_error_msg() . "\n", FILE_APPEND);
    }

    return $criterios;
}


?>