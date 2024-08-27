<?php

include '../data/imagenData.php';

class ImagenBusiness {

    private $imagenData;

    public function __construct() {
        $this->imagenData = new imagenData();
    }

    public function insertTbimagen($imagen) {
        return $this->imagenData->insertTbimagen($imagen);
    }

    public function updateTbimagen($imagen) {
        return $this->imagenData->updateTbimagen($imagen);
    }

    public function deleteTbimagen($idimagen) {
        return $this->imagenData->deleteTbimagen($idimagen);
    }

    public function getAllTbImagen() {
        return $this->imagenData->getAllTbImagen();
    }

    public function exist($nombre) {
        return $this->imagenData->exist($nombre);
    }

    public function getTbImagenById($id) {
        return $this->imagenData->getTbImagenById($id);
    }

    public function uploadImage($id, $nombreArchivo, $crudId, $registroId, $directorioActual) {
        
        $baseDir = '../resources/img/';
        $directory = $directorioActual;
        // Determinar subdirectorio
        if (!empty($crudId)) { 
            switch ($crudId) {
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
                    header("Location: ../view/imagenView.php?error=emptyField");
            }
        }
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
        // Obtener la imagen actual de la base de datos
        $imagen = $this->getTbImagenById($id);
        $nombre = $imagen->getTbImagenNombre();
        $fileExtension = pathinfo($nombre, PATHINFO_EXTENSION);
        

        if (isset($_FILES['imageUpload_']) && $_FILES['imageUpload_']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['imageUpload_']['tmp_name'];
            $fileExtension = pathinfo($_FILES['imageUpload_']['name'], PATHINFO_EXTENSION);

            // Obtener el nuevo nombre de archivo
            $nuevoNombreArchivo = strtolower(str_replace(' ', '-', $nombreArchivo));
            $newFileName = $nuevoNombreArchivo . '.' . $fileExtension;
            $destination = $directory . $newFileName;

            // Mover el archivo subido
            if (move_uploaded_file($fileTmpPath, $destination)) {
                // Actualizar la base de datos con la nueva imagen
                $imagen->setTbImagenNombre($newFileName);
                $imagen->setTbImagenCrudId($crudId);
                $imagen->setTbImagenRegistroId($registroId);
                $imagen->setTbImagenDirectorio($directory);
                $result = $this->updateTbimagen($imagen);

                if ($result == 1) {
                    header("Location: ../view/imagenView.php?success=updated");
                    exit;
                } else {
                    header("Location: ../view/imagenView.php?error=dbError");
                    exit;
                }
            } else {
                echo 'Error al mover el archivo.';
                exit;
            }
        } else {
            // Si no se sube una nueva imagen, actualiza la base de datos manteniendo la imagen existente
            $nuevoNombreArchivo = strtolower(str_replace(' ', '-', $nombreArchivo));
            $newFileName = $nuevoNombreArchivo . '.' . $fileExtension;
            $imagen->setTbImagenNombre($newFileName); // Aquí puedes mantener el nombre original si no cambia
            $result = $this->updateTbimagen($imagen);

            if ($result == 1) {
                header("Location: ../view/imagenView.php?success=updated");
                exit;
            } else {
                header("Location: ../view/imagenView.php?error=dbError");
                exit;
            }
        }
    }

    public function deleteFromServer() {
        
    }
}
