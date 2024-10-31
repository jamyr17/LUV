<?php

function createFolderIfNotExists($folderPath) {
    if (!file_exists($folderPath)) {
        mkdir($folderPath, 0777, true);
    }
}

function createDataFile($nombre, $data) {
    $filePath = "../resources/criterios/{$nombre}.dat";
    $file = fopen($filePath, 'w');
    if ($file) {
        if (is_array($data)) {
            foreach ($data as $line) {
                fwrite($file, $line . PHP_EOL);
            }
        } else {
            fwrite($file, $data . PHP_EOL);
        }
        fclose($file);
    } else {
        echo "Error: No se pudo crear el archivo {$filePath}";
    }
}

function agregarValorSiNoExiste($nombreArchivo, $valor) {
    $filePath = "../resources/criterios/{$nombreArchivo}.dat";
    $contenidoActual = [];

    // Verificar si el archivo ya existe
    if (file_exists($filePath)) {
        $contenidoActual = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    } else {
        // Si el archivo no existe, obtener valores iniciales de la IA y crearlo
        $valoresIA = obtenerDatosIA($nombreArchivo);
        $valoresArray = explode(", ", $valoresIA);
        createDataFile($nombreArchivo, $valoresArray);
        $contenidoActual = $valoresArray;
    }

    // Verificar si el valor ya existe
    if (!in_array($valor, $contenidoActual)) {
        // Si el valor no existe, agregarlo al archivo
        $file = fopen($filePath, 'a');
        if ($file) {
            // Si ya hay contenido en el archivo, añadir una coma antes
            if (count($contenidoActual) > 0) {
                fwrite($file, ', ' . $valor);
            } else {
                fwrite($file, $valor);
            }
            fclose($file);

            // Obtener valores relacionados del asistente
            $valoresRelacionados = obtenerDatosIA($valor);
            $valoresRelacionadosArray = explode(", ", $valoresRelacionados);

            // Escribir los valores relacionados
            $file = fopen($filePath, 'a');
            foreach ($valoresRelacionadosArray as $valorRelacionado) {
                if (!in_array($valorRelacionado, $contenidoActual)) {
                    fwrite($file, ', ' . $valorRelacionado);
                }
            }
            fclose($file);

            return "Valor '{$valor}' y valores relacionados agregados.";
        } else {
            return "Error: No se pudo abrir el archivo para escritura.";
        }
    } else {
        return "El valor '{$valor}' ya existe.";
    }
}


function enviarMensaje($nombreCriterio, $token, $threadId) {
    // URL para enviar el mensaje al hilo
    $url = 'https://api.openai.com/v1/threads/' . $threadId . '/messages';

    // Encabezados requeridos para la autenticación
    $headers = [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json',
        'OpenAI-Beta: assistants=v2'  // Agregar el encabezado requerido
    ];

    // Crear el cuerpo de la solicitud con el contenido del mensaje
    $postData = json_encode([
        'content' => $nombreCriterio,
        'role' => 'user',
    ]);

    // Inicializar cURL para enviar el mensaje
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

    // Ejecutar la solicitud
    $response = curl_exec($ch);
    
    // Manejo de errores de cURL
    if (curl_errno($ch)) {
        $error_msg = curl_error($ch);
        curl_close($ch);
        file_put_contents('../resource/log.dat', "Error de cURL: " . $error_msg . "\n", FILE_APPEND);
        return null;
    }

    // Cerrar cURL
    curl_close($ch);

    // Decodificar la respuesta JSON
    $result = json_decode($response, true);

    // Verificar si hubo un error en la respuesta
    if (isset($result['error'])) {
        file_put_contents('../resource/log.dat', "Error en la respuesta de la API: " . $result['error']['message'] . "\n", FILE_APPEND);
        return null;
    }

    // Verificar si se ha creado un mensaje exitosamente
    if (isset($result['id'])) {
        $messageId = $result['id'];
        file_put_contents('../resource/log.dat', "ID del mensaje creado: " . $messageId . "\n", FILE_APPEND);
        return $messageId;
    }

    return null;
}

