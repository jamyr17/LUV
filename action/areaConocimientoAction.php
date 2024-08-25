<?php

include '../bussiness/areaConocimientoBussiness.php';

if (isset($_POST['update'])) {

    if (isset($_POST['nombre']) && isset($_POST['descripcion'])) {
            
        $idAreaConocimiento = $_POST['idAreaConocimiento'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        
        if (strlen($nombre) > 0 && strlen($descripcion) > 0) {
            if (!is_numeric($nombre) && !is_numeric($descripcion)) {

                $areaConocimientoBusiness = new AreaConocimientoBussiness();

                $resultExist = $areaConocimientoBusiness->nameExist($nombre, $idAreaConocimiento);

                if ($resultExist) {
                    header("location: ../view/areaConocimientoView.php?error=exist");
                } else {
                    $areaConocimiento = new AreaConocimiento($idAreaConocimiento, $nombre, $descripcion, 1);

                    $result = $areaConocimientoBusiness->updateTbAreaConocimiento($areaConocimiento);

                    if ($result == 1) {
                        header("location: ../view/areaConocimientoView.php?success=updated");
                    } else {
                        header("location: ../view/areaConocimientoView.php?error=dbError");
                    }

                }
                
            } else {
                header("location: ../view/areaConocimientoView.php?error=numberFormat");
            }
        } else {
            header("location: ../view/areaConocimientoView.php?error=emptyField");
        }
    } else {
        header("location: ../view/areaConocimientoView.php?error=error");
    }
}else if (isset($_POST['delete'])) {

    if (isset($_POST['idAreaConocimiento'])) {

        $idAreaConocimiento = $_POST['idAreaConocimiento'];
        echo "$idAreaConocimiento";

        $areaConocimientoBussiness = new AreaConocimientoBussiness();
        $result = $areaConocimientoBussiness->deleteTbAreaConocimiento($idAreaConocimiento);

        if ($result == 1) {
            header("location: ../view/areaConocimientoView.php?success=deleted");
        } else {
            header("location: ../view/areaConocimientoView.php?error=dbError");
        }
    } else {
        header("location: ../view/areaConocimientoView.php?error=error");
    }
} else if (isset($_POST['create'])) {

    if (isset($_POST['nombre']) && isset($_POST['descripcion'])) {
        
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];

        if (strlen($nombre) > 0 && strlen($descripcion) > 0) {
            if (!is_numeric($nombre) && !is_numeric($descripcion)) {
                
                $areaConocimientoBussiness = new AreaConocimientoBussiness();

                $resultExist = $areaConocimientoBussiness->exist($nombre);

                if ($resultExist == 1) {
                    header("location: ../view/areaConocimientoView.php?error=exist");
                } else {
                    $areaConocimiento = new AreaConocimiento(0, $nombre, $descripcion, 1);
    
                    $result = $areaConocimientoBussiness->insertTbAreaConocimiento($areaConocimiento);
    
                    if ($result == 1) {
                        header("location: ../view/areaConocimientoView.php?success=inserted");
                    } else {
                        header("location: ../view/areaConocimientoView.php?error=dbError");
                    }

                }
                
            } else {
                header("location: ../view/areaConocimientoView.php?error=numberFormat");
            }
        } else {
            header("location: ../view/areaConocimientoView.php?error=emptyField");
        }
    } else {
        header("location: ../view/areaConocimientoView.php?error=error");
    }
}
?>