<?php

include './campusBussiness.php';

if (isset($_POST['update'])) {

    if (isset($_POST['nombre']) && isset($_POST['direccion']) && isset($_POST['latitud']) && isset($_POST['longitud'])) {
        
        $idCampus = $_POST['idCampus'];
        $idUniversidad = $_POST['idUniversidad'];
        $nombre = $_POST['nombre'];
        $direccion = $_POST['direccion'];
        $latitud = $_POST['latitud'];
        $longitud = $_POST['longitud'];

        if (strlen($nombre) > 0 && strlen($direccion) > 0) {
            if (!is_numeric($nombre)) {
                // verificar que no exista un registro con el mismo valor que esta siendo ingresado
                $campusBusiness = new CampusBusiness();

                $resultExist = $campusBusiness->exist($nombre);

                if ($resultExist == 1) {
                    header("location: ../view/campusView.php?error=exist");
                } else {
                    $campus = new Campus($idCampus, $idUniversidad, $nombre, $direccion, $latitud, $longitud, 1);

                    $result = $campusBusiness->updateTbCampus($campus);

                    if ($result == 1) {
                        header("location: ../view/campusView.php?success=updated");
                    } else {
                        header("location: ../view/campusView.php?error=dbError");
                    }

                }
                
            } else {
                header("location: ../view/campusView.php?error=numberFormat");
            }
        } else {
            header("location: ../view/campusView.php?error=emptyField");
        }
    } else {
        header("location: ../view/campusView.php?error=error");
    }
} else if (isset($_POST['delete'])) {

    if (isset($_POST['idCampus'])) {

        $idCampus = $_POST['idCampus'];
        echo "$idCampus";

        $campusBusiness = new CampusBusiness();
        $result = $campusBusiness->deleteTbCampus($idCampus);

        if ($result == 1) {
            header("location: ../view/campusView.php?success=deleted");
        } else {
            header("location: ../view/campusView.php?error=dbError");
        }
    } else {
        header("location: ../view/campusView.php?error=error");
    }
} else if (isset($_POST['create'])) {

    if (isset($_POST['nombre']) && isset($_POST['direccion']) && isset($_POST['latitud']) && isset($_POST['longitud'])) {

        $idUniversidad = $_POST['idUniversidad'];
        $nombre = $_POST['nombre'];
        $direccion = $_POST['direccion'];
        $latitud = $_POST['latitud'];
        $longitud = $_POST['longitud'];

        if (strlen($nombre) > 0 && strlen($direccion > 0)) {
            if (!is_numeric($nombre)) {
                // verificar que no exista un registro con el mismo valor que esta siendo ingresado
                $campusBusiness = new CampusBusiness();

                $resultExist = $campusBusiness->exist($nombre);

                if ($resultExist == 1) {
                    header("location: ../view/campusView.php?error=exist");
                } else {
                    $campus = new Campus(0, $idUniversidad, $nombre, $direccion, $latitud, $longitud, 1);
    
                    $result = $campusBusiness->insertTbCampus($campus);
    
                    if ($result == 1) {
                        header("location: ../view/campusView.php?success=inserted");
                    } else {
                        header("location: ../view/campusView.php?error=dbError");
                    }

                }
                
            } else {
                header("location: ../view/campusView.php?error=numberFormat");
            }
        } else {
            header("location: ../view/campusView.php?error=emptyField");
        }
    } else {
        header("location: ../view/campusView.php?error=error");
    }
} else if(isset($_GET['idU'])) { 
    $idU = $_GET['idU'];
    $campusBusiness = new CampusBusiness();
    $campusBusiness->getAllTbCampusByUniversidad($idU);
} 