function crearRun($threadId, $assistantId, $token) {
    // URL para crear el run en el hilo
    $url = 'https://api.openai.com/v1/threads/' . $threadId . '/runs';

    // Encabezados para autenticación
    $headers = [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json',
        'OpenAI-Beta: assistants=v2'  // Agregar el encabezado requerido
    ];

    // Crear el cuerpo de la solicitud
    $postData = json_encode([
        'assistant_id' => $assistantId  // Aquí pasas el ID del asistente
    ]);

    // Inicializar cURL para enviar el request del run
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

    // Ejecutar la solicitud
    $response = curl_exec($ch);

    // Manejo de errores de cURL
    if (curl_errno($ch)) {
        $error_msg = curl_error($ch);
        curl_close($ch);
        file_put_contents('../resource/log.dat', "Error de cURL al crear el run: " . $error_msg . "\n", FILE_APPEND);
        return null;
    }

    curl_close($ch);

    // Decodificar la respuesta
    $result = json_decode($response, true);

    // Guardar la respuesta completa en el log para depuración
    file_put_contents('../resource/log.dat', "Respuesta de la API al crear el run: " . json_encode($result) . "\n", FILE_APPEND);

    // Verificar si se creó el run exitosamente
    if (isset($result['id'])) {
        return $result['id'];  // Devuelve el ID del run creado
    } else {
        // Si hay un error, escribir el error en el log
        if (isset($result['error'])) {
            file_put_contents('../resource/log.dat', "Error al crear el run: " . $result['error']['message'] . "\n", FILE_APPEND);
        }
        return null;
    }
}

function obtenerDatosIA($nombreCriterio) {


    $token = ''; // Tu API Key

    $assistantId = '';

    $threadId = '';



    // 1. Enviar el mensaje al hilo
    $messageId = enviarMensaje($nombreCriterio, $token, $threadId);
    if (!$messageId) {
        return 'Error: No se pudo enviar el mensaje.';
    }

    // 2. Crear el run para procesar el mensaje
    $runId = crearRun($threadId, $assistantId, $token);
    if (!$runId) {
        return 'Error: No se pudo crear el run.';
    }

    // 3. Esperar hasta que el run se complete
    $runCompletado = false;
    $maxRetries = 10;
    $retryDelay = 2;

    for ($i = 0; $i < $maxRetries; $i++) {
        $runStatus = verificarEstadoRun($threadId, $runId, $token);
        if ($runStatus === 'completed') {
            $runCompletado = true;
            break;
        }
        sleep($retryDelay); // Esperar antes de intentar nuevamente
    }

    if (!$runCompletado) {
        return 'Error: El run no se completó dentro del tiempo permitido.';
    }

    // 4. Obtener los mensajes del hilo
    $mensajes = obtenerMensajesDelThread($threadId, $token);

    // Verificar que haya mensajes
    if ($mensajes) {
        foreach ($mensajes as $mensaje) {
            if (isset($mensaje['run_id']) && $mensaje['run_id'] === $runId) {
                if (isset($mensaje['content'][0]['text']['value'])) {
                    // Eliminar el último carácter si es un punto o coma
                    $respuestaIA = rtrim($mensaje['content'][0]['text']['value'], ',.'); 
                    return $respuestaIA;
                }
            }
        }
    }

    return 'Error: No se encontró un mensaje generado por la IA para el run especificado.';
}




function obtenerMensajesDelThread($threadId, $token) {
    // URL para obtener los mensajes del thread
    $url = 'https://api.openai.com/v1/threads/' . $threadId . '/messages';

    // Encabezados requeridos para la autenticación
    $headers = [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json',
        'OpenAI-Beta: assistants=v2'  // Agregar el encabezado requerido
    ];

    // Inicializar cURL para obtener los mensajes del thread
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Ejecutar la solicitud
    $response = curl_exec($ch);
    curl_close($ch);

    // Decodificar la respuesta
    $result = json_decode($response, true);

    // Verificar si hubo un error en la respuesta
    if (isset($result['error'])) {
        file_put_contents('../resource/log.dat', "Error en la respuesta de la API: " . $result['error']['message'] . "\n", FILE_APPEND);
        return null;
    }

    // Retornar los mensajes del thread
    return isset($result['data']) ? $result['data'] : null;
}


function verificarEstadoRun($threadId, $runId, $token) {
    // URL para verificar el estado del run
    $url = 'https://api.openai.com/v1/threads/' . $threadId . '/runs/' . $runId;

    // Encabezados requeridos para la autenticación
    $headers = [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json',
        'OpenAI-Beta: assistants=v2'  // Agregar el encabezado requerido
    ];

    // Inicializar cURL para hacer la solicitud
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Ejecutar la solicitud
    $response = curl_exec($ch);
    curl_close($ch);

    // Decodificar la respuesta
    $result = json_decode($response, true);

    // Retornar el estado del run
    if (isset($result['status'])) {
        return $result['status'];
    } else {
        return null;
    }
}

?>
