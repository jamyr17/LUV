<?php

include '../bussiness/criterioBusiness.php';

if (isset($_POST['update'])) {

    if (isset($_POST['nombre'])) {

        $idCriterio = $_POST['idCriterio'];
        $nombre = $_POST['nombre'];

        if (strlen($nombre) > 0) {
            if (!is_numeric($nombre)) {
                $criterioBusiness = new CriterioBusiness();

                $resultExist = $criterioBusiness->exist($nombre);

                if ($resultExist == 1) {
                    header("location: ../view/criterioView.php?error=exist");
                } else {
                    $criterio = new Criterio($idCriterio, $nombre, 1);

                    $result = $criterioBusiness->updateTbCriterio($criterio);

                    if ($result == 1) {
                        header("location: ../view/criterioView.php?success=updated");
                    } else {
                        header("location: ../view/criterioView.php?error=dbError");
                    }
                }
            } else {
                header("location: ../view/criterioView.php?error=numberFormat");
            }
        } else {
            header("location: ../view/criterioView.php?error=emptyField");
        }
    } else {
        header("location: ../view/criterioView.php?error=error");
    }
} else if (isset($_POST['delete'])) {

    if (isset($_POST['idCriterio'])) {

        $idCriterio = $_POST['idCriterio'];

        $criterioBusiness = new CriterioBusiness();
        $result = $criterioBusiness->deleteTbCriterio($idCriterio);

        if ($result == 1) {
            header("location: ../view/criterioView.php?success=deleted");
        } else {
            header("location: ../view/criterioView.php?error=dbError");
        }
    } else {
        header("location: ../view/criterioView.php?error=error");
    }
} else if (isset($_POST['create'])) {

    if (isset($_POST['nombre'])) {

        $nombre = $_POST['nombre'];

        if (strlen($nombre) > 0) {
            if (!is_numeric($nombre)) {
                $criterioBusiness = new CriterioBusiness();

                $resultExist = $criterioBusiness->exist($nombre);

                if ($resultExist == 1) {
                    header("location: ../view/criterioView.php?error=exist");
                } else {
                    $criterio = new Criterio(0, $nombre, 1);

                    $result = $criterioBusiness->insertTbCriterio($criterio);

                    if ($result == 1) {
                        header("location: ../view/criterioView.php?success=inserted");
                    } else {
                        header("location: ../view/criterioView.php?error=dbError");
                    }
                }
            } else {
                header("location: ../view/criterioView.php?error=numberFormat");
            }
        } else {
            header("location: ../view/criterioView.php?error=emptyField");
        }
    } else {
        header("location: ../view/criterioView.php?error=error");
    }
}
?>
