<?php
include "../action/sessionAdminAction.php";
include '../business/imagenBusiness.php';
include '../business/universidadBusiness.php';
include '../business/campusBusiness.php';
include '../business/areaConocimientoBusiness.php';
include '../business/generoBusiness.php';
include '../business/orientacionSexualBusiness.php';
include_once '../domain/imagenDomain.php';
include 'functions.php';

$imagenBusiness = new ImagenBusiness();
$universidadBusiness = new UniversidadBusiness();
$campusBusiness = new CampusBusiness();
$areaConocimientoBusiness = new AreaConocimientoBusiness();
$generoBusiness = new GeneroBusiness();
$orientacionSexualBusiness = new OrientacionSexualBusiness();

define('BASE_DIR', '../resources/img/');
define('DIR_UNIVERSIDAD', BASE_DIR . 'universidad/');
define('DIR_AREA_CONOCIMIENTO', BASE_DIR . 'areaConocimiento/');
define('DIR_GENERO', BASE_DIR . 'genero/');
define('DIR_ORIENTACION_SEXUAL', BASE_DIR . 'orientacionSexual/');
define('DIR_CAMPUS', BASE_DIR . 'campus/');

if (isset($_POST['create'])) {
    $type = $_POST['idOptionsHidden'] ?? '';
    $selectedId = $_POST['dynamic-select'] ?? '';
    $itemName = strtolower(str_replace(' ', '-', $_POST['dynamic-select-name'] ?? ''));

    $directory = getDirectoryByType($type);
    createDirectoryIfNotExists($directory);

    if (isset($_FILES['imageUpload']) && $_FILES['imageUpload']['error'] === UPLOAD_ERR_OK) {
        $fileType = mime_content_type($_FILES['imageUpload']['tmp_name']);
        if (!in_array($fileType, ['image/jpeg', 'image/png', 'image/gif'])) {
            header("Location: ../view/imagenView.php?error=fileTypeNotAllowed");
            exit;
        }

        if ($_FILES['imageUpload']['size'] > 5 * 1024 * 1024) {
            header("Location: ../view/imagenView.php?error=fileSizeExceeded");
            exit;
        } 

        if(archiveExist($directory, $itemName)){
            header("Location: ../view/imagenView.php?error=archiveExist");
            exit;
        }

        if (procesarImagen('imageUpload', $directory, $itemName)) {
            $imagen = new Imagen(0, $type, $selectedId, $itemName . '.webp', $directory, 1);
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
} elseif (isset($_POST['delete'])) {
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
                header("Location: ../view/imagenView.php?error=fileNotFound");
            }
        }
    }
} elseif (isset($_POST['update'])) {
    $id = $_POST['id'] ?? '';
    $nombreArchivo = $_POST['nombreArchivo'] ?? '';
    $nombreArchivoImagen = $_POST['dynamic-select-name-update'] ?? '';
    $crudId = $_POST['idOptionsHidden'] ?? '';
    $crudActualizadoId = $_POST['idOptionsUpdate'] ?? '';
    $registroId = $_POST['idRegistroHidden'] ?? '';
    $registroActualizadoId = $_POST['dynamic-select'] ?? '';
    $directorioActual = $_POST['directorioActualHidden'] ?? '';
    $actualizarDirectorio = 0;

    if ($registroId != 0 && !empty($crudId)) {
        $actualizarDirectorio = 1;
    }
    
    $directory = getDirectoryByType($crudActualizadoId);
    createDirectoryIfNotExists($directory);

    $imagen = $imagenBusiness->getTbImagenById($id);
    $imagen->setTbImagenNombre($nombreArchivo . '.webp');
    $imagen->setTbImagenCrudId($crudActualizadoId);
    $imagen->setTbImagenRegistroId($registroActualizadoId);
    $imagen->setTbImagenDirectorio($directory);

    if($imagenBusiness->getTbImagenById($id)->getTbImagenNombre()!==$imagen->getTbImagenNombre()){
        if(archiveExist($directory, $nombreArchivo)){
            header("Location: ../view/imagenView.php?error=archiveExist");
            exit;
        }
    }

    if (!empty($_POST['id']) && !empty($_POST['nombreArchivo'])) {
        if (isset($_FILES['imageUpload_']) && $_FILES['imageUpload_']['error'] === UPLOAD_ERR_OK || $actualizarDirectorio) {
            $fileType = mime_content_type($_FILES['imageUpload_']['tmp_name']);
            if (!in_array($fileType, ['image/jpeg', 'image/png', 'image/gif'])) {
                header("Location: ../view/imagenView.php?error=fileTypeNotAllowed");
                exit;
            }

            if ($_FILES['imageUpload_']['size'] > 5 * 1024 * 1024) {
                header("Location: ../view/imagenView.php?error=fileSizeExceeded");
                exit;
            }

            if (procesarImagen('imageUpload_', $directory, $nombreArchivo)) {
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
        } else {
            $result = $imagenBusiness->updateTbimagen($imagen);

            if ($result == 1) {
                header("Location: ../view/imagenView.php?success=updated");
            } else {
                header("Location: ../view/imagenView.php?error=dbError");
            }
        }
    } else {
        header("Location: ../view/imagenView.php?error=missingData");
    }
} else {
    header("Location: ../view/imagenView.php?error=unknown");
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

function archiveExist($directory, $archiveName) {
    if (!is_dir($directory)) {
        return false;
    }
    
    $path = rtrim($directory, '/') . '/' . $archiveName . '.webp' ;
    echo $path;

    return file_exists($path);
}

