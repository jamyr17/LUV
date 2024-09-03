<?php

include '../business/universidadBusiness.php';
include 'functions.php';

if (isset($_POST['update'])) {

    if (isset($_POST['nombre'])) {
            
        $idUniversidad = $_POST['idUniversidad'];
        $nombre = $_POST['nombre'];
        
        // Verifica que el nombre no exceda los 150 caracteres
        if (strlen($nombre) > 150) {
            guardarFormData();
            header("location: ../view/universidadView.php?error=longText");
            exit();
        }

        if (strlen($nombre) > 0) {
            if (!is_numeric($nombre)) {
                // verificar que no exista un registro con el mismo valor que esta siendo ingresado
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
        echo "$idUniversidad";

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

        // Verifica que el nombre no exceda los 150 caracteres
        if (strlen($nombre) > 150) {
            guardarFormData();
            header("location: ../view/universidadView.php?error=longText");
            exit();
        }

        if (strlen($nombre) > 0) {
            if (!is_numeric($nombre)) {
                // verificar que no exista un registro con el mismo valor que esta siendo ingresado
                $universidadBusiness = new UniversidadBusiness();

                $resultExist = $universidadBusiness->exist($nombre);

                if ($resultExist == 1) {
                    guardarFormData();
                    header("location: ../view/universidadView.php?error=exist");
                } else {
                    $universidad = new Universidad(0, $nombre, 1);
    
                    $result = $universidadBusiness->insertTbUniversidad($universidad);
    
                    if ($result == 1) {
                        header("location: ../view/universidadView.php?success=inserted");
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
}
?>
