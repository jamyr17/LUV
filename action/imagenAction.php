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
            header("Location: ../view/imagenView.php?error=fileExist");
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

    // Determinar el directorio y asegurar que existe
    $directorio = getDirectoryByType($crudActualizadoId);
    createDirectoryIfNotExists($directorio);

    $imagenActualizada = $imagenBusiness->getTbImagenById($id);
    $imagenActualizada->setTbImagenNombre($nombreArchivo . '.webp');
    $imagenActualizada->setTbImagenCrudId($crudActualizadoId);
    $imagenActualizada->setTbImagenRegistroId($registroActualizadoId);
    $imagenActualizada->setTbImagenDirectorio($directorio);

    // Obtener el registro de imagen anterior
    $imagenAnterior = $imagenBusiness->getTbImagenById($id);

    // Comprobar si el nombre de la imagen o directorio ha cambiado 
    if ($imagenAnterior->getTbImagenNombre() !== $imagenActualizada->getTbImagenNombre() || $imagenAnterior->getTbImagenDirectorio() !== $imagenActualizada->getTbImagenDirectorio()) {
        
        // ya existe una imagen con ese nombre en el directorio de destino
        if (archiveExist($directorio, $nombreArchivo)) {
            header("Location: ../view/imagenView.php?error=fileExist");
            exit;
        }

        $rutaImagenAnterior = $imagenAnterior->getTbImagenDirectorio() . '/' . $imagenAnterior->getTbImagenNombre();
        $nuevaRutaImagen = $directorio . '/' . $imagenActualizada->getTbImagenNombre();

        if (file_exists($rutaImagenAnterior)) {
            if (!rename($rutaImagenAnterior, $nuevaRutaImagen)) {
                header("Location: ../view/imagenView.php?error=fileMoveError");
                exit;
            }
        }else{
            header("Location: ../view/imagenView.php?error=previouslyDeleted");
            exit;
        }
    }

    // validar datos
    if (!empty($_POST['id']) && !empty($_POST['nombreArchivo'])) {
        // si se subió una imagen nueva se debe procesar
        if (isset($_FILES['imageUpload_']) && $_FILES['imageUpload_']['error'] === UPLOAD_ERR_OK) {
            // Comprobar tipo de archivo
            $fileType = mime_content_type($_FILES['imageUpload_']['tmp_name']);
            if (!in_array($fileType, ['image/jpeg', 'image/png', 'image/gif'])) {
                header("Location: ../view/imagenView.php?error=fileTypeNotAllowed");
                exit;
            }

            // Comprobar tamaño del archivo
            if ($_FILES['imageUpload_']['size'] > 5 * 1024 * 1024) {
                header("Location: ../view/imagenView.php?error=fileSizeExceeded");
                exit;
            }

            // Procesar y mover la imagen
            if (procesarImagen('imageUpload_', $directorio, $nombreArchivo)) {
                $result = $imagenBusiness->updateTbimagen($imagenActualizada);

                if ($result == 1) {
                    header("Location: ../view/imagenView.php?success=updated");
                } else {
                    header("Location: ../view/imagenView.php?error=dbError");
                }
                exit;
            } else {
                header("Location: ../view/imagenView.php?error=movingImg");
                exit;
            }
        } else {
            // Actualizar el registro sin carga de archivo
            $result = $imagenBusiness->updateTbimagen($imagenActualizada);

            if ($result == 1) {
                header("Location: ../view/imagenView.php?success=updated");
            } else {
                header("Location: ../view/imagenView.php?error=dbError");
            }
            exit;
        }
    } else {
        header("Location: ../view/imagenView.php?error=missingData");
        exit;
    }
} else {
    header("Location: ../view/imagenView.php?error=unknown");
    exit;
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

