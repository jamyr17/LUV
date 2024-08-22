<?php

include '../bussiness/campusRegionBusiness.php';

if (isset($_POST['update'])) {

    if (isset($_POST['idCampusRegion']) && isset($_POST['nombre']) && isset($_POST['descripcion'])) {

        $idCampusRegion = $_POST['idCampusRegion'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];

        if (strlen($nombre) > 0 && strlen($descripcion) > 0) {
            if (!is_numeric($nombre) && !is_numeric($descripcion)) {
                $campusRegionBusiness = new CampusRegionBusiness();

                $resultExist = $campusRegionBusiness->exist($nombre);

                if ($resultExist == 1) {
                    header("location: ../view/campusRegionView.php?error=exist");
                } else {
                    $campusRegion = new CampusRegion($idCampusRegion, $nombre, $descripcion, 1);

                    $result = $campusRegionBusiness->updateTbCampusRegion($campusRegion);

                    if ($result == 1) {
                        header("location: ../view/campusRegionView.php?success=updated");
                    } else {
                        header("location: ../view/campusRegionView.php?error=dbError");
                    }
                }
            } else {
                header("location: ../view/campusRegionView.php?error=numberFormat");
            }
        } else {
            header("location: ../view/campusRegionView.php?error=emptyField");
        }
    } else {
        header("location: ../view/campusRegionView.php?error=error");
    }
} else if (isset($_POST['delete'])) {

    if (isset($_POST['idCampusRegion'])) {

        $idCampusRegion = $_POST['idCampusRegion'];

        $campusRegionBusiness = new CampusRegionBusiness();
        $result = $campusRegionBusiness->deleteTbCampusRegion($idCampusRegion);

        if ($result == 1) {
            header("location: ../view/campusRegionView.php?success=deleted");
        } else {
            header("location: ../view/campusRegionView.php?error=dbError");
        }
    } else {
        header("location: ../view/campusRegionView.php?error=error");
    }
} else if (isset($_POST['create'])) {

    if (isset($_POST['nombre']) && isset($_POST['descripcion'])) {

        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];

        if (strlen($nombre) > 0 && strlen($descripcion) > 0) {
            if (!is_numeric($nombre) && !is_numeric($descripcion)) {
                $campusRegionBusiness = new CampusRegionBusiness();

                $resultExist = $campusRegionBusiness->exist($nombre);

                if ($resultExist == 1) {
                    header("location: ../view/campusRegionView.php?error=exist");
                } else {
                    $campusRegion = new CampusRegion(0, $nombre, $descripcion, 1);

                    $result = $campusRegionBusiness->insertTbCampusRegion($campusRegion);

                    if ($result == 1) {
                        header("location: ../view/campusRegionView.php?success=inserted");
                    } else {
                        header("location: ../view/campusRegionView.php?error=dbError");
                    }
                }
            } else {
                header("location: ../view/campusRegionView.php?error=numberFormat");
            }
        } else {
            header("location: ../view/campusRegionView.php?error=emptyField");
        }
    } else {
        header("location: ../view/campusRegionView.php?error=error");
    }
}
?>
