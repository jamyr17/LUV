<?php
function enviarDatosAIA($usuarioId, $imagenNombre) {
    // Conectar a la base de datos y obtener los datos de zoom por usuario y imagen
    $conn = new mysqli('localhost', 'usuario', 'contraseña', 'base_datos');
    $query = "SELECT region, duration, zoom_scale FROM tbzoomregiones WHERE usuario_id = ? AND imagen_nombre = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('is', $usuarioId, $imagenNombre);
    $stmt->execute();
    $result = $stmt->get_result();

    $regiones = [];
    while ($row = $result->fetch_assoc()) {
        $regiones[] = [
            'region' => $row['region'],
            'duration' => $row['duration'],
            'zoom_scale' => $row['zoom_scale']
        ];
    }

    // Estructurar mensaje para el asistente IA
    $mensaje = [
        'role' => 'user',
        'content' => [
            [
                'type' => 'text',
                'text' => 'Aquí están los datos de zoom y regiones de la imagen:'
            ],
            [
                'type' => 'text',
                'text' => json_encode($regiones)
            ]
        ]
    ];

    // Enviar solicitud a la API del asistente
    $ch = curl_init('https://api.openai.com/v1/threads/TU_THREAD/messages');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($mensaje));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer TU_TOKEN',
        'Content-Type: application/json'
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}
