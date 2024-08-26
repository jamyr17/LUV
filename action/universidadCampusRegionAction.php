<?php

include '../bussiness/universidadCampusRegionBussiness.php';

if (isset($_POST['update'])) {

    if (isset($_POST['idUniversidadCampusRegion']) && isset($_POST['nombre']) && isset($_POST['descripcion'])) {

        $idUniversidadCampusRegion = $_POST['idUniversidadCampusRegion'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];

        if (strlen($nombre) > 0 && strlen($descripcion) > 0) {
            if (!is_numeric($nombre) && !is_numeric($descripcion)) {
                $campusRegionBusiness = new UniversidadCampusRegionBussiness();

                $resultExist = $campusRegionBusiness->nameExist($nombre, $idUniversidadCampusRegion);

                if ($resultExist == 1) {
                    header("location: ../view/universidadCampusRegionView.php?error=exist");
                } else {
                    $campusRegion = new UniversidadCampusRegion($idUniversidadCampusRegion, $nombre, $descripcion, 1);

                    $result = $campusRegionBusiness->updateTbUniversidadCampusRegion($campusRegion);

                    if ($result == 1) {
                        header("location: ../view/universidadCampusRegionView.php?success=updated");
                    } else {
                        header("location: ../view/universidadCampusRegionView.php?error=dbError");
                    }
                }
            } else {
                header("location: ../view/universidadCampusRegionView.php?error=numberFormat");
            }
        } else {
            header("location: ../view/universidadCampusRegionView.php?error=emptyField");
        }
    } else {
        header("location: ../view/universidadCampusRegionView.php?error=error");
    }
} else if (isset($_POST['delete'])) {

    if (isset($_POST['idUniversidadCampusRegion'])) {

        $idUniversidadCampusRegion = $_POST['idUniversidadCampusRegion'];

        $campusRegionBusiness = new UniversidadCampusRegionBussiness();
        $result = $campusRegionBusiness->deleteTbUniversidadCampusRegion($idUniversidadCampusRegion);

        if ($result == 1) {
            header("location: ../view/universidadCampusRegionView.php?success=deleted");
        } else {
            header("location: ../view/universidadCampusRegionView.php?error=dbError");
        }
    } else {
        header("location: ../view/universidadCampusRegionView.php?error=error");
    }
} else if (isset($_POST['create'])) {

    if (isset($_POST['nombre']) && isset($_POST['descripcion'])) {

        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];

        if (strlen($nombre) > 0 && strlen($descripcion) > 0) {
            if (!is_numeric($nombre) && !is_numeric($descripcion)) {
                $campusRegionBusiness = new UniversidadCampusRegionBussiness();

                $resultExist = $campusRegionBusiness->exist($nombre);

                if ($resultExist == 1) {
                    header("location: ../view/universidadCampusRegionView.php?error=exist");
                } else {
                    $campusRegion = new UniversidadCampusRegion(0, $nombre, $descripcion, 1);

                    $result = $campusRegionBusiness->insertTbUniversidadCampusRegion($campusRegion);

                    if ($result == 1) {
                        header("location: ../view/universidadCampusRegionView.php?success=inserted");
                    } else {
                        header("location: ../view/universidadCampusRegionView.php?error=dbError");
                    }
                }
            } else {
                header("location: ../view/universidadCampusRegionView.php?error=numberFormat");
            }
        } else {
            header("location: ../view/universidadCampusRegionView.php?error=emptyField");
        }
    } else {
        header("location: ../view/universidadCampusRegionView.php?error=error");
    }
}
?>