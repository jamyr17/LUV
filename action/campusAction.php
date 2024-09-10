<?php
header('Content-Type: application/json');

include '../business/campusBusiness.php';
include '../business/universidadCampusColectivoBusiness.php';
include 'functions.php';

if (isset($_POST['update'])) {

    if (isset($_POST['nombre']) && isset($_POST['direccion']) && isset($_POST['latitud']) && isset($_POST['longitud']) && isset($_POST['idCampus']) && isset($_POST['idUniversidad']) && isset($_POST['idRegion']) && isset($_POST['idEspecializacion'])) {

        $idCampus = $_POST['idCampus'];
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
                $campusBusiness = new CampusBusiness();

                $resultExist = $campusBusiness->nameExist($nombre, $idCampus);


                if ($resultExist == 1) {
                    guardarFormData();
                    header("Location: ../view/campusView.php?error=exist");
                    exit(); 
                } else {
                    $campus = new Campus($idCampus, $idUniversidad, $idRegion, $nombre, $direccion, $latitud, $longitud, 1, $idEspecializacion, $colectivos);
                    $result = $campusBusiness->updateTbCampus($campus);

                    if ($result == 1) {
                        header("Location: ../view/campusView.php?success=updated");
                        exit(); 
                    } else {
                        guardarFormData();
                        header("Location: ../view/campusView.php?error=dbError");
                        exit(); 
                    }
                }
                
            } else {
                guardarFormData();
                header("Location: ../view/campusView.php?error=numberFormat");
                exit(); 
            }
        } else {
            guardarFormData();
            header("Location: ../view/campusView.php?error=emptyField");
            exit();
        }
    } else {
        guardarFormData();
        header("Location: ../view/campusView.php?error=error");
        exit(); 
    }
}else if (isset($_POST['delete'])) {

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
        $idRegion = $_POST['idRegion'];
        $idEspecializacion = $_POST['idEspecializacion'];
        $nombre = $_POST['nombre'];
        $direccion = $_POST['direccion'];
        $latitud = $_POST['latitud'];
        $longitud = $_POST['longitud'];
        $colectivos = isset($_POST['colectivos']) ? $_POST['colectivos'] : []; 

        if (strlen($nombre) > 0 && strlen($direccion > 0)) {

            if (!is_numeric($nombre)) {

                $campusBusiness = new CampusBusiness();

                $resultExist = $campusBusiness->exist($nombre);

                if ($resultExist == 1) {
                    guardarFormData();
                    header("location: ../view/campusView.php?error=exist");
                    exit();
                } 
                
                $nombresExistentes = $campusBusiness->getAllTbCampusNombres();
                if (esNombreSimilar($nombre, $nombresExistentes)) {
                    guardarFormData();
                    header("location: ../view/campusView.php?error=alike");
                    exit();
                } else {
                    $campus = new Campus(0, $idUniversidad, $idRegion, $nombre, $direccion, $latitud, $longitud, 1, $idEspecializacion, $colectivos);
                    $result = $campusBusiness->insertTbCampus($campus);
    
                    if ($result == 1) {
                        header("location: ../view/campusView.php?success=inserted");
                        exit();
                    } else {
                        guardarFormData();
                        header("location: ../view/campusView.php?error=dbError");
                        exit();
                    }
                }
            } else {
                guardarFormData();
                header("location: ../view/campusView.php?error=numberFormat");
                exit();
            }
        } else {
            guardarFormData();
            header("location: ../view/campusView.php?error=emptyField");
        }
    } else {
        guardarFormData();
        header("location: ../view/campusView.php?error=error");
    }
} else if(isset($_GET['idU'])) { 
    $idU = $_GET['idU'];
    $campusBusiness = new CampusBusiness();
    $campusBusiness->getAllTbCampusByUniversidad($idU);
    
}else if (isset($_POST['restore'])) {

    if (isset($_POST['idCampus'])) {
        $idCampus = $_POST['idCampus'];
        $campusBusiness = new CampusBusiness();
        $result = $campusBusiness->restoreTbCampus($idCampus);

        if ($result == 1) {
            header("location: ../view/campusView.php?success=restored");
        } else {
            header("location: ../view/campusView.php?error=dbError");
        }
    } else {
        header("location: ../view/campusView.php?error=error");
    }
}

?>