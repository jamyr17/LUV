<?php

include './campusColectivoBussiness.php';

if (isset($_POST['update'])) {

    if (isset($_POST['nombre']) && isset($_POST['descripcion'])) {
            
        $idCampusColectivo = $_POST['idCampusColectivo'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        
        if (strlen($nombre) > 0 && strlen($descripcion) > 0) {
            if (!is_numeric($nombre)) {
                // verificar que no exista un registro con el mismo valor que está siendo ingresado
                $campusColectivoBussiness = new CampusColectivoBussiness();

                $resultExist = $campusColectivoBussiness->exist($nombre);

                if ($resultExist == 1) {
                    header("location: ../view/campusColectivoView.php?error=exist");
                } else {
                    $campusColectivo = new CampusColectivo($idCampusColectivo, $nombre, $descripcion, 1);

                    $result = $campusColectivoBussiness->updateTbCampusColectivo($campusColectivo);

                    if ($result == 1) {
                        header("location: ../view/campusColectivoView.php?success=updated");
                    } else {
                        header("location: ../view/campusColectivoView.php?error=dbError");
                    }

                }
                
            } else {
                header("location: ../view/campusColectivoView.php?error=numberFormat");
            }
        } else {
            header("location: ../view/campusColectivoView.php?error=emptyField");
        }
    } else {
        header("location: ../view/campusColectivoView.php?error=error");
    }
} else if (isset($_POST['delete'])) {

    if (isset($_POST['idCampusColectivo'])) {

        $idCampusColectivo = $_POST['idCampusColectivo'];

        $campusColectivoBussiness = new CampusColectivoBussiness();
        $result = $campusColectivoBussiness->deleteTbCampusColectivo($idCampusColectivo);

        if ($result == 1) {
            header("location: ../view/campusColectivoView.php?success=deleted");
        } else {
            header("location: ../view/campusColectivoView.php?error=dbError");
        }
    } else {
        header("location: ../view/campusColectivoView.php?error=error");
    }
} else if (isset($_POST['create'])) {

    if (isset($_POST['nombre']) && isset($_POST['descripcion'])) {
            
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];

        if (strlen($nombre) > 0 && strlen($descripcion) > 0) {
            if (!is_numeric($nombre)) {
                // verificar que no exista un registro con el mismo valor que está siendo ingresado
                $campusColectivoBussiness = new CampusColectivoBussiness();

                $resultExist = $campusColectivoBussiness->exist($nombre);

                if ($resultExist == 1) {
                    header("location: ../view/campusColectivoView.php?error=exist");
                } else {
                    $campusColectivo = new CampusColectivo(0, $nombre, $descripcion, 1);
    
                    $result = $campusColectivoBussiness->insertTbCampusColectivo($campusColectivo);
    
                    if ($result == 1) {
                        header("location: ../view/campusColectivoView.php?success=inserted");
                    } else {
                        header("location: ../view/campusColectivoView.php?error=dbError");
                    }

                }
                
            } else {
                header("location: ../view/campusColectivoView.php?error=numberFormat");
            }
        } else {
            header("location: ../view/campusColectivoView.php?error=emptyField");
        }
    } else {
        header("location: ../view/campusColectivoView.php?error=error");
    }
}

?>
