<?php

include './generoBusiness.php';

if (isset($_POST['update'])) {

    if (isset($_POST['nombre']) && isset($_POST['descripcion'])) {

        $idGenero = $_POST['idGenero'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];

        if (strlen($nombre) > 0 && strlen($descripcion) > 0) {
            if (!is_numeric($nombre) && !is_numeric($descripcion)) {
                $generoBusiness = new GeneroBusiness();

                $resultExist = $generoBusiness->exist($nombre);

                if ($resultExist == 1) {
                    header("location: ../view/generoView.php?error=exist");
                } else {
                    $genero = new Genero($idGenero, $nombre, $descripcion, 1);

                    $result = $generoBusiness->updateTbGenero($genero);

                    if ($result == 1) {
                        header("location: ../view/generoView.php?success=updated");
                    } else {
                        header("location: ../view/generoView.php?error=dbError");
                    }
                }
            } else {
                header("location: ../view/generoView.php?error=numberFormat");
            }
        } else {
            header("location: ../view/generoView.php?error=emptyField");
        }
    } else {
        header("location: ../view/generoView.php?error=error");
    }
} else if (isset($_POST['delete'])) {

    if (isset($_POST['idGenero'])) {

        $idGenero = $_POST['idGenero'];

        $generoBusiness = new GeneroBusiness();
        $result = $generoBusiness->deleteTbGenero($idGenero);

        if ($result == 1) {
            header("location: ../view/generoView.php?success=deleted");
        } else {
            header("location: ../view/generoView.php?error=dbError");
        }
    } else {
        header("location: ../view/generoView.php?error=error");
    }
} else if (isset($_POST['create'])) {

    if (isset($_POST['nombre']) && isset($_POST['descripcion'])) {

        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];

        if (strlen($nombre) > 0 && strlen($descripcion) > 0) {
            if (!is_numeric($nombre) && !is_numeric($descripcion)) {
                $generoBusiness = new GeneroBusiness();

                $resultExist = $generoBusiness->exist($nombre);

                if ($resultExist == 1) {
                    header("location: ../view/generoView.php?error=exist");
                } else {
                    $genero = new Genero(0, $nombre, $descripcion, 1);

                    $result = $generoBusiness->insertTbGenero($genero);

                    if ($result == 1) {
                        header("location: ../view/generoView.php?success=inserted");
                    } else {
                        header("location: ../view/generoView.php?error=dbError");
                    }
                }
            } else {
                header("location: ../view/generoView.php?error=numberFormat");
            }
        } else {
            header("location: ../view/generoView.php?error=emptyField");
        }
    } else {
        header("location: ../view/generoView.php?error=error");
    }
}
?>
