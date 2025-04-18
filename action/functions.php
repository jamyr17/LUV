<?php

include_once "cloudAction.php";
require_once 'gestionImagenesIAAction.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function guardarFormData()
{

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if (isset($_POST['create']) ||  isset($_POST['newUser'])) {
            $_SESSION['formCrearData'] = $_POST;
        } else if (isset($_POST['update'])) {
            $_SESSION['formActualizarData'] = $_POST;
        }
    }
}

function eliminarFormData()
{
    unset($_SESSION['formCrearData']);
    unset($_SESSION['formActualizarData']);
}

// generar input de texto para poder aplicar logica de si hay data que debe ser cargada o no
function generarCampoTexto($nombreCampo, $tipoForm, $placeholder, $valorPorDefecto = '')
{
    $valor = isset($_SESSION[$tipoForm][$nombreCampo]) ? htmlspecialchars($_SESSION[$tipoForm][$nombreCampo]) : htmlspecialchars($valorPorDefecto);
    $autofocusAttr = isset($_SESSION[$tipoForm][$nombreCampo]) ? 'autofocus' : '';

    echo "<input required type='text' name='$nombreCampo' id='$nombreCampo' class='form-control' placeholder='$placeholder' value='$valor' $autofocusAttr />";
}

// generar input de texto para poder aplicar logica de si hay data que debe ser cargada o no
function generarCampoTextoSinRequired($nombreCampo, $tipoForm, $placeholder, $valorPorDefecto = '')
{
    $valor = isset($_SESSION[$tipoForm][$nombreCampo]) ? htmlspecialchars($_SESSION[$tipoForm][$nombreCampo]) : htmlspecialchars($valorPorDefecto);
    $autofocusAttr = isset($_SESSION[$tipoForm][$nombreCampo]) ? 'autofocus' : '';

    echo "<input type='text' name='$nombreCampo' id='$nombreCampo' class='form-control' placeholder='$placeholder' value='$valor' $autofocusAttr />";
}


// generar input de textarea para poder aplicar logica de si hay data que debe ser cargada o no
function generarTextarea($nombreCampo, $tipoForm, $placeholder, $valorPorDefecto = '', $filas = 3, $columnas = 30, $autofocus = true)
{
    $valor = isset($_SESSION[$tipoForm][$nombreCampo]) ? htmlspecialchars($_SESSION[$tipoForm][$nombreCampo]) : htmlspecialchars($valorPorDefecto);
    $autofocusAttr = (!isset($_SESSION[$tipoForm][$nombreCampo]) && $autofocus) ? 'autofocus' : '';

    echo "<textarea name='$nombreCampo' id='$nombreCampo' class='form-control' placeholder='$placeholder' rows='$filas' cols='$columnas' $autofocusAttr>$valor</textarea>";
}

// generar input de password para poder aplicar logica de si hay data que debe ser cargada o no
function generarCampoContrasena($nombreCampo, $tipoForm, $placeholder, $valorPorDefecto = '')
{
    $valor = isset($_SESSION[$tipoForm][$nombreCampo]) ? htmlspecialchars($_SESSION[$tipoForm][$nombreCampo]) : htmlspecialchars($valorPorDefecto);
    $autofocusAttr = isset($_SESSION[$tipoForm][$nombreCampo]) ? 'autofocus' : '';

    echo "<input required type='password' name='$nombreCampo' id='$nombreCampo' class='form-control' placeholder='$placeholder' value='$valor' $autofocusAttr />";
}

