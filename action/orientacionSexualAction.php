<?php

include '../business/orientacionSexualBusiness.php';
include 'functions.php';

$maxLength = 255;

if (isset($_POST['update'])) {

    if (isset($_POST['nombre']) && isset($_POST['descripcion'])) {
            
        $idOrientacionSexual = $_POST['idOrientacionSexual'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];

        if (strlen($nombre) > $maxLength) {
            guardarFormData();
            header("Location: ../view/orientacionSexualView.php?error=nameTooLong");
            exit();
        }

        if (strlen($descripcion) > $maxLength) {
            guardarFormData();
            header("Location: ../view/orientacionSexualView.php?error=descriptionTooLong");
            exit();
        }
                
        if (strlen($nombre) > 0 && strlen($descripcion) > 0) {
            if (!is_numeric($nombre)) {
                // verificar que no exista un registro con el mismo valor que esta siendo ingresado
                $orientacionSexualBusiness = new OrientacionSexualBusiness();

                $resultExist = $orientacionSexualBusiness->nameExist($nombre, $idOrientacionSexual);

                if ($resultExist == 1) {
                    guardarFormData();
                    header("location: ../view/orientacionSexualView.php?error=exist");
                } else {
                    $orientacionSexual = new OrientacionSexual($idOrientacionSexual, $nombre, $descripcion, 1);

                    $result = $orientacionSexualBusiness->updateTbOrientacionSexual($orientacionSexual);

                    if ($result == 1) {
                        header("location: ../view/orientacionSexualView.php?success=updated");
                    } else {
                        guardarFormData();
                        header("location: ../view/orientacionSexualView.php?error=dbError");
                    }

                }
                
            } else {
                guardarFormData();
                header("location: ../view/orientacionSexualView.php?error=numberFormat");
            }
        } else {
            guardarFormData();
            header("location: ../view/orientacionSexualView.php?error=emptyField");
        }
    } else {
        guardarFormData();
        header("location: ../view/orientacionSexualView.php?error=error");
    }
} else if (isset($_POST['delete'])) {

    if (isset($_POST['idOrientacionSexual'])) {

        $idOrientacionSexual = $_POST['idOrientacionSexual'];

        $orientacionSexualBusiness = new OrientacionSexualBusiness();
        $result = $orientacionSexualBusiness->deleteTbOrientacionSexual($idOrientacionSexual);

        if ($result == 1) {
            header("location: ../view/orientacionSexualView.php?success=deleted");
        } else {
            header("location: ../view/orientacionSexualView.php?error=dbError");
        }
    } else {
        header("location: ../view/orientacionSexualView.php?error=error");
    }
} else if (isset($_POST['create'])) {

    if (isset($_POST['nombre']) && isset($_POST['descripcion'])) {
            
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];

        if (strlen($nombre) > $maxLength) {
            guardarFormData();
            header("Location: ../view/orientacionSexualView.php?error=nameTooLong");
            exit();
        }

        if (strlen($descripcion) > $maxLength) {
            guardarFormData();
            header("Location: ../view/orientacionSexualView.php?error=descriptionTooLong");
            exit();
        }

        if (strlen($nombre) > 0 && strlen($descripcion) > 0) {
            if (!is_numeric($nombre)) {
                // verificar que no exista un registro con el mismo valor que esta siendo ingresado
                $orientacionSexualBusiness = new OrientacionSexualBusiness();

                $resultExist = $orientacionSexualBusiness->exist($nombre);

                if ($resultExist == 1) {
                    guardarFormData();
                    header("location: ../view/orientacionSexualView.php?error=exist");
                    exit();
                } 
                
                $nombresExistentes = $orientacionSexualBusiness->getAllTbOrientacionSexualNombres();
                if(esNombreSimilar($nombre, $nombresExistentes)) {
                    guardarFormData();
                    header("location: ../view/orientacionSexualView.php?error=alike");
                    exit();
                } else {
                    $orientacionSexual = new OrientacionSexual(0, $nombre, $descripcion, 1);
    
                    $result = $orientacionSexualBusiness->insertTbOrientacionSexual($orientacionSexual);
    
                    if ($result == 1) {
                        header("location: ../view/orientacionSexualView.php?success=inserted");
                        exit();
                    } else {
                        guardarFormData();
                        header("location: ../view/orientacionSexualView.php?error=dbError");
                        exit();
                    }

                }
                
            } else {
                guardarFormData();
                header("location: ../view/orientacionSexualView.php?error=numberFormat");
            }
        } else {
            guardarFormData();
            header("location: ../view/orientacionSexualView.php?error=emptyField");
        }
    } else {
        guardarFormData();
        header("location: ../view/orientacionSexualView.php?error=error");
    }
}else if (isset($_POST['restore'])) {

    if (isset($_POST['idOrientacionSexual'])) {
        $idOrientacionSexual = $_POST['idOrientacionSexual'];
        $OrientacionSexualBusiness = new OrientacionSexualBusiness();
        $result = $OrientacionSexualBusiness->restoreTbCampusOrientacionSexual($idOrientacionSexual);

        if ($result == 1) {
            header("location: ../view/orientacionSexualView.php?success=restored");
        } else {
            header("location: ../view/orientacionSexualView.php?error=dbError");
        }
    } else {
        header("location: ../view/orientacionSexualView.php?error=error");
    }
}
