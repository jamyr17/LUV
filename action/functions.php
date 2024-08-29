<?php 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function guardarFormData(){
 
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        if(isset($_POST['create']) ||  isset($_POST['newUser'])){
            $_SESSION['formCrearData'] = $_POST;
        }else if(isset($_POST['update'])){
            $_SESSION['formActualizarData'] = $_POST;
        }
    }

}

function eliminarFormData(){
    unset($_SESSION['formCrearData']);
    unset($_SESSION['formActualizarData']);
}

// generar input de texto para poder aplicar logica de si hay data que debe ser cargada o no
function generarCampoTexto($nombreCampo, $tipoForm, $placeholder, $valorPorDefecto = '') {
    $valor = isset($_SESSION[$tipoForm][$nombreCampo]) ? htmlspecialchars($_SESSION[$tipoForm][$nombreCampo]) : htmlspecialchars($valorPorDefecto);
    $autofocusAttr = isset($_SESSION[$tipoForm][$nombreCampo]) ? 'autofocus' : '';

    echo "<input required type='text' name='$nombreCampo' id='$nombreCampo' class='form-control' placeholder='$placeholder' value='$valor' $autofocusAttr />";
}

// generar input de textarea para poder aplicar logica de si hay data que debe ser cargada o no
function generarTextarea($nombreCampo, $tipoForm, $placeholder, $valorPorDefecto = '', $filas = 3, $columnas = 30, $autofocus = true) {
    $valor = isset($_SESSION[$tipoForm][$nombreCampo]) ? htmlspecialchars($_SESSION[$tipoForm][$nombreCampo]) : htmlspecialchars($valorPorDefecto);
    $autofocusAttr = (!isset($_SESSION[$tipoForm][$nombreCampo]) && $autofocus) ? 'autofocus' : '';

    echo "<textarea name='$nombreCampo' id='$nombreCampo' class='form-control' placeholder='$placeholder' rows='$filas' cols='$columnas' $autofocusAttr>$valor</textarea>";
}

// generar input de password para poder aplicar logica de si hay data que debe ser cargada o no
function generarCampoContrasena($nombreCampo, $tipoForm, $placeholder, $valorPorDefecto = '') {
    $valor = isset($_SESSION[$tipoForm][$nombreCampo]) ? htmlspecialchars($_SESSION[$tipoForm][$nombreCampo]) : htmlspecialchars($valorPorDefecto);
    $autofocusAttr = isset($_SESSION[$tipoForm][$nombreCampo]) ? 'autofocus' : '';

    echo "<input required type='password' name='$nombreCampo' id='$nombreCampo' class='form-control' placeholder='$placeholder' value='$valor' $autofocusAttr />";
}

// procesar imagenes
function procesarImagen($nombreVariableForm, $directorio, $nombreArchivo) {
    if (isset($_FILES[$nombreVariableForm])) {
        echo "Archivo recibido.<br>";
        
        if ($_FILES[$nombreVariableForm]['error'] === UPLOAD_ERR_OK) {
            echo "Sin errores en la carga del archivo.<br>";
            
            $fileTmpPath = $_FILES[$nombreVariableForm]['tmp_name'];
            $fileExtension = pathinfo($_FILES[$nombreVariableForm]['name'], PATHINFO_EXTENSION);
            
            $newFileName = $nombreArchivo . '.' . $fileExtension;
            $destination = $directorio . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destination)) {
                return $destination;
            } else {
                return false;
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
}
