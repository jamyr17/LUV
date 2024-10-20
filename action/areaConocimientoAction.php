<?php

include '../business/areaConocimientoBusiness.php';
include 'functions.php';

$maxLength = 255;

if (isset($_POST['update'])) {

    if (isset($_POST['nombre']) && isset($_POST['descripcion'])) {
            
        $idAreaConocimiento = $_POST['idAreaConocimiento'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];

        if (strlen($nombre) > $maxLength) {
            guardarFormData();
            header("Location: ../view/areaConocimientoView.php?error=nameTooLong");
            exit();
        }

        if (strlen($descripcion) > $maxLength) {
            guardarFormData();
            header("Location: ../view/areaConocimientoView.php?error=descriptionTooLong");
            exit();
        }
        
        if (strlen($nombre) > 0 && strlen($descripcion) > 0) {
            if (!is_numeric($nombre) && !is_numeric($descripcion)) {

                $areaConocimientoBusiness = new AreaConocimientoBusiness();

                $resultExist = $areaConocimientoBusiness->nameExist($nombre, $idAreaConocimiento);

                if ($resultExist) {
                    guardarFormData();
                    header("location: ../view/areaConocimientoView.php?error=exist");
                } else {
                    $areaConocimiento = new AreaConocimiento($idAreaConocimiento, $nombre, $descripcion, 1);

                    $result = $areaConocimientoBusiness->updateTbAreaConocimiento($areaConocimiento);

                    if ($result == 1) {
                        header("location: ../view/areaConocimientoView.php?success=updated");
                    } else {
                        guardarFormData();
                        header("location: ../view/areaConocimientoView.php?error=dbError");
                    }

                }
                
            } else {
                guardarFormData();
                header("location: ../view/areaConocimientoView.php?error=numberFormat");
            }
        } else {
            guardarFormData();
            header("location: ../view/areaConocimientoView.php?error=emptyField");
        }
    } else {
        guardarFormData();
        header("location: ../view/areaConocimientoView.php?error=error");
    }
}else if (isset($_POST['delete'])) {

    if (isset($_POST['idAreaConocimiento'])) {

        $idAreaConocimiento = $_POST['idAreaConocimiento'];
        echo "$idAreaConocimiento";

        $areaConocimientoBusiness = new AreaConocimientoBusiness();
        $result = $areaConocimientoBusiness->deleteTbAreaConocimiento($idAreaConocimiento);

        if ($result == 1) {
            header("location: ../view/areaConocimientoView.php?success=deleted");
        } else {
            header("location: ../view/areaConocimientoView.php?error=dbError");
        }
    } else {
        header("location: ../view/areaConocimientoView.php?error=error");
    }
} else if (isset($_POST['create'])) {

    if (isset($_POST['nombre']) && isset($_POST['descripcion'])) {
        
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];

        if (strlen($nombre) > $maxLength) {
            guardarFormData();
            header("Location: ../view/areaConocimientoView.php?error=nameTooLong");
            exit();
        }

        if (strlen($descripcion) > $maxLength) {
            guardarFormData();
            header("Location: ../view/areaConocimientoView.php?error=descriptionTooLong");
            exit();
        }

        if (strlen($nombre) > 0 && strlen($descripcion) > 0) {
            if (!is_numeric($nombre) && !is_numeric($descripcion)) {
                
                $areaConocimientoBusiness = new AreaConocimientoBusiness();

                $resultExist = $areaConocimientoBusiness->exist($nombre);

                if ($resultExist == 1) {
                    guardarFormData();
                    header("location: ../view/areaConocimientoView.php?error=exist");
                    exit();
                }

                $nombresExistentes = $areaConocimientoBusiness->getAllTbAreaConocimientoNombres();
                if (esNombreSimilar($nombre, $nombresExistentes)) {
                    guardarFormData();
                    header("location: ../view/areaConocimientoView.php?error=alike");
                    exit();
                } else {
                    $areaConocimiento = new AreaConocimiento(0, $nombre, $descripcion, 1);
    
                    $result = $areaConocimientoBusiness->insertTbAreaConocimiento($areaConocimiento);
    
                    if ($result == 1) {
                        header("location: ../view/areaConocimientoView.php?success=inserted");
                        exit();
                    } else {
                        guardarFormData();
                        header("location: ../view/areaConocimientoView.php?error=dbError");
                        exit();
                    }

                }
                
            } else {
                guardarFormData();
                header("location: ../view/areaConocimientoView.php?error=numberFormat");
                exit();
            }
        } else {
            guardarFormData();
            header("location: ../view/areaConocimientoView.php?error=emptyField");
        }
    } else {
        guardarFormData();
        header("location: ../view/areaConocimientoView.php?error=error");
    }
}else if (isset($_POST['restore'])) {

    if (isset($_POST['idAreaConocimiento'])) {
        $idAreaConocimiento = $_POST['idAreaConocimiento'];
        $AreaConocimientoBusiness = new AreaConocimientoBusiness();
        $result = $AreaConocimientoBusiness->restoreTbCampusAreaConocimiento($idAreaConocimiento);

        if ($result == 1) {
            header("location: ../view/areaConocimientoView.php?success=restored");
        } else {
            header("location: ../view/areaConocimientoView.php?error=dbError");
        }
    } else {
        header("location: ../view/areaConocimientoView.php?error=error");
    }
}
