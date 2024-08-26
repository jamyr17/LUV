<?php

include '../bussiness/universidadCampusEspecializacionBussiness.php';

if (isset($_POST['update'])) {

    if (isset($_POST['nombre']) && isset($_POST['descripcion'])) {
            
        $idCampusEspecializacion = $_POST['idCampusEspecializacion'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        
        if (strlen($nombre) > 0 && strlen($descripcion) > 0) {
            if (!is_numeric($nombre)) {
                $campusEspecializacionBussiness = new universidadCampusEspecializacionBussiness();

                $resultExist = $campusEspecializacionBussiness->nameExist($nombre, $idCampusEspecializacion);

                if ($resultExist) {
                    header("location: ../view/universidadCampusEspecializacionView.php?error=exist");
                } else {
                    $universidadCampusEspecializacion = new universidadCampusEspecializacion($idCampusEspecializacion, $nombre, $descripcion, 1);

                    $result = $campusEspecializacionBussiness->updateTbUniversidadCampusEspecializacion($universidadCampusEspecializacion);

                    if ($result) {
                        header("location: ../view/universidadCampusEspecializacionView.php?success=updated");
                    } else {
                        header("location: ../view/universidadCampusEspecializacionView.php?error=dbError");
                    }
                }
            } else {
                header("location: ../view/universidadCampusEspecializacionView.php?error=numberFormat");
            }
        } else {
            header("location: ../view/universidadCampusEspecializacionView.php?error=emptyField");
        }
    } else {
        header("location: ../view/universidadCampusEspecializacionView.php?error=error");
    }
} else if (isset($_POST['delete'])) {

    if (isset($_POST['idCampusEspecializacion'])) {

        $idCampusEspecializacion = $_POST['idCampusEspecializacion'];

        $campusEspecializacionBussiness = new universidadCampusEspecializacionBussiness();
        $result = $campusEspecializacionBussiness->deleteTbUniversidadCampusEspecializacion($idCampusEspecializacion);

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

        if (strlen($nombre) > 0 && strlen($descripcion) > 0) {
            if (!is_numeric($nombre)) {
                $campusEspecializacionBussiness = new universidadCampusEspecializacionBussiness();

                $resultExist = $campusEspecializacionBussiness->exist($nombre);

                if ($resultExist) {
                    header("location: ../view/universidadCampusEspecializacionView.php?error=exist");
                } else {
                    $universidadCampusEspecializacion = new universidadCampusEspecializacion(0, $nombre, $descripcion, 1);
    
                    $result = $campusEspecializacionBussiness->insertTbUniversidadCampusEspecializacion($universidadCampusEspecializacion);
    
                    if ($result) {
                        header("location: ../view/universidadCampusEspecializacionView.php?success=inserted");
                    } else {
                        header("location: ../view/universidadCampusEspecializacionView.php?error=dbError");
                    }
                }
            } else {
                header("location: ../view/universidadCampusEspecializacionView.php?error=numberFormat");
            }
        } else {
            header("location: ../view/universidadCampusEspecializacionView.php?error=emptyField");
        }
    } else {
        header("location: ../view/universidadCampusEspecializacionView.php?error=error");
    }
}
