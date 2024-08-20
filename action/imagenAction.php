<?php
include '../bussiness/universidadBussiness.php';
include '../bussiness/campusBussiness.php';
include '../bussiness/areaConocimientoBussiness.php';
include '../bussiness/generoBusiness.php';
include '../bussiness/orientacionSexualBussiness.php';
include '../bussiness/imagenBusiness.php';
include_once  '../domain/imagen.php';

$universidadBusiness = new UniversidadBusiness();
$campusBusiness = new CampusBusiness();
$areaConocimientoBusiness = new AreaConocimientoBussiness();
$generoBusiness = new GeneroBusiness();
$orientacionSexualBusiness = new OrientacionSexualBusiness();
$imagenBusiness = new ImagenBusiness();

if(isset($_POST['create'])) {
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

// Procesar la imagen subida
if (isset($_FILES['imageUpload']) && $_FILES['imageUpload']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['imageUpload']['tmp_name'];
    $fileExtension = pathinfo($_FILES['imageUpload']['name'], PATHINFO_EXTENSION);

    // Obtener el nombre del archivo del combo box
    $itemName = $_POST['dynamic-select-name'] ?? ''; // Campo para el nombre del registro
    $itemName = strtolower(str_replace(' ', '-', $itemName));
    $newFileName = $itemName . '.' . $fileExtension;
    $destination = $directory . $newFileName;

    if (move_uploaded_file($fileTmpPath, $destination)) {
        $imagen = new Imagen(0, $type, 0, $newFileName, $directory, 1);
        $result = $imagenBusiness->insertTbimagen($imagen);

        if ($result == 1) {
            header("location: ../view/imagenView.php?success=inserted");
        } else {
            header("location: ../view/imagenView.php?error=dbError");
        }
    } else {
        echo 'Error al mover el archivo.';
    }
} else {
    echo 'No se ha subido ningún archivo o ha ocurrido un error.';
}

} else if (isset($_POST['delete'])) {
    if (isset($_POST['id'])) {

        $id = $_POST['id'];

        $imagenBusiness = new ImagenBusiness();
        $result = $imagenBusiness->deleteTbimagen($id);

        if ($result == 1) {
            header("location: ../view/imagenView.php?success=deleted");
        } else {
            header("location: ../view/imagenView.php?error=dbError");
        }
    } else {
        header("location: ../view/imagenView.php?error=error");
    }

}
?>
