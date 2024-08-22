<?php

include '../bussiness/orientacionSexualBussiness.php';

if (isset($_POST['update'])) {

    if (isset($_POST['nombre']) && isset($_POST['descripcion'])) {
            
        $idOrientacionSexual = $_POST['idOrientacionSexual'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        
        if (strlen($nombre) > 0 && strlen($descripcion) > 0) {
            if (!is_numeric($nombre)) {
                // verificar que no exista un registro con el mismo valor que esta siendo ingresado
                $orientacionSexualBusiness = new OrientacionSexualBusiness();

                $resultExist = $orientacionSexualBusiness->exist($nombre);

                if ($resultExist == 1) {
                    header("location: ../view/orientacionSexualView.php?error=exist");
                } else {
                    $orientacionSexual = new OrientacionSexual($idOrientacionSexual, $nombre, $descripcion, 1);

                    $result = $orientacionSexualBusiness->updateTbOrientacionSexual($orientacionSexual);

                    if ($result == 1) {
                        header("location: ../view/orientacionSexualView.php?success=updated");
                    } else {
                        header("location: ../view/orientacionSexualView.php?error=dbError");
                    }

                }
                
            } else {
                header("location: ../view/orientacionSexualView.php?error=numberFormat");
            }
        } else {
            header("location: ../view/orientacionSexualView.php?error=emptyField");
        }
    } else {
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

        if (strlen($nombre) > 0 && strlen($descripcion) > 0) {
            if (!is_numeric($nombre)) {
                // verificar que no exista un registro con el mismo valor que esta siendo ingresado
                $orientacionSexualBusiness = new OrientacionSexualBusiness();

                $resultExist = $orientacionSexualBusiness->exist($nombre);

                if ($resultExist == 1) {
                    header("location: ../view/orientacionSexualView.php?error=exist");
                } else {
                    $orientacionSexual = new OrientacionSexual(0, $nombre, $descripcion, 1);
    
                    $result = $orientacionSexualBusiness->insertTbOrientacionSexual($orientacionSexual);
    
                    if ($result == 1) {
                        header("location: ../view/orientacionSexualView.php?success=inserted");
                    } else {
                        header("location: ../view/orientacionSexualView.php?error=dbError");
                    }

                }
                
            } else {
                header("location: ../view/orientacionSexualView.php?error=numberFormat");
            }
        } else {
            header("location: ../view/orientacionSexualView.php?error=emptyField");
        }
    } else {
        header("location: ../view/orientacionSexualView.php?error=error");
    }
}
?>
