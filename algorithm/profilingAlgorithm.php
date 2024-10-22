<?php

include_once '../business/generoBusiness.php';
include_once '../business/orientacionSexualBusiness.php';

$generoBusiness = new GeneroBusiness();
$orientacionSexualBusiness = new OrientacionSexualBusiness();

$generos = $generoBusiness->getAllTbGeneroNombres();
$orientacionesSexuales = $orientacionSexualBusiness->getAllTbOrientacionSexualNombres();

header('Content-Type: application/json');

// Verificar si los valores de POST están presentes
if (isset($_POST['genero']) && isset($_POST['orientacion'])) {
    $miGenero = $_POST['genero'];
    $miOrientacion = $_POST['orientacion'];

    // Función que devuelve las afinidades basadas en género y orientación sexual
    function obtenerAfinidades($genero, $orientacion) {
        global $generos, $orientacionesSexuales; // Importar las listas globales
        $afinidades = [];

        // Reglas de afinidad dinámicas basadas en las listas
        if ($orientacion === 'Heterosexual') {
            if ($genero === 'Masculino') {
                $afinidades[] = ['Genero' => 'Femenino', 'Orientacion' => 'Heterosexual'];
            } elseif ($genero === 'Femenino') {
                $afinidades[] = ['Genero' => 'Masculino', 'Orientacion' => 'Heterosexual'];
            }
        }

        if ($orientacion === 'Homosexual') {
            $afinidades[] = ['Genero' => $genero, 'Orientacion' => 'Homosexual'];
        }

        if ($orientacion === 'Bisexual') {
            if ($genero === 'Masculino') {
                $afinidades[] = ['Genero' => 'Femenino', 'Orientacion' => 'Heterosexual'];
                $afinidades[] = ['Genero' => 'Masculino', 'Orientacion' => 'Homosexual'];
            } elseif ($genero === 'Femenino') {
                $afinidades[] = ['Genero' => 'Masculino', 'Orientacion' => 'Heterosexual'];
                $afinidades[] = ['Genero' => 'Femenino', 'Orientacion' => 'Homosexual'];
            } else {
                foreach (['Masculino', 'Femenino'] as $gen) {
                    $afinidades[] = ['Genero' => $gen, 'Orientacion' => 'Heterosexual'];
                    $afinidades[] = ['Genero' => $gen, 'Orientacion' => 'Homosexual'];
                }
            }
        }

        // Puedes agregar el resto de los casos para otras orientaciones aquí...

        return $afinidades;
    }

    // Obtener afinidades
    $misAfinidades = obtenerAfinidades($miGenero, $miOrientacion);

    // Devolver las afinidades en formato JSON
    echo json_encode($misAfinidades);
} else {
    // Respuesta en caso de falta de parámetros
    echo json_encode(['error' => 'Faltan parámetros de entrada']);
}