function procesarImagen($nombreVariableForm, $directorio, $nombreArchivo)
{
    if (isset($_FILES[$nombreVariableForm]) && $_FILES[$nombreVariableForm]['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES[$nombreVariableForm]['tmp_name'];
        $fileExtension = strtolower(pathinfo($_FILES[$nombreVariableForm]['name'], PATHINFO_EXTENSION));
        $nombreOriginal = pathinfo($_FILES[$nombreVariableForm]['name'], PATHINFO_FILENAME);

        // Crear la imagen desde el archivo temporal
        switch ($fileExtension) {
            case 'jpg':
            case 'jpeg':
                $image = imagecreatefromjpeg($fileTmpPath);
                break;
            case 'png':
                $image = imagecreatefrompng($fileTmpPath);
                break;
            case 'gif':
                $image = imagecreatefromgif($fileTmpPath);
                break;
            default:
                echo "Tipo de archivo no soportado\n";
                return false;
        }

        if (!$image) {
            echo "No se pudo cargar la imagen\n";
            return false;
        }

        $newFileName = $nombreArchivo . '.webp';
        $destination = $directorio . $newFileName;

        if (function_exists('imagewebp') && imagewebp($image, $destination, 100)) {

            if ($_SESSION['nombreUsuario'] != null) {
                $nombreUsuario = $_SESSION['nombreUsuario'];
                $rutaDirectorioUsuario = "../resources/afinidadesUsuarios/$nombreUsuario/";
            }else{
                $rutaDirectorioUsuario = "../resources/afinidadesUsuarios/$nombreArchivo/";
            }
            
            
            // Crear la carpeta si no existe
            if (!is_dir($rutaDirectorioUsuario)) {
                if (!mkdir($rutaDirectorioUsuario, 0777, true)) {
                    echo "No se pudo crear el directorio del usuario\n";
                    imagedestroy($image);
                    return false;
                }
            }

            $urlImagenCloudinary = subirImagenACloudinary($fileTmpPath, $nombreArchivo);
            if (!$urlImagenCloudinary) {
                echo "Cloudinary problem\n";
                return false;
            }

            $criteriosIA = procesarImagenIA($urlImagenCloudinary);
            file_put_contents('debug.log', "Criterios IA respuesta: " . print_r($criteriosIA, true) . "\n", FILE_APPEND);
            
            if (!$criteriosIA) {
                file_put_contents('debug.log', "Error: Criterios IA no generados correctamente.\n", FILE_APPEND);
                return false;
            }
            
            $criterios = obtenerCriteriosCloud($criteriosIA);
            file_put_contents('debug.log', "Criterios obtenidos tras procesamiento: " . print_r($criterios, true) . "\n", FILE_APPEND);
            
            if (empty($criterios)) {
                file_put_contents('debug.log', "Error: Criterios vacíos después de obtenerCriteriosCloud.\n", FILE_APPEND);
                return false;
            }
            

            $archivoDatos = $rutaDirectorioUsuario . "dataAfinidad.dat";

            $segmentacionPredeterminada = [
                'Region' => ['1,1', '1,2', '1,3', '2,1', '2,2', '2,3', '3,1', '3,2', '3,3'],
                'Duracion' => array_fill(0, 9, 0),
                'ZoomScale' => array_fill(0, 9, 1)
            ];

            $lineaDatos = "ImagenURL: $destination ";
            $lineaDatos .= "| Duracion: " . implode(',', $segmentacionPredeterminada['Duracion']);
            $lineaDatos .= " | ZoomScale: " . implode(',', $segmentacionPredeterminada['ZoomScale']);
            $lineaDatos .= " | $criterios\n";

            file_put_contents($archivoDatos, $lineaDatos, FILE_APPEND | LOCK_EX);

            $archivoDatosImagenes = "../resources/img/criteriosImagenes.dat";
            if (!file_exists($archivoDatosImagenes)) {
                touch($archivoDatosImagenes);
            }
            file_put_contents($archivoDatosImagenes, "ImagenURL: $destination | $criterios\n", FILE_APPEND);

            imagedestroy($image);
            return $destination;
        } else {
            imagedestroy($image);
            return false;
        }
    }
    echo "No se encontró archivo en la solicitud\n";
    return false;
}

function levenshtein_algoritmo($str1, $str2)
{
    $len1 = strlen($str1);
    $len2 = strlen($str2);

    // Crear la matriz
    $d = array();
    for ($i = 0; $i <= $len1; $i++) {
        $d[$i] = array();
        $d[$i][0] = $i;
    }
    for ($j = 0; $j <= $len2; $j++) {
        $d[0][$j] = $j;
    }

    // Rellenar la matriz
    for ($i = 1; $i <= $len1; $i++) {
        for ($j = 1; $j <= $len2; $j++) {
            $cost = ($str1[$i - 1] == $str2[$j - 1]) ? 0 : 1;
            $d[$i][$j] = min(
                $d[$i - 1][$j] + 1,      // Eliminación
                $d[$i][$j - 1] + 1,      // Inserción
                $d[$i - 1][$j - 1] + $cost // Sustitución
            );
        }
    }

    // La distancia de Levenshtein está en la esquina inferior derecha
    return $d[$len1][$len2];
}

// Función para comprobar si el nombre es similar a uno ya existente
function esNombreSimilar($nombreNuevo, $nombresExistentes)
{
    $umbralSimilitud = 0.8;

    foreach ($nombresExistentes as $nombreExistente) {
        // Calcula la distancia de Levenshtein entre el nuevo nombre y el existente
        $distancia = levenshtein_algoritmo($nombreNuevo, $nombreExistente);

        // Calcula la longitud del nombre más largo para normalizar la distancia
        $longitudMaxima = max(strlen($nombreNuevo), strlen($nombreExistente));

        // Calcula la similitud como un porcentaje (0 a 1)
        $similitud = 1 - ($distancia / $longitudMaxima);

        // Si la similitud es mayor o igual al umbral, considera los nombres como similares
        if ($similitud >= $umbralSimilitud) {
            return true; // Nombre similar encontrado
        }
    }
    return false; // Ningún nombre similar encontrado
}