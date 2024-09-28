<?php

include_once '../business/criterioBusiness.php';
include_once 'functions.php';
include_once 'gestionArchivosIAAction.php';

$maxLength = 255;

if (isset($_POST['update'])) {
    if (isset($_POST['nombre'])) {
        $idCriterio = $_POST['idCriterio'];
        $nombre = $_POST['nombre'];

        if (strlen($nombre) > $maxLength) {
            guardarFormData();
            header("Location: ../view/criterioView.php?error=nameTooLong");
            exit();
        }

        if (strlen($nombre) > 0) {
            if (!is_numeric($nombre)) {
                $criterioBusiness = new CriterioBusiness();

                $resultExist = $criterioBusiness->exist($nombre);

                if ($resultExist == 1) {
                    guardarFormData();
                    header("location: ../view/criterioView.php?error=exist");
                } else {
                    $criterioNombreAnterior = $criterioBusiness->getCriterioNombreById($idCriterio);
                    $criterio = new Criterio($idCriterio, $nombre, 1);

                    rename("../resources/criterios/{$criterioNombreAnterior}.dat", "../resources/criterios/{$nombre}.dat");

                    $result = $criterioBusiness->updateTbCriterio($criterio);

                    if ($result == 1) {
                        header("location: ../view/criterioView.php?success=updated");
                    } else {
                        guardarFormData();
                        header("location: ../view/criterioView.php?error=dbError");
                    }
                }
            } else {
                guardarFormData();
                header("location: ../view/criterioView.php?error=numberFormat");
            }
        } else {
            guardarFormData();
            header("location: ../view/criterioView.php?error=emptyField");
        }
    } else {
        guardarFormData();
        header("location: ../view/criterioView.php?error=error");
    }

} else if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    if (isset($_POST['idCriterio'])) {
        $idCriterio = $_POST['idCriterio'];
        $criterioBusiness = new CriterioBusiness();
        $result = $criterioBusiness->checkAssociatedValues($idCriterio);

        // Si hay valores asociados
        if ($result['status'] === 'confirm') {
            echo json_encode($result); // Respuesta JSON para confirmación
            exit();
        } else {
            // Si no hay valores asociados
            $deleteResult = $criterioBusiness->deleteCriterioById($idCriterio);
            echo json_encode($deleteResult); // Procede con la eliminación
            exit();
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ID de criterio no especificado.']);
        exit();
    }
}

// Confirmación final de la eliminación
else if (isset($_POST['action']) && $_POST['action'] === 'deleteConfirmed') {
    if (isset($_POST['idCriterio'])) {
        $idCriterio = $_POST['idCriterio'];
        $criterioBusiness = new CriterioBusiness();
        $deleteResult = $criterioBusiness->deleteCriterioById($idCriterio);

        if ($deleteResult) {
            echo json_encode(['status' => 'success', 'message' => 'Criterio eliminado correctamente.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al eliminar el criterio.']);
        }
        exit();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ID de criterio no especificado.']);
        exit();
    }
} else if (isset($_POST['create'])) {
     if (isset($_POST['nombre'])) {
         $nombre = $_POST['nombre'];
 
         if (strlen($nombre) > $maxLength) {
             guardarFormData();
             header("Location: ../view/criterioView.php?error=nameTooLong");
             exit();
         }
 
         if (strlen($nombre) > 0 && !is_numeric($nombre)) {
             $criterioBusiness = new CriterioBusiness();
             $resultExist = $criterioBusiness->exist($nombre);
 
             if ($resultExist == 0) {
 
                 $nombresExistentes = $criterioBusiness->getAllTbCriterioNombres();
 
                 if (esNombreSimilar($nombre, $nombresExistentes)) {
                     guardarFormData();
                     header("Location: ../view/criterioView.php?error=alike");
                     exit();
                 } else {
                     createFolderIfNotExists('../resources/criterios'); // Crear carpeta si no existe.
 
                     // Obtener datos relacionados al criterio desde la IA.
                     $data = obtenerDatosIA($nombre);
 
                     if ($data) {
                         createDataFile($nombre, $data);  // Guardar los datos en un archivo .dat.
                     }
 
                     // Crear el nuevo criterio en la base de datos.
                     $criterio = new Criterio(0, $nombre, 1);
                     $criterioBusiness->insertTbCriterio($criterio);
 
                     header("location: ../view/criterioView.php?success=inserted");
                     exit();
                 }
             } else {
                 guardarFormData();
                 header("location: ../view/criterioView.php?error=exist");
                 exit();
             }
         } else {
             guardarFormData();
             header("location: ../view/criterioView.php?error=numberFormat");
             exit();
         }
     } else {
         guardarFormData();
         header("location: ../view/criterioView.php?error=emptyField");
     }
 } else if (isset($_POST['restore'])) {

    if (isset($_POST['idCriterio'])) {
        $idCriterio = $_POST['idCriterio'];
        $CriterioBusiness = new CriterioBusiness();
        $result = $CriterioBusiness->restoreTbCriterio($idCriterio);

        if ($result == 1) {
            header("location: ../view/criterioView.php?success=restored");
        } else {
            header("location: ../view/criterioView.php?error=dbError");
        }
    } else {
        header("location: ../view/criterioView.php?error=error");
    }
}
