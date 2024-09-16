<?php

include_once '../business/universidadCampusEspecializacionBusiness.php';
include_once 'functions.php';

$maxLength = 255;

if (isset($_POST['update'])) {

    if (isset($_POST['nombre']) && isset($_POST['descripcion'])) {
            
        $idCampusEspecializacion = $_POST['idCampusEspecializacion'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        
        if (strlen($nombre) > $maxLength) {
            guardarFormData();
            header("Location: ../view/universidadCampusEspecializacionView.php?error=nameTooLong");
            exit();
        }

        if (strlen($descripcion) > $maxLength) {
            guardarFormData();
            header("Location: ../view/universidadCampusEspecializacionView.php?error=descriptionTooLong");
            exit();
        }

        if (strlen($nombre) > 0 && strlen($descripcion) > 0) {
            if (!is_numeric($nombre)) {
                $campusEspecializacionBusiness = new universidadCampusEspecializacionBusiness();

                $resultExist = $campusEspecializacionBusiness->nameExist($nombre, $idCampusEspecializacion);

                if ($resultExist) {
                    guardarFormData();
                    header("location: ../view/universidadCampusEspecializacionView.php?error=exist");
                } else {
                    $universidadCampusEspecializacion = new universidadCampusEspecializacion($idCampusEspecializacion, $nombre, $descripcion, 1);

                    $result = $campusEspecializacionBusiness->updateTbUniversidadCampusEspecializacion($universidadCampusEspecializacion);

                    if ($result) {
                        header("location: ../view/universidadCampusEspecializacionView.php?success=updated");
                    } else {
                        guardarFormData();
                        header("location: ../view/universidadCampusEspecializacionView.php?error=dbError");
                    }
                }
            } else {
                guardarFormData();
                header("location: ../view/universidadCampusEspecializacionView.php?error=numberFormat");
            }
        } else {
            guardarFormData();
            header("location: ../view/universidadCampusEspecializacionView.php?error=emptyField");
        }
    } else {
        guardarFormData();
        header("location: ../view/universidadCampusEspecializacionView.php?error=error");
    }
} else if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    if (isset($_POST['idCampusEspecializacion'])) {
        $idUniversidadCampusEspecializacion = $_POST['idCampusEspecializacion'];
        $campusEspecializacionBusiness = new universidadCampusEspecializacionBusiness();
        $result = $campusEspecializacionBusiness->checkAssociatedCampusSpecialization($idUniversidadCampusEspecializacion);

        // Si hay campus asociados
        if ($result['status'] === 'confirm') {
            echo json_encode($result); // Respuesta JSON para confirmación
            exit();
        } else {
            // Si no hay campus asociados
            $deleteResult = $campusEspecializacionBusiness->deleteSpecializationById($idUniversidadCampusEspecializacion);
            echo json_encode($deleteResult); // Procede con la eliminación
            exit();
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ID de especialización no especificado.']);
        exit();
    }
}

// Confirmación final de la eliminación
else if (isset($_POST['action']) && $_POST['action'] === 'deleteConfirmed') {
    if (isset($_POST['idCampusEspecializacion'])) {
        $idUniversidadCampusEspecializacion = $_POST['idCampusEspecializacion'];
        $campusEspecializacionBusiness = new universidadCampusEspecializacionBusiness();
        $deleteResult = $campusEspecializacionBusiness->deleteSpecializationById($idUniversidadCampusEspecializacion);

        if ($deleteResult) {
            echo json_encode(['status' => 'success', 'message' => 'Especialización eliminada correctamente.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al eliminar la especialización.']);
        }
        exit();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ID de especialización no especificado.']);
        exit();
    }
} else if (isset($_POST['create'])) {

    if (isset($_POST['nombre']) && isset($_POST['descripcion'])) {

        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];

        if (strlen($nombre) > $maxLength) {
            guardarFormData();
            header("Location: ../view/universidadCampusEspecializacionView.php?error=nameTooLong");
            exit();
        }

        if (strlen($descripcion) > $maxLength) {
            guardarFormData();
            header("Location: ../view/universidadCampusEspecializacionView.php?error=descriptionTooLong");
            exit();
        }

        if (strlen($nombre) > 0 && strlen($descripcion) > 0) {
            if (!is_numeric($nombre)) {
                $campusEspecializacionBusiness = new universidadCampusEspecializacionBusiness();

                $resultExist = $campusEspecializacionBusiness->exist($nombre);

                if ($resultExist) {
                    guardarFormData();
                    header("location: ../view/universidadCampusEspecializacionView.php?error=exist");
                }
                
                $nombresExistentes = $campusEspecializacionBusiness->getAllTbUniversidadCampusEspecializacionNombres();
                if (esNombreSimilar($nombre, $nombresExistentes)) {
                    guardarFormData();
                    header("location: ../view/universidadCampusEspecializacionView.php?error=alike");
                    exit();
                } else {
                    $universidadCampusEspecializacion = new universidadCampusEspecializacion(0, $nombre, $descripcion, 1);
    
                    $result = $campusEspecializacionBusiness->insertTbUniversidadCampusEspecializacion($universidadCampusEspecializacion);
    
                    if ($result) {
                        header("location: ../view/universidadCampusEspecializacionView.php?success=inserted");
                    } else {
                        guardarFormData();
                        header("location: ../view/universidadCampusEspecializacionView.php?error=dbError");
                    }
                }
            } else {
                guardarFormData();
                header("location: ../view/universidadCampusEspecializacionView.php?error=numberFormat");
            }
        } else {
            guardarFormData();
            header("location: ../view/universidadCampusEspecializacionView.php?error=emptyField");
        }
    } else {
        guardarFormData();
        header("location: ../view/universidadCampusEspecializacionView.php?error=error");
    }
}else if (isset($_POST['restore'])) {

    if (isset($_POST['idCampusEspecializacion'])) {
        $idCampusEspecializacion = $_POST['idCampusEspecializacion'];
        $campusEspecializacionBusiness = new universidadCampusEspecializacionBusiness();
        $result = $campusEspecializacionBusiness->restoreTbCampusEspecializacion($idCampusEspecializacion);

        if ($result == 1) {
            header("location: ../view/universidadCampusEspecializacionView.php?success=restored");
        } else {
            header("location: ../view/universidadCampusEspecializacionView.php?error=dbError");
        }
    } else {
        header("location: ../view/universidadCampusEspecializacionView.php?error=error");
    }
}
?>
