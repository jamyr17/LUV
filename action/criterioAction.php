<?php

include '../business/criterioBusiness.php';
include 'functions.php';

$maxLength = 255;


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
            fwrite($file, $data);
        }
        fclose($file);
    } else {
        echo "Error: No se pudo crear el archivo {$filePath}";
    }
}

function obtenerDatosIA($nombre, $apiKey) {
    $headers = [
        'Authorization: Bearer ' . $apiKey,
        'Content-Type: application/json'
    ];

    // Prompt dirigido y más claro
    $prompt = "Genera una lista de opciones claras y concisas para el criterio llamado '{$nombre}'. Cada opción debe estar relacionada directamente con el criterio y ser breve, por ejemplo, 'Gustos por el Arte': 'Pintura', 'Escultura', 'Música', 'Teatro', 'Literatura'.";
    
    $postData = [
        "inputs" => $prompt,
        "options" => ["wait_for_model" => true]
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api-inference.huggingface.co/models/gpt2');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);

    // Extrae solo el texto generado y separa por comas o líneas
    if (isset($data[0]['generated_value'])) {
        $opciones = explode("\n", trim($data[0]['generated_value']));
        // Asegúrate de limpiar cualquier texto no deseado
        $opcionesFiltradas = array_filter($opciones, function($opcion) {
            return !empty($opcion) && strlen($opcion) < 50; // Filtro básico
        });
        return $opcionesFiltradas;
    }

    return [""]; // Valor de fallback si la IA no generó nada útil
}



if (isset($_POST['update'])) {
    if (isset($_POST['nombre'])) {
        $idCriterio = $_POST['idCriterio'];
        $nombre = $_POST['nombre'];

        if (strlen($nombre) > $maxLength) {
            guardarFormData();
            header("Location: ../view/criterioView.php?error=nameTooLong");
            exit();
        }


        if (strlen($nombre) > 0) {
            if (!is_numeric($nombre)) {
                $criterioBusiness = new CriterioBusiness();

                $resultExist = $criterioBusiness->exist($nombre);

                if ($resultExist == 1) {
                    guardarFormData();
                    header("location: ../view/criterioView.php?error=exist");
                } else {
                    $criterioNombreAnterior = $criterioBusiness->getCriterioNombreById($idCriterio);
                    $criterio = new Criterio($idCriterio, $nombre, 1);

                    rename("../resources/criterios/{$criterioNombreAnterior}.dat", "../resources/criterios/{$nombre}.dat");

                    $result = $criterioBusiness->updateTbCriterio($criterio);

                    if ($result == 1) {
                        header("location: ../view/criterioView.php?success=updated");
                    } else {
                        guardarFormData();
                        header("location: ../view/criterioView.php?error=dbError");
                    }
                }
            } else {
                guardarFormData();
                header("location: ../view/criterioView.php?error=numberFormat");
            }
        } else {
            guardarFormData();
            header("location: ../view/criterioView.php?error=emptyField");
        }
    } else {
        guardarFormData();
        header("location: ../view/criterioView.php?error=error");
    }
} else if (isset($_POST['delete'])) {
    if (isset($_POST['idCriterio'])) {
        $idCriterio = $_POST['idCriterio'];

        $criterioBusiness = new CriterioBusiness();
        
        $criterioNombre = $criterioBusiness->getCriterioNombreById($idCriterio);

        $result = $criterioBusiness->deleteTbCriterio($idCriterio);

        if ($result == 1) {
            $criterioFile = "../resources/criterios/{$criterioNombre}.dat";
            if (file_exists($criterioFile)) {
                unlink($criterioFile);
            }
            header("location: ../view/criterioView.php?success=deleted");
        } else {
            header("location: ../view/criterioView.php?error=dbError");
        }
    } else {
        header("location: ../view/criterioView.php?error=error");
    }

} else if (isset($_POST['create'])) {
    if (isset($_POST['nombre'])) {
        $nombre = $_POST['nombre'];
        
        if (strlen($nombre) > $maxLength) {
            guardarFormData();
            header("Location: ../view/criterioView.php?error=nameTooLong");
            exit();
        }

        if (strlen($nombre) > 0 && !is_numeric($nombre)) {
            $criterioBusiness = new CriterioBusiness();
            $resultExist = $criterioBusiness->exist($nombre);
            if ($resultExist == 0) {
                createFolderIfNotExists('../resources/criterios');
                
                // Obtener datos de la IA
                $data = obtenerDatosIA($nombre, $apiKey);
                
                if ($data) {
                    createDataFile($nombre, $data);
                }

                $criterio = new Criterio(0, $nombre, 1);
                $criterioBusiness->insertTbCriterio($criterio);
                header("location: ../view/criterioView.php?success=inserted");
            } else {
                guardarFormData();
                header("location: ../view/criterioView.php?error=exist");
            }
        } else {
            guardarFormData();
            header("location: ../view/criterioView.php?error=numberFormat");
        }
    } else {
        guardarFormData();
        header("location: ../view/criterioView.php?error=emptyField");
    }
}else if (isset($_POST['restore'])) {

    if (isset($_POST['idCriterio'])) {
        $idCriterio = $_POST['idCriterio'];
        $CriterioBusiness = new CriterioBusiness();
        $result = $CriterioBusiness->restoreTbCriterio($idCriterio);

        if ($result == 1) {
            header("location: ../view/criterioView.php?success=restored");
        } else {
            header("location: ../view/criterioView.php?error=dbError");
        }
    } else {
        header("location: ../view/criterioView.php?error=error");
    }
}
?>
