<?php

include '../business/universidadCampusColectivoBusiness.php';
include 'functions.php';

if (isset($_POST['update'])) {

    if (isset($_POST['nombre']) && isset($_POST['descripcion'])) {
            
        $idUniversidadCampusColectivo = $_POST['idUniversidadCampusColectivo'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        
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
} else if (isset($_POST['delete'])) {

    if (isset($_POST['idUniversidadCampusColectivo'])) {

        $idUniversidadCampusColectivo = $_POST['idUniversidadCampusColectivo'];

        $universidadCampusColectivoBusiness = new universidadCampusColectivoBusiness();
        $result = $universidadCampusColectivoBusiness->deleteTbUniversidadCampusColectivo($idUniversidadCampusColectivo);

        if ($result == 1) {
            header("location: ../view/universidadCampusColectivoView.php?success=deleted");
        } else {
            header("location: ../view/universidadCampusColectivoView.php?error=dbError");
        }
    } else {
        header("location: ../view/universidadCampusColectivoView.php?error=error");
    }
} else if (isset($_POST['create'])) {

    if (isset($_POST['nombre']) && isset($_POST['descripcion'])) {
            
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];

        if (strlen($nombre) > 0 && strlen($descripcion) > 0) {
            if (!is_numeric($nombre)) {
                // Verificar que no exista un registro con el mismo valor que está siendo ingresado
                $universidadCampusColectivoBusiness = new universidadCampusColectivoBusiness();

                $resultExist = $universidadCampusColectivoBusiness->exist($nombre);

                if ($resultExist == 1) {
                    guardarFormData();
                    header("location: ../view/universidadCampusColectivoView.php?error=exist");
                } else {
                    $universidadCampusColectivo = new universidadCampusColectivo(0, $nombre, $descripcion, 1);
    
                    $result = $universidadCampusColectivoBusiness->insertTbUniversidadCampusColectivo($universidadCampusColectivo);
    
                    if ($result == 1) {
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
}
