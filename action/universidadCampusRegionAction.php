<?php
include_once '../business/universidadCampusRegionBusiness.php';
include_once 'functions.php';

if (isset($_POST['update'])) {

    if (isset($_POST['idUniversidadCampusRegion']) && isset($_POST['nombre']) && isset($_POST['descripcion'])) {

        $idUniversidadCampusRegion = $_POST['idUniversidadCampusRegion'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];

        if (strlen($nombre) > 255) {
            header("Location: ../view/universidadCampusRegionView.php?error=nombreTooLong");
            exit();
        }
        
        if (strlen($descripcion) > 255) {
            header("Location: ../view/universidadCampusRegionView.php?error=descripcionTooLong");
            exit();
        }

        if (strlen($nombre) > 0 && strlen($descripcion) > 0) {
            if (!is_numeric($nombre) && !is_numeric($descripcion)) {
                $campusRegionBusiness = new UniversidadCampusRegionBusiness();

                $resultExist = $campusRegionBusiness->nameExist($nombre, $idUniversidadCampusRegion);

                if ($resultExist == 1) {
                    guardarFormData();
                    header("location: ../view/universidadCampusRegionView.php?error=exist");
                } else {
                    $campusRegion = new UniversidadCampusRegion($idUniversidadCampusRegion, $nombre, $descripcion, 1);

                    $result = $campusRegionBusiness->updateTbUniversidadCampusRegion($campusRegion);

                    if ($result == 1) {
                        header("location: ../view/universidadCampusRegionView.php?success=updated");
                    } else {
                        guardarFormData();
                        header("location: ../view/universidadCampusRegionView.php?error=dbError");
                    }
                }
            } else {
                guardarFormData();
                header("location: ../view/universidadCampusRegionView.php?error=numberFormat");
            }
        } else {
            guardarFormData();
            header("location: ../view/universidadCampusRegionView.php?error=emptyField");
        }
    } else {
        guardarFormData();
        header("location: ../view/universidadCampusRegionView.php?error=error");
    }
} else if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    if (isset($_POST['idUniversidadCampusRegion'])) {
        $idUniversidadCampusRegion = $_POST['idUniversidadCampusRegion'];
        $campusRegionBusiness = new UniversidadCampusRegionBusiness();
        $result = $campusRegionBusiness->checkAssociatedCampus($idUniversidadCampusRegion);

        // Si hay campus asociados
        if ($result['status'] === 'confirm') {
            echo json_encode($result); // Respuesta JSON para confirmación
            exit();
        } else {
            // Si no hay campus asociados
            $deleteResult = $campusRegionBusiness->deleteRegionById($idUniversidadCampusRegion);
            echo json_encode($deleteResult); // Procede con la eliminación
            exit();
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ID de universidad no especificado.']);
        exit();
    }
}

// Confirmación final de la eliminación
else if (isset($_POST['action']) && $_POST['action'] === 'deleteConfirmed') {
    if (isset($_POST['idUniversidadCampusRegion'])) {
        $idUniversidadCampusRegion = $_POST['idUniversidadCampusRegion'];
        $campusRegionBusiness = new UniversidadCampusRegionBusiness();
        $deleteResult = $campusRegionBusiness->deleteRegionById($idUniversidadCampusRegion);

        if ($deleteResult) {
            echo json_encode(['status' => 'success', 'message' => 'Región eliminada correctamente.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al eliminar la región.']);
        }
        exit();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ID de región no especificado.']);
        exit();
    }

}else if (isset($_POST['create'])) {

    if (isset($_POST['nombre']) && isset($_POST['descripcion'])) {

        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];

        if (strlen($nombre) > 255) {
            header("Location: ../view/universidadCampusRegionView.php?error=nombreTooLong");
            exit();
        }
        
        if (strlen($descripcion) > 255) {
            header("Location: ../view/universidadCampusRegionView.php?error=descripcionTooLong");
            exit();
        }

        if (strlen($nombre) > 0 && strlen($descripcion) > 0) {
            if (!is_numeric($nombre) && !is_numeric($descripcion)) {
                $campusRegionBusiness = new UniversidadCampusRegionBusiness();

                $resultExist = $campusRegionBusiness->exist($nombre);

                if ($resultExist == 1) {
                    guardarFormData();
                    header("location: ../view/universidadCampusRegionView.php?error=exist");
                }

                $nombresExistentes = $campusRegionBusiness->getAllTbUniversidadCampusRegionNombres();
                if (esNombreSimilar($nombre, $nombresExistentes)) {
                    guardarFormData();
                    header("location: ../view/universidadCampusRegionView.php?error=alike");
                    exit();
                } else {
                    $campusRegion = new UniversidadCampusRegion(0, $nombre, $descripcion, 1);

                    $result = $campusRegionBusiness->insertTbUniversidadCampusRegion($campusRegion);

                    if ($result == 1) {
                        header("location: ../view/universidadCampusRegionView.php?success=inserted");
                    } else {
                        guardarFormData();
                        header("location: ../view/universidadCampusRegionView.php?error=dbError");
                    }
                }
            } else {
                guardarFormData();
                header("location: ../view/universidadCampusRegionView.php?error=numberFormat");
            }
        } else {
            guardarFormData();
            header("location: ../view/universidadCampusRegionView.php?error=emptyField");
        }
    } else {
        guardarFormData();
        header("location: ../view/universidadCampusRegionView.php?error=error");
    }
}else if (isset($_POST['restore'])) {

    if (isset($_POST['idUniversidadCampusRegion'])) {
        $idUniversidadCampusRegion = $_POST['idUniversidadCampusRegion'];
        $campusRegionBusiness = new UniversidadCampusRegionBusiness();
        $result = $campusRegionBusiness->restoreTbCampusRegion($idUniversidadCampusRegion);

        if ($result == 1) {
            header("location: ../view/universidadCampusRegionView.php?success=restored");
        } else {
            header("location: ../view/universidadCampusRegionView.php?error=dbError");
        }
    } else {
        header("location: ../view/universidadCampusRegionView.php?error=error");
    }
}
?>
