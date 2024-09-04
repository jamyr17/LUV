<?php
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

    // Definir directorio base
    $baseDir = '../resources/img/';

    // Determinar subdirectorio
    switch ($type) {
        case '1':
            $directory = $baseDir . 'universidad/';
            break;
        case '2':
            $directory = $baseDir . 'areaConocimiento/';
            break;
        case '3':
            $directory = $baseDir . 'genero/';
            break;
        case '4':
            $directory = $baseDir . 'orientacionSexual/';
            break;
        case '5':
            $directory = $baseDir . 'campus/';
            break;
        default:
            die('Tipo no válido');
    }

    // Crear el directorio si no existe
    if (!file_exists($directory)) {
        mkdir($directory, 0777, true);
    }

    // Obtener el nombre del archivo del combo box
    $itemName = $_POST['dynamic-select-name'] ?? ''; // Campo para el nombre del registro
    $itemName = strtolower(str_replace(' ', '-', $itemName));

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
    if (!empty($_POST['id']) && !empty($_POST['nombreArchivo'])) {
        if ($actualizarDirectorio == 1) {
            $imagenBusiness->uploadImage($id, $nombreArchivoImagen, $crudId, $registroId, $directorioActual);
        } else {
            $imagenBusiness->uploadImage($id, $nombreArchivo, $crudId, $registroId, $directorioActual);
        }
    } else {
        header("Location: ../view/imagenView.php?error=emptyField");
    }
}
