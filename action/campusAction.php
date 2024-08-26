<?php

include '../bussiness/campusBussiness.php';

if (isset($_POST['update'])) {

    if (isset($_POST['nombre']) && isset($_POST['direccion']) && isset($_POST['longitud']) && isset($_POST['latitud']) && isset($_POST['idRegion']) && isset($_POST['idEspecializacion']) && isset($_POST['idCampus']) && isset($_POST['idUniversidad'])) {
        
        $idCampus = $_POST['idCampus'];
        $idUniversidad = $_POST['idUniversidad'];
        $idRegion = $_POST['idRegion'];
        $idEspecializacion = $_POST['idEspecializacion'];
        $longitud = $_POST['longitud'];
        $latitud = $_POST['latitud'];
        $nombre = $_POST['nombre'];
        $direccion = $_POST['direccion'];

        if (strlen($nombre) > 0 && strlen($direccion) > 0) {
            if (!is_numeric($nombre)) {
                $campusBusiness = new CampusBusiness();

                // Verificar que no exista un registro con el mismo nombre
                $resultExist = $campusBusiness->exist($nombre);

                if ($resultExist == 1) {
                    header("location: ../view/campusView.php?error=exist");
                } else {
                    // Obtener el campus existente para no modificar los colectivos
                    $existingCampus = $campusBusiness->getTbCampusById($idCampus);

                    if ($existingCampus) {
                        $colectivos = $existingCampus->getColectivos(); // Mantener los colectivos existentes

                        $campus = new Campus($idCampus, $idUniversidad, $idRegion, $nombre, $direccion, $latitud, $longitud, $existingCampus->getTbCampusEstado(), $idEspecializacion, $colectivos);

                        $result = $campusBusiness->updateTbCampus($campus);

                        if ($result == 1) {
                            header("location: ../view/campusView.php?success=updated");
                        } else {
                            header("location: ../view/campusView.php?error=dbError");
                        }
                    } else {
                        header("location: ../view/campusView.php?error=notFound");
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

    if (isset($_POST['nombre']) && isset($_POST['direccion']) && isset($_POST['latitud']) && isset($_POST['longitud']) && isset($_POST['idUniversidad']) && isset($_POST['idRegion']) && isset($_POST['idEspecializacion'])) {

        $idUniversidad = $_POST['idUniversidad'];
        $idRegion = $_POST['idRegion'];
        $idEspecializacion = $_POST['idEspecializacion'];
        $nombre = $_POST['nombre'];
        $direccion = $_POST['direccion'];
        $latitud = $_POST['latitud'];
        $longitud = $_POST['longitud'];
        $colectivos = isset($_POST['colectivos']) ? $_POST['colectivos'] : [];

        if (strlen($nombre) > 0 && strlen($direccion) > 0) {
            if (!is_numeric($nombre)) {
                // Verificar que no exista un registro con el mismo nombre
                $campusBusiness = new CampusBusiness();

                $resultExist = $campusBusiness->exist($nombre);

                if ($resultExist == 1) {
                    header("location: ../view/campusView.php?error=exist");
                } else {
                    $campus = new Campus(0, $idUniversidad, $idRegion, $nombre, $direccion, $latitud, $longitud, 1, $idEspecializacion, $colectivos);
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
} else if (isset($_GET['idU'])) { 
    $idU = $_GET['idU'];
    $campusBusiness = new CampusBusiness();
    $campusBusiness->getAllTbCampusByUniversidad($idU);
} 
?>
