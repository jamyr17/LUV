<?php

include '../business/universidadBusiness.php';
include 'functions.php';

if (isset($_POST['update'])) {
    if (isset($_POST['nombre'])) {
        $idUniversidad = $_POST['idUniversidad'];
        $nombre = $_POST['nombre'];
        
        if (strlen($nombre) > 150) {
            guardarFormData();
            header("location: ../view/universidadView.php?error=longText");
            exit();
        }

        if (strlen($nombre) > 0 && !is_numeric($nombre)) {
            $universidadBusiness = new UniversidadBusiness();
            $resultExist = $universidadBusiness->exist($nombre);

            if ($resultExist == 1) {
                guardarFormData();
                header("location: ../view/universidadView.php?error=exist");
            } else {
                $universidad = new Universidad($idUniversidad, $nombre, 1);
                $result = $universidadBusiness->updateTbUniversidad($universidad);

                if ($result == 1) {
                    header("location: ../view/universidadView.php?success=updated");
                } else {
                    guardarFormData();
                    header("location: ../view/universidadView.php?error=dbError");
                }
            }
        } else {
            guardarFormData();
            header("location: ../view/universidadView.php?error=emptyField");
        }
    }
} else if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    // Verificar si la universidad tiene campus asociados
    if (isset($_POST['idUniversidad'])) {
        $idUniversidad = $_POST['idUniversidad'];
        $universidadBusiness = new UniversidadBusiness();
        $result = $universidadBusiness->checkAssociatedCampus($idUniversidad);

        if ($result['status'] === 'confirm') {
            // Si hay campus asociados, enviamos el mensaje para confirmación en el frontend
            echo json_encode($result);
            exit();
        } else {
            // Si no hay campus asociados, procedemos a la eliminación directamente
            $deleteResult = $universidadBusiness->deleteUniversityById($idUniversidad);
            echo json_encode($deleteResult);
            exit();
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ID de universidad no especificado.']);
        exit();
    }
} else if (isset($_POST['action']) && $_POST['action'] === 'deleteConfirmed') {
    // Eliminar definitivamente la universidad después de la confirmación
    if (isset($_POST['idUniversidad'])) {
        $idUniversidad = $_POST['idUniversidad'];
        $universidadBusiness = new UniversidadBusiness();
        $deleteResult = $universidadBusiness->deleteUniversityById($idUniversidad);
        echo json_encode($deleteResult);
        exit();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ID de universidad no especificado.']);
        exit();
    }
} else if (isset($_POST['create'])) {
    // Crear universidad
    if (isset($_POST['nombre'])) {
        $nombre = $_POST['nombre'];

        if (strlen($nombre) > 150) {
            guardarFormData();
            header("location: ../view/universidadView.php?error=longText");
            exit();
        }

        if (strlen($nombre) > 0 && !is_numeric($nombre)) {
            $universidadBusiness = new UniversidadBusiness();
            $resultExist = $universidadBusiness->exist($nombre);

            if ($resultExist == 1) {
                guardarFormData();
                header("location: ../view/universidadView.php?error=exist");
            } else {
                $universidad = new Universidad(0, $nombre, 1);
                $result = $universidadBusiness->insertTbUniversidad($universidad);

                if ($result == 1) {
                    header("location: ../view/universidadView.php?success=inserted");
                } else {
                    guardarFormData();
                    header("location: ../view/universidadView.php?error=dbError");
                }
            }
        } else {
            guardarFormData();
            header("location: ../view/universidadView.php?error=emptyField");
        }
    }
} else if (isset($_POST['restore'])) {
    // Restaurar universidad
    if (isset($_POST['idUniversidad'])) {
        $idUniversidad = $_POST['idUniversidad'];
        $universidadBusiness = new UniversidadBusiness();
        $result = $universidadBusiness->restoreTbUniversidad($idUniversidad);

        if ($result == 1) {
            header("location: ../view/universidadView.php?success=restored");
        } else {
            header("location: ../view/universidadView.php?error=dbError");
        }
    }
}

?>
