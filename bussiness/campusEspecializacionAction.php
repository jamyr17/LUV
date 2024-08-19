<?php

include './campusEspecializacionBussiness.php';

if (isset($_POST['update'])) {

    if (isset($_POST['nombre']) && isset($_POST['descripcion'])) {
            
        $idCampusEspecializacion = $_POST['idCampusEspecializacion'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        
        if (strlen($nombre) > 0 && strlen($descripcion) > 0) {
            if (!is_numeric($nombre)) {
                // verificar que no exista un registro con el mismo valor que esta siendo ingresado
                $campusEspecializacionBusiness = new CampusEspecializacionBussiness();

                $resultExist = $campusEspecializacionBusiness->exist($nombre);

                if ($resultExist == 1) {
                    header("location: ../view/campusEspecializacionView.php?error=exist");
                } else {
                    $campusEspecializacion = new CampusEspecializacion($idCampusEspecializacion, $nombre, $descripcion, 1);

                    $result = $campusEspecializacionBusiness->updateTbCampusEspecializacion($campusEspecializacion);

                    if ($result == 1) {
                        header("location: ../view/campusEspecializacionView.php?success=updated");
                    } else {
                        header("location: ../view/campusEspecializacionView.php?error=dbError");
                    }

                }
                
            } else {
                header("location: ../view/campusEspecializacionView.php?error=numberFormat");
            }
        } else {
            header("location: ../view/campusEspecializacionView.php?error=emptyField");
        }
    } else {
        header("location: ../view/campusEspecializacionView.php?error=error");
    }
} else if (isset($_POST['delete'])) {

    if (isset($_POST['idCampusEspecializacion'])) {

        $idCampusEspecializacion = $_POST['idCampusEspecializacion'];
        echo "$idCampusEspecializacion";

        $campusEspecializacionBussiness = new CampusEspecializacionBussiness();
        $result = $campusEspecializacionBussiness->deleteTbCampusEspecializacion($idCampusEspecializacion);

        if ($result == 1) {
            header("location: ../view/campusEspecializacionView.php?success=deleted");
        } else {
            header("location: ../view/campusEspecializacionView.php?error=dbError");
        }
    } else {
        header("location: ../view/campusEspecializacionView.php?error=error");
    }
} else if (isset($_POST['create'])) {

    if (isset($_POST['nombre']) && isset($_POST['descripcion'])) {

        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];

        if (strlen($nombre) > 0 && strlen($descripcion > 0)) {
            if (!is_numeric($nombre)) {
                // verificar que no exista un registro con el mismo valor que esta siendo ingresado
                $campusEspecializacionBussiness = new CampusEspecializacionBussiness();

                $resultExist = $campusEspecializacionBussiness->exist($nombre);

                if ($resultExist == 1) {
                    header("location: ../view/campusEspecializacionView.php?error=exist");
                } else {
                    $campusEspecializacion = new CampusEspecializacion(0, $nombre, $descripcion, 1);
    
                    $result = $campusEspecializacionBussiness->insertTbCampusEspecializacion($campusEspecializacion);
    
                    if ($result == 1) {
                        header("location: ../view/campusEspecializacionView.php?success=inserted");
                    } else {
                        header("location: ../view/campusEspecializacionView.php?error=dbError");
                    }
 
                }
                
            } else {
                header("location: ../view/campusEspecializacionView.php?error=numberFormat");
            }
        } else {
            header("location: ../view/campusEspecializacionView.php?error=emptyField");
        }
    } else {
        header("location: ../view/campusEspecializacionView.php?error=error");
    }
}
