<?php

include_once '../business/generoBusiness.php';
include_once '../business/orientacionSexualBusiness.php';

$generoBusiness = new GeneroBusiness();
$orientacionSexualBusiness = new OrientacionSexualBusiness();

$generos = $generoBusiness->getAllTbGeneroNombres();
$orientacionesSexuales = $orientacionSexualBusiness->getAllTbOrientacionSexualNombres();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');  // Captura el cuerpo de la solicitud
    $postData = json_decode($input, true);  // Decodifica el JSON recibido

    if (isset($postData['genero']) && isset($postData['orientacion'])) {
        $miGenero = $postData['genero'];
        $miOrientacion = $postData['orientacion'];

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

            if ($orientacion === 'Pansexual') {
                foreach ($generos as $gen) {
                    foreach ($orientacionesSexuales as $ori) {
                        $afinidades[] = ['Genero' => $gen, 'Orientacion' => $ori];
                    }
                }
            }
        
            if ($orientacion === 'Asexual') {
                foreach ($generos as $gen) {
                    $afinidades[] = ['Genero' => $gen, 'Orientacion' => 'Asexual'];
                }
            }
        
            if ($orientacion === 'Demisexual') {
                foreach (['Masculino', 'Femenino'] as $gen) {
                    $afinidades[] = ['Genero' => $gen, 'Orientacion' => 'Demisexual'];
                }
            }
        
            if ($orientacion === 'Sapiosexual') {
                foreach ($generos as $gen) {
                    $afinidades[] = ['Genero' => $gen, 'Orientacion' => 'Sapiosexual'];
                }
            }
        
            if ($orientacion === 'Autosexual') {
                $afinidades[] = ['Genero' => $genero, 'Orientacion' => 'Autosexual'];
            }
        
            if ($orientacion === 'Androsexual') {
                foreach ($generos as $gen) {
                    if ($gen === 'Masculino') {
                        $afinidades[] = ['Genero' => 'Masculino', 'Orientacion' => 'Androsexual'];
                    }
                }
            }
        
            if ($orientacion === 'Ginesexual') {
                foreach ($generos as $gen) {
                    if ($gen === 'Femenino') {
                        $afinidades[] = ['Genero' => 'Femenino', 'Orientacion' => 'Ginesexual'];
                    }
                }
            }
        
            if ($orientacion === 'Polisexual') {
                foreach ($generos as $gen) {
                    if ($gen !== 'Masculino' && $gen !== 'Femenino') {
                        $afinidades[] = ['Genero' => $gen, 'Orientacion' => 'Polisexual'];
                    }
                }
            }
        
            if ($orientacion === 'Skoliosexual') {
                foreach ($generos as $gen) {
                    if ($gen !== 'Masculino' && $gen !== 'Femenino') {
                        $afinidades[] = ['Genero' => $gen, 'Orientacion' => 'Skoliosexual'];
                    }
                }
            }
        
            if ($orientacion === 'Omnisexual') {
                foreach ($generos as $gen) {
                    $afinidades[] = ['Genero' => $gen, 'Orientacion' => 'Omnisexual'];
                }
            }
        
            if ($orientacion === 'Grisexual') {
                foreach (['Masculino', 'Femenino'] as $gen) {
                    $afinidades[] = ['Genero' => $gen, 'Orientacion' => 'Grisexual'];
                }
            }
        
            if ($orientacion === 'Fraysexual') {
                foreach ($generos as $gen) {
                    $afinidades[] = ['Genero' => $gen, 'Orientacion' => 'Fraysexual'];
                }
            }
        
            return $afinidades;
        }

        $misAfinidades = obtenerAfinidades($miGenero, $miOrientacion);

        echo json_encode($misAfinidades);
    } else {
        // Respuesta en caso de falta de parámetros
        echo json_encode(['error' => 'Faltan parámetros de entrada']);
    }
}