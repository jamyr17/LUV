<?php
$token = 'tu_token_openai';
$threadId = 'thread_agadzic0eyyqqGYzxXbUEYne';

$data = [
    "role" => "user",
    "content" => [
        [
            "type" => "image_url",
            "image_url" => [
                "url" => "https://faunacr.com/wp-content/uploads/2022/04/shutterstock_1249664152-1-scaled.jpg"
            ]
        ]
    ]
];

$headers = [
    'Authorization: Bearer ' . $token,
    'Content-Type: application/json'
];

// Enviar la solicitud
$ch = curl_init("https://api.openai.com/v1/threads/$threadId/messages");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);
curl_close($ch);

// Procesar la respuesta del asistente
$responseData = json_decode($response, true);
$criterios = [];

if (isset($responseData['data'][0]['content']['text']['value'])) {
    // Convertir la respuesta en una lista de criterios
    $criterios = explode(', ', $responseData['data'][0]['content']['text']['value']);
}

// Guardar criterios en el archivo de texto
foreach ($criterios as $criterio) {
    if (preg_match('/\((\d+),\s*(\d+)\)/', $criterio, $matches)) {
        $region = "{$matches[1]},{$matches[2]}";
        $criterio = trim(str_replace($matches[0], '', $criterio));

        // Enviar los datos al archivo de guardado
        $formData = json_encode(['region' => $region, 'criterio' => $criterio]);
        file_get_contents('http://localhost/LUV/action/guardarSegmentacion.php', false, stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/json",
                'content' => $formData
            ]
        ]));
    }
}

// Enviar respuesta de éxito
echo json_encode(['status' => 'success', 'message' => 'Segmentación procesada y guardada.']);
?>
