<?php

include '../business/actividadBusiness.php';
include '../action/functions.php';

if (isset($_POST['update'])) {

    if (isset($_POST['idActividad']) && isset($_POST['titulo']) && isset($_POST['descripcion']) && isset($_POST['descripcion']) 
         && isset( $_POST['fecha']) && isset($_POST['duracion']) && isset($_POST['direccion'])
    ) {
        
        $idActividad = $_POST['idActividad'];
        $titulo = $_POST['titulo'];
        $descripcion = $_POST['descripcion'];
        $fecha = $_POST['fecha'];
        $duracion = $_POST['duracion'];
        $direccion = $_POST['direccion'];
        $anonimo = isset($_POST['anonimo']) ? true : false;
        $colectivos = $_POST['colectivos'];

        if (strlen($titulo) > 63) {
            guardarFormData();
            header("Location: ../view/actividadView.php?error=nameTooLong");
            exit();
        }

        if (strlen($descripcion) > 255) {
            guardarFormData();
            header("Location: ../view/actividadView.php?error=descriptionTooLong");
            exit();
        }

        if (strlen($direccion) > 255) {
            guardarFormData();
            header("Location: ../view/actividadView.php?error=directionTooLong");
            exit();
        }

        if (strlen($titulo) > 0 && strlen($descripcion) > 0 && strlen($direccion) > 0) {
            if (!is_numeric($titulo) && !is_numeric($descripcion) && !is_numeric($direccion)) {
                $actividadBusiness = new ActividadBusiness();

                $resultExist = $actividadBusiness->nameExist($titulo, $idActividad);

                if ($resultExist == 1) {
                    guardarFormData();
                    header("location: ../view/actividadView.php?error=exist");
                } else {
                    $actividad = new Actividad($idActividad, $titulo, $descripcion, $fecha, $duracion, $direccion, 0, 0, true, $anonimo, $colectivos);

                    $result = $actividadBusiness->updateTbActividad($actividad);

                    if ($result == 1) {
                        header("location: ../view/actividadView.php?success=updated");
                    } else {
                        guardarFormData();
                        header("location: ../view/actividadView.php?error=dbError");
                    }
                }
            } else {
                guardarFormData();
                header("location: ../view/actividadView.php?error=numberFormat");
            }
        } else {
            guardarFormData();
            header("location: ../view/actividadView.php?error=emptyField");
        }
    } else {
        guardarFormData();
        header("location: ../view/actividadView.php?error=error");
    }
} else if (isset($_POST['delete'])) {

    if (isset($_POST['idActividad'])) {

        $idActividad = $_POST['idActividad'];

        $actividadBusiness = new ActividadBusiness();
        $result = $actividadBusiness->deleteTbActividad($idActividad);

        if ($result == 1) {
            header("location: ../view/actividadView.php?success=deleted");
        } else {
            header("location: ../view/actividadView.php?error=dbError");
        }
    } else {
        header("location: ../view/actividadView.php?error=error");
    }
} else if (isset($_POST['create'])) {

    if (isset($_POST['titulo']) && isset($_POST['descripcion']) && isset($_POST['descripcion']) 
         && isset( $_POST['fecha']) && isset($_POST['duracion']) && isset($_POST['direccion'])
    ) {

        $titulo = $_POST['titulo'];
        $descripcion = $_POST['descripcion'];
        $fecha = $_POST['fecha'];
        $duracion = $_POST['duracion'];
        $direccion = $_POST['direccion'];
        $anonimo = isset($_POST['anonimo']) ? true : false;
        $colectivos = $_POST['colectivos'];

        if (strlen($titulo) > 63) {
            guardarFormData();
            header("Location: ../view/actividadView.php?error=titleTooLong");
            exit();
        }

        if (strlen($descripcion) > 255) {
            guardarFormData();
            header("Location: ../view/actividadView.php?error=descriptionTooLong");
            exit();
        }

        if (strlen($direccion) > 255) {
            guardarFormData();
            header("Location: ../view/actividadView.php?error=directionTooLong");
            exit();
        }

        if (strlen($titulo) > 0 && strlen($descripcion) > 0 && strlen($direccion) > 0) {
            if (!is_numeric($titulo) && !is_numeric($descripcion) && !is_numeric($direccion)) {
                $actividadBusiness = new ActividadBusiness();

                $resultExist = $actividadBusiness->exist($titulo);

                if ($resultExist == 1) {
                    guardarFormData();
                    header("location: ../view/actividadView.php?error=exist");
                    exit();
                } 
                
                $titulosExistentes = $actividadBusiness->getAllTbActividadTitulos();

                if (esNombreSimilar($titulo, $titulosExistentes)) { 
                    guardarFormData();
                    header("Location: ../view/actividadView.php?error=alike");
                    exit();
                } else {
                    $actividad = new Actividad(0, $titulo, $descripcion, $fecha, $duracion, $direccion, 0, 0, true, $anonimo, $colectivos);

                    $result = $actividadBusiness->insertTbActividad($actividad);

                    if ($result == 1) {
                        header("location: ../view/actividadView.php?success=inserted");
                        exit();
                    } else {
                        guardarFormData();
                        header("location: ../view/actividadView.php?error=dbError");
                        exit();
                    }
                }
            } else {
                guardarFormData();
                header("location: ../view/actividadView.php?error=numberFormat");
                exit();
            }
        } else {
            guardarFormData();
            header("location: ../view/actividadView.php?error=emptyField");
        }
    } else {
        guardarFormData();
        header("location: ../view/actividadView.php?error=error");
    }
}else if (isset($_POST['restore'])) {

    if (isset($_POST['idActividad'])) {
        $idActividad = $_POST['idActividad'];
        $ActividadBusiness = new ActividadBusiness();
        $result = $ActividadBusiness->restoreTbActividad($idActividad);

        if ($result == 1) {
            header("location: ../view/actividadView.php?success=restored");
        } else {
            header("location: ../view/actividadView.php?error=dbError");
        }
    } else {
        header("location: ../view/actividadView.php?error=error");
    }
}
