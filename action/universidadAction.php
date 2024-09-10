<?php

include '../business/universidadBusiness.php';
include 'functions.php';

if (isset($_POST['update'])) {

    if (isset($_POST['nombre'])) {

        $idUniversidad = $_POST['idUniversidad'];
        $nombre = $_POST['nombre'];
        
        if (strlen($nombre) > 150) {
            guardarFormData();
            header("location: ../view/universidadView.php?error=longText");
            exit();
        }

        if (strlen($nombre) > 0) {
            if (!is_numeric($nombre)) {
                $universidadBusiness = new UniversidadBusiness();
                $resultExist = $universidadBusiness->exist($nombre);

                if ($resultExist == 1) {
                    guardarFormData();
                    header("location: ../view/universidadView.php?error=exist");
                } else {
                    $universidad = new Universidad($idUniversidad, $nombre, 1);
                    $result = $universidadBusiness->updateTbUniversidad($universidad);

                    if ($result == 1) {
                        header("location: ../view/universidadView.php?success=updated");
                    } else {
                        guardarFormData();
                        header("location: ../view/universidadView.php?error=dbError");
                    }
                }

            } else {
                guardarFormData();
                header("location: ../view/universidadView.php?error=numberFormat");
            }
        } else {
            guardarFormData();
            header("location: ../view/universidadView.php?error=emptyField");
        }
    } else {
        guardarFormData();
        header("location: ../view/universidadView.php?error=error");
    }
} else if (isset($_POST['delete'])) {

    if (isset($_POST['idUniversidad'])) {
        $idUniversidad = $_POST['idUniversidad'];
        $universidadBusiness = new UniversidadBusiness();
        $result = $universidadBusiness->deleteTbUniversidad($idUniversidad);

        if ($result == 1) {
            header("location: ../view/universidadView.php?success=deleted");
        } else {
            header("location: ../view/universidadView.php?error=dbError");
        }
    } else {
        header("location: ../view/universidadView.php?error=error");
    }
} else if (isset($_POST['create'])) {

    if (isset($_POST['nombre'])) {
        $nombre = $_POST['nombre'];

        // Verificar la longitud del nombre
        if (strlen($nombre) > 150) {
            guardarFormData();
            header("Location: ../view/universidadView.php?error=longText");
            exit();
        }

        // Verificar si el nombre no está vacío y no es un número
        if (strlen($nombre) > 0) {
            if (!is_numeric($nombre)) {
                $universidadBusiness = new UniversidadBusiness();
                $resultExist = $universidadBusiness->exist($nombre);

                // Comprobar si el nombre ya existe
                if ($resultExist == 1) {
                    guardarFormData();
                    header("Location: ../view/universidadView.php?error=exist");
                    exit();
                }

                // Comprobar si el nombre es similar a alguno existente
                $nombresExistentes = $universidadBusiness->getAllTbUniversidadNombres();
                if (esNombreSimilar($nombre, $nombresExistentes)) {
                    guardarFormData();
                    header("Location: ../view/universidadView.php?error=alike");
                    exit();
                } else {
                    $universidad = new Universidad(0, $nombre, 1);
                    $result = $universidadBusiness->insertTbUniversidad($universidad);

                    // Verificar el resultado de la inserción
                    if ($result == 1) {
                        header("Location: ../view/universidadView.php?success=inserted");
                        exit();
                    } else {
                        guardarFormData();
                        header("Location: ../view/universidadView.php?error=dbError");
                        exit();
                    }
                }
            } else {
                guardarFormData();
                header("Location: ../view/universidadView.php?error=numberFormat");
                exit();
            }
        } else {
            guardarFormData();
            header("Location: ../view/universidadView.php?error=emptyField");
            exit();
        }
    } else {
        guardarFormData();
        header("Location: ../view/universidadView.php?error=error");
        exit();
    }
} else if (isset($_POST['restore'])) {

    if (isset($_POST['idUniversidad'])) {
        $idUniversidad = $_POST['idUniversidad'];
        $universidadBusiness = new UniversidadBusiness();
        $result = $universidadBusiness->restoreTbUniversidad($idUniversidad);

        if ($result == 1) {
            header("location: ../view/universidadView.php?success=restored");
        } else {
            header("location: ../view/universidadView.php?error=dbError");
        }
    } else {
        header("location: ../view/universidadView.php?error=error");
    }
}
?>
