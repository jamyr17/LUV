<?php

include_once '../business/universidadCampusColectivoBusiness.php';
include_once 'functions.php';
$maxLength = 255;

if (isset($_POST['update'])) {

    if (isset($_POST['nombre']) && isset($_POST['descripcion'])) {
            
        $idUniversidadCampusColectivo = $_POST['idUniversidadCampusColectivo'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];

        if (strlen($nombre) > $maxLength) {
            guardarFormData();
            header("Location: ../view/universidadCampusColectivoView.php?error=nameTooLong");
            exit();
        }

        if (strlen($descripcion) > $maxLength) {
            guardarFormData();
            header("Location: ../view/universidadCampusColectivoView.php?error=descriptionTooLong");
            exit();
        }

        
        if (strlen($nombre) > 0 && strlen($descripcion) > 0) {
            if (!is_numeric($nombre)) {
                // Verificar que no exista un registro con el mismo valor que está siendo ingresado
                $universidadCampusColectivoBusiness = new universidadCampusColectivoBusiness();

                $resultExist = $universidadCampusColectivoBusiness->nameExist($nombre, $idUniversidadCampusColectivo);

                if ($resultExist == 1) {
                    guardarFormData();
                    header("location: ../view/universidadCampusColectivoView.php?error=exist");
                } else {
                    $universidadCampusColectivo = new universidadCampusColectivo($idUniversidadCampusColectivo, $nombre, $descripcion, 1);

                    $result = $universidadCampusColectivoBusiness->updateTbUniversidadCampusColectivo($universidadCampusColectivo);

                    if ($result == 1) {
                        header("location: ../view/universidadCampusColectivoView.php?success=updated");
                    } else {
                        guardarFormData();
                        header("location: ../view/universidadCampusColectivoView.php?error=dbError");
                    }

                }
                
            } else {
                guardarFormData();
                header("location: ../view/universidadCampusColectivoView.php?error=numberFormat");
            }
        } else {
            guardarFormData();
            header("location: ../view/universidadCampusColectivoView.php?error=emptyField");
        }
    } else {
        guardarFormData();
        header("location: ../view/universidadCampusColectivoView.php?error=error");
    }
} else if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    if (isset($_POST['idCampusColectivo'])) {
        $idCampusColectivo = $_POST['idCampusColectivo'];
        $campusColectivoBusiness = new universidadCampusColectivoBusiness();
        $result = $campusColectivoBusiness->checkAssociatedCampusColectivo($idCampusColectivo);

        // Si hay campus asociados
        if ($result['status'] === 'confirm') {
            echo json_encode($result); // Respuesta JSON para confirmación
            exit();
        } else {
            // Si no hay campus asociados
            $deleteResult = $campusColectivoBusiness->deleteColectivoById($idCampusColectivo);
            echo json_encode($deleteResult); // Procede con la eliminación
            exit();
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ID de colectivo no especificado.']);
        exit();
    }
}

// Confirmación final de la eliminación
else if (isset($_POST['action']) && $_POST['action'] === 'deleteConfirmed') {
    if (isset($_POST['idCampusColectivo'])) {
        $idCampusColectivo = $_POST['idCampusColectivo'];
        $campusColectivoBusiness = new universidadCampusColectivoBusiness();
        $deleteResult = $campusColectivoBusiness->deleteColectivoById($idCampusColectivo);

        if ($deleteResult) {
            echo json_encode(['status' => 'success', 'message' => 'Colectivo eliminado correctamente.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al eliminar el colectivo.']);
        }
        exit();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ID de colectivo no especificado.']);
        exit();
    }
} else if (isset($_POST['create'])) {

    if (isset($_POST['nombre']) && isset($_POST['descripcion'])) {
            
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];

        if (strlen($nombre) > $maxLength) {
            guardarFormData();
            header("Location: ../view/universidadCampusColectivoView.php?error=nameTooLong");
            exit();
        }

        if (strlen($descripcion) > $maxLength) {
            guardarFormData();
            header("Location: ../view/universidadCampusColectivoView.php?error=descriptionTooLong");
            exit();
        }
        
        if (strlen($nombre) > 0 && strlen($descripcion) > 0) {
            if (!is_numeric($nombre)) {

                $universidadCampusColectivoBusiness = new universidadCampusColectivoBusiness();

                $resultExist = $universidadCampusColectivoBusiness->exist($nombre);

                if ($resultExist == 1) {
                    guardarFormData();
                    header("location: ../view/universidadCampusColectivoView.php?error=exist");
                } 
                
                $nombresExistentes = $universidadCampusColectivoBusiness->getAllTbUniversidadCampusColectivoNombres();
                
                if (esNombreSimilar($nombre, $nombresExistentes)) {
                    guardarFormData();
                    header("location: ../view/universidadCampusColectivoView.php?error=alike");
                    exit();
                    
                } else {
                    $universidadCampusColectivo = new universidadCampusColectivo(0, $nombre, $descripcion, 1);
    
                    $result = $universidadCampusColectivoBusiness->insertTbUniversidadCampusColectivo($universidadCampusColectivo);
    
                    if ($result['result'] == 1) {
                        header("location: ../view/universidadCampusColectivoView.php?success=inserted");
                    } else {
                        guardarFormData();
                        header("location: ../view/universidadCampusColectivoView.php?error=dbError");
                    }

                }
                
            } else {
                guardarFormData();
                header("location: ../view/universidadCampusColectivoView.php?error=numberFormat");
            }
        } else {
            guardarFormData();
            header("location: ../view/universidadCampusColectivoView.php?error=emptyField");
        }
    } else {
        guardarFormData();
        header("location: ../view/universidadCampusColectivoView.php?error=error");
    }
    
}else if (isset($_POST['colectivoadd'])) {

    if (isset($_POST['nombre']) && trim($_POST['nombre']) !== '') {
        $nombre = trim($_POST['nombre']);

        if (!is_numeric($nombre)) {
            $universidadCampusColectivoBusiness = new universidadCampusColectivoBusiness();

            $resultExist = $universidadCampusColectivoBusiness->exist($nombre);

            if ($resultExist == 1) {
                echo json_encode(['status' => 'error', 'code' => 'exist']);
            } else {
                $universidadCampusColectivo = new universidadCampusColectivo(0, $nombre, "1", 0);

                $result = $universidadCampusColectivoBusiness->insertTbUniversidadCampusColectivo($universidadCampusColectivo);
                
                error_log("Resultado de la inserción del colectivo: " . json_encode($result));
                
                if ($result['result']) {
                    echo json_encode(['status' => 'success', 'id' => $result['id']]);
                } else {
                    echo json_encode(['status' => 'error', 'code' => 'dbError']);
                }
            }
        } else {
            echo json_encode(['status' => 'error', 'code' => 'numberFormat']);
        }
    } else {
        echo json_encode(['status' => 'error', 'code' => 'emptyField']);
    }
} else if (isset($_POST['restore'])) {
    if (isset($_POST['idColectivo'])) {
        $idColectivo = $_POST['idColectivo'];
        $campusColectivoBusiness = new universidadCampusColectivoBusiness();
        $restoreResult = $campusColectivoBusiness->restoreTbCampusColectivo($idColectivo);

        if ($restoreResult) { // Verifica si la restauración fue exitosa
            header("Location: ../view/universidadCampusColectivoView.php?success=restored");
        } else {
            header("Location: ../view/universidadCampusColectivoView.php?error=dbError");
        }
        exit();
    } else {
        header("Location: ../view/universidadCampusColectivoView.php?error=error");
        exit();
    }
}
