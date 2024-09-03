<?php

include '../business/universidadCampusEspecializacionBusiness.php';
include 'functions.php';

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
} else if (isset($_POST['delete'])) {

    if (isset($_POST['idCampusEspecializacion'])) {

        $idCampusEspecializacion = $_POST['idCampusEspecializacion'];

        $campusEspecializacionBusiness = new universidadCampusEspecializacionBusiness();
        $result = $campusEspecializacionBusiness->deleteTbUniversidadCampusEspecializacion($idCampusEspecializacion);

        if ($result) {
            header("location: ../view/universidadCampusEspecializacionView.php?success=deleted");
        } else {
            header("location: ../view/universidadCampusEspecializacionView.php?error=dbError");
        }
    } else {
        header("location: ../view/universidadCampusEspecializacionView.php?error=error");
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
}
?>
