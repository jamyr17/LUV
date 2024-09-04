<?php
define('BASE_DIR', '../resources/img/');
define('DIR_UNIVERSIDAD', BASE_DIR . 'universidad/');
define('DIR_AREA_CONOCIMIENTO', BASE_DIR . 'areaConocimiento/');
define('DIR_GENERO', BASE_DIR . 'genero/');
define('DIR_ORIENTACION_SEXUAL', BASE_DIR . 'orientacionSexual/');
define('DIR_CAMPUS', BASE_DIR . 'campus/');

include '../business/universidadBusiness.php';
include '../business/campusBusiness.php';
include '../business/areaConocimientoBusiness.php';
include '../business/generoBusiness.php';
include '../business/orientacionSexualBusiness.php';
include '../business/imagenBusiness.php';
include_once  '../domain/imagenDomain.php';
include 'functions.php';

$universidadBusiness = new UniversidadBusiness();
$campusBusiness = new CampusBusiness();
$areaConocimientoBusiness = new AreaConocimientoBusiness();
$generoBusiness = new GeneroBusiness();
$orientacionSexualBusiness = new OrientacionSexualBusiness();
$imagenBusiness = new ImagenBusiness();

if (isset($_POST['create'])) {
    // Obtener tipo y valor del combo box
    $type = $_POST['idOptionsHidden'] ?? '';
    $selectedId = $_POST['dynamic-select'] ?? '';
    // Obtener el nombre del archivo del combo box
    $itemName = strtolower(str_replace(' ', '-', $_POST['dynamic-select-name'] ?? ''));

    $directory = getDirectoryByType($type);
    createDirectoryIfNotExists($directory);

    // Procesar la imagen subida
    if (isset($_FILES['imageUpload']) && $_FILES['imageUpload']['error'] === UPLOAD_ERR_OK) {

        if (procesarImagen('imageUpload',$directory,$itemName)) {
            $imagen = new Imagen(0, $type, $selectedId, $itemName .'.webp', $directory, 1);
            $result = $imagenBusiness->insertTbimagen($imagen);

            if ($result == 1) {
                header("Location: ../view/imagenView.php?success=inserted");
            } else {
                header("Location: ../view/imagenView.php?error=dbError");
            }
        } else {
            header("Location: ../view/imagenView.php?error=movingImg");
        }
    } else {
        header("Location: ../view/imagenView.php?error=unknown");
    }
} else if (isset($_POST['delete'])) {
    if (isset($_POST['id'])) {

        $id = $_POST['id'];
        $imagen = $imagenBusiness->getTbImagenById($id);

        if ($imagen) {

            $filePath = $imagen->getTbImagenDirectorio() . '/' . $imagen->getTbImagenNombre();

            if (file_exists($filePath)) {
                if (unlink($filePath)) {
                    $result = $imagenBusiness->deleteTbimagen($id);

                    if ($result == 1) {
                        header("Location: ../view/imagenView.php?success=deleted");
                    } else {
                        header("Location: ../view/imagenView.php?error=dbError");
                    }
                } else {
                    header("Location: ../view/imagenView.php?error=fileDeleteError");
                }
            } else {
                header("location: ../view/imagenView.php?error=error");
            }
        }
    }
} else if (isset($_POST['update'])) {
    $id = $_POST['id'] ?? '';
    $nombreArchivo = $_POST['nombreArchivo'] ?? '';
    $nombreArchivoImagen = $_POST['dynamic-select-name-update'] ?? '';
    $crudId = $_POST['idOptionsHidden'];
    $registroId = $_POST['idRegistroHidden'];
    $directorioActual = $_POST['directorioActualHidden'] ?? '';
    $actualizarDirectorio = 0;

    if ($registroId != 0 && !empty($_POST['idOptionHidden'])) {
        $actualizarDirectorio = 1;
    }
    
    $directory = getDirectoryByType($crudId);
    createDirectoryIfNotExists($directory);

    $imagen = $imagenBusiness->getTbImagenById($id);
    $imagen->setTbImagenNombre($nombreArchivo. '.webp');
    $imagen->setTbImagenCrudId($crudId);
    $imagen->setTbImagenRegistroId($registroId);
    $imagen->setTbImagenDirectorio($directory);

    if (!empty($_POST['id']) && !empty($_POST['nombreArchivo'])) {
        
        if (isset($_FILES['imageUpload_']) && $_FILES['imageUpload_']['error'] === UPLOAD_ERR_OK || $actualizarDirectorio){

            if(procesarImagen('imageUpload_',$directory,$nombreArchivo)){
                $result = $imagenBusiness->updateTbimagen($imagen);

                if ($result == 1) {
                    header("Location: ../view/imagenView.php?success=updated");
                    exit;
                } else {
                    header("Location: ../view/imagenView.php?error=dbError");
                    exit;
                }

            } else {
                header("Location: ../view/imagenView.php?error=movingImg");
                exit;
            }

        }else{
            $result = $imagenBusiness->updateTbimagen($imagen);

            if ($result == 1) {
                header("Location: ../view/imagenView.php?success=updated");
                exit;
            } else {
                header("Location: ../view/imagenView.php?error=dbError");
                exit;
            }
        }

    } else {
        header("Location: ../view/imagenView.php?error=emptyField");
    }
}

function getDirectoryByType($type) {
    switch ($type) {
        case '1':
            return DIR_UNIVERSIDAD;
        case '2':
            return DIR_AREA_CONOCIMIENTO;
        case '3':
            return DIR_GENERO;
        case '4':
            return DIR_ORIENTACION_SEXUAL;
        case '5':
            return DIR_CAMPUS;
        default:
            header("Location: ../view/imagenView.php?error=formIncomplete");
    }
}

function createDirectoryIfNotExists($directory) {
    if (!file_exists($directory)) {
        mkdir($directory, 0777, true);
    }
}
