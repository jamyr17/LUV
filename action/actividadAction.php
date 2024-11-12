<?php

include '../business/actividadBusiness.php';
include '../action/functions.php';

if (isset($_POST['update'])) {

    if (isset($_POST['idActividad']) && isset($_POST['titulo']) && isset($_POST['descripcion'])
         && isset( $_POST['fechaInicioInput']) && isset($_POST['fechaTerminaInput']) && isset($_POST['direccion'])
    ) {
        
        $idActividad = $_POST['idActividad'];
        $titulo = $_POST['titulo'];
        $descripcion = $_POST['descripcion'];
        $fechaInicio = $_POST['fechaInicioInput'];
        $fechaTermina = $_POST['fechaTerminaInput'];
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

        $fechaInicioObj = new DateTime($fechaInicio);
        $fechaTerminaObj = new DateTime($fechaTermina);

        if ($fechaInicioObj >= $fechaTerminaObj) {
            guardarFormData();
            header("Location: ../view/activitiesCalendarView.php?error=invalidDates");
            exit();
        }

        if (strlen($titulo) > 0 && strlen($descripcion) > 0 && strlen($direccion) > 0) {
            if (!is_numeric($titulo) && !is_numeric($descripcion) && !is_numeric($direccion)) {
                if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] != UPLOAD_ERR_NO_FILE) {
                    $directorioImagenes = '../resources/img/actividad/';
                    $nombreArchivoImagen = strtolower(str_replace(' ', '-', $titulo));

                    $resultImagen = procesarImagen('imagen', $directorioImagenes, $nombreArchivoImagen);

                    if (!$resultImagen) {
                        guardarFormData();
                        header("location: ../view/actividadView.php?error=imageUpload");
                        exit();
                    }

                }
                
                $actividadBusiness = new ActividadBusiness();

                $resultExist = $actividadBusiness->nameExist($titulo, $idActividad);

                if ($resultExist == 1) {
                    guardarFormData();
                    header("location: ../view/actividadView.php?error=exist");
                } else {
                    $actividad = new Actividad($idActividad, null, $titulo, $descripcion, $resultImagen, $fechaInicio, $fechaTermina, $direccion, 0, 0, true, $anonimo, $colectivos);

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
}else if (isset($_POST['userUpdate'])) {

    if (isset($_POST['idActividad']) && isset($_POST['titulo']) && isset($_POST['descripcion'])
         && isset( $_POST['fechaInicioInput']) && isset($_POST['fechaTerminaInput']) && isset($_POST['direccion'])
    ) {
        
        $idActividad = $_POST['idActividad'];
        $titulo = $_POST['titulo'];
        $descripcion = $_POST['descripcion'];
        $fechaInicio = $_POST['fechaInicioInput'];
        $fechaTermina = $_POST['fechaTerminaInput'];
        $direccion = $_POST['direccion'];
        $anonimo = isset($_POST['anonimo']) ? true : false;
        $colectivos = $_POST['colectivos'];

        if (strlen($titulo) > 63) {
            guardarFormData();
            header("Location: ../view/activitiesCalendarView.php?error=nameTooLong");
            exit();
        }

        if (strlen($descripcion) > 255) {
            guardarFormData();
            header("Location: ../view/activitiesCalendarView.php?error=descriptionTooLong");
            exit();
        }

        if (strlen($direccion) > 255) {
            guardarFormData();
            header("Location: ../view/activitiesCalendarView.php?error=directionTooLong");
            exit();
        }

        $fechaInicioObj = new DateTime($fechaInicio);
        $fechaTerminaObj = new DateTime($fechaTermina);

        if ($fechaInicioObj >= $fechaTerminaObj) {
            guardarFormData();
            header("Location: ../view/activitiesCalendarView.php?error=invalidDates");
            exit();
        }

        if (strlen($titulo) > 0 && strlen($descripcion) > 0 && strlen($direccion) > 0) {
            if (!is_numeric($titulo) && !is_numeric($descripcion) && !is_numeric($direccion)) {
                if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] != UPLOAD_ERR_NO_FILE) {
                    echo 'entró';
                    $directorioImagenes = '../resources/img/actividad/';
                    $nombreArchivoImagen = strtolower(str_replace(' ', '-', $titulo));

                    $resultImagen = procesarImagen('imagen', $directorioImagenes, $nombreArchivoImagen);

                    if (!$resultImagen) {
                        guardarFormData();
                        header("location: ../view/activitiesCalendarView.php?error=imageUpload");
                        exit();
                    }

                }

                $actividadBusiness = new ActividadBusiness();

                    $actividad = new Actividad($idActividad, null, $titulo, $descripcion, $resultImagen, $fechaInicio, $fechaTermina, $direccion, 0, 0, true, $anonimo, $colectivos);

                    $result = $actividadBusiness->updateTbActividad($actividad);

                    if ($result == 1) {
                        header("location: ../view/activitiesCalendarView.php?success=updated");
                    } else {
                        guardarFormData();
                        header("location: ../view/activitiesCalendarView.php?error=dbError");
                    }
                
            } else {
                guardarFormData();
                header("location: ../view/activitiesCalendarView.php?error=numberFormat");
            }
        } else {
            guardarFormData();
            header("location: ../view/activitiesCalendarView.php?error=emptyField");
        }
    } else {
        guardarFormData();
        header("location: ../view/activitiesCalendarView.php?error=error");
    }
} 
else if (isset($_POST['delete'])) {

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

    if (isset($_POST['titulo']) && isset($_POST['descripcion'])
         && isset( $_POST['fechaInicioInput']) && isset($_POST['fechaTerminaInput']) && isset($_POST['direccion'])
    ) {

        $idUsuario = $_POST['idUsuario'] ? $_POST['idUsuario'] : 0;
        $titulo = $_POST['titulo'];
        $descripcion = $_POST['descripcion'];
        $fechaInicio = $_POST['fechaInicioInput'];
        $fechaTermina = $_POST['fechaTerminaInput'];
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

        $fechaInicioObj = new DateTime($fechaInicio);
        $fechaTerminaObj = new DateTime($fechaTermina);

        if ($fechaInicioObj >= $fechaTerminaObj) {
            guardarFormData();
            header("Location: ../view/activitiesCalendarView.php?error=invalidDates");
            exit();
        }

        if (strlen($titulo) > 0 && strlen($descripcion) > 0 && strlen($direccion) > 0) {
            if (!is_numeric($titulo) && !is_numeric($descripcion) && !is_numeric($direccion)) {
                if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] != UPLOAD_ERR_NO_FILE) {
                    $directorioImagenes = '../resources/img/actividad/';
                    $nombreArchivoImagen = strtolower(str_replace(' ', '-', $titulo));

                    $resultImagen = procesarImagen('imagen', $directorioImagenes, $nombreArchivoImagen);

                    if (!$resultImagen) {
                        guardarFormData();
                        header("location: ../view/registerView.php?error=imageUpload");
                        exit();
                    }

                }

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
                    $actividad = new Actividad(0, $idUsuario, $titulo, $descripcion, $resultImagen, $fechaInicio, $fechaTermina, $direccion, 0, 0, true, $anonimo, $colectivos);

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
}else if (isset($_POST['createUser'])) {

    if (isset($_POST['titulo']) && isset($_POST['descripcion'])
         && isset( $_POST['fechaInicioInput']) && isset($_POST['fechaTerminaInput']) && isset($_POST['direccion'])
    ) {

        $idUsuario = $_POST['idUsuario'] ? $_POST['idUsuario'] : 0;
        $titulo = $_POST['titulo'];
        $descripcion = $_POST['descripcion'];
        $fechaInicio = $_POST['fechaInicioInput'];
        $fechaTermina = $_POST['fechaTerminaInput'];
        $direccion = $_POST['direccion'];
        $anonimo = isset($_POST['anonimo']) ? true : false;
        $colectivos = $_POST['colectivos'];

        if (strlen($titulo) > 63) {
            guardarFormData();
            header("Location: ../view/activitiesCalendarView.php?error=titleTooLong");
            exit();
        }

        if (strlen($descripcion) > 255) {
            guardarFormData();
            header("Location: ../view/activitiesCalendarView.php?error=descriptionTooLong");
            exit();
        }

        if (strlen($direccion) > 255) {
            guardarFormData();
            header("Location: ../view/activitiesCalendarView.php?error=directionTooLong");
            exit();
        }

        $fechaInicioObj = new DateTime($fechaInicio);
        $fechaTerminaObj = new DateTime($fechaTermina);

        if ($fechaInicioObj >= $fechaTerminaObj) {
            guardarFormData();
            header("Location: ../view/activitiesCalendarView.php?error=invalidDates");
            exit();
        }

        if (strlen($titulo) > 0 && strlen($descripcion) > 0 && strlen($direccion) > 0) {
            if (!is_numeric($titulo) && !is_numeric($descripcion) && !is_numeric($direccion)) {
                if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] != UPLOAD_ERR_NO_FILE) {
                    echo 'entró';
                    $directorioImagenes = '../resources/img/actividad/';
                    $nombreArchivoImagen = strtolower(str_replace(' ', '-', $titulo));

                    $resultImagen = procesarImagen('imagen', $directorioImagenes, $nombreArchivoImagen);

                    if (!$resultImagen) {
                        guardarFormData();
                        header("location: ../view/activitiesCalendarView.php?error=imageUpload");
                        exit();
                    }

                }
                
                $actividadBusiness = new ActividadBusiness();

                $resultExist = $actividadBusiness->exist($titulo);

                if ($resultExist == 1) {
                    guardarFormData();
                    header("location: ../view/activitiesCalendarView.php?error=exist");
                    exit();
                } 
                
                $titulosExistentes = $actividadBusiness->getAllTbActividadTitulos();

                if (esNombreSimilar($titulo, $titulosExistentes)) { 
                    guardarFormData();
                    header("Location: ../view/activitiesCalendarView.php?error=alike");
                    exit();
                } else {
                    $actividad = new Actividad(0, $idUsuario, $titulo, $descripcion, $resultImagen, $fechaInicio, $fechaTermina, $direccion, 0, 0, true, $anonimo, $colectivos);

                    $result = $actividadBusiness->insertTbActividad($actividad);

                    if ($result == 1) {
                        header("location: ../view/activitiesCalendarView.php?success=inserted");
                        exit();
                    } else {
                        guardarFormData();
                        header("location: ../view/activitiesCalendarView.php?error=dbError");
                        exit();
                    }
                }
            } else {
                guardarFormData();
                header("location: ../view/activitiesCalendarView.php?error=numberFormat");
                exit();
            }
        } else {
            guardarFormData();
            header("location: ../view/activitiesCalendarView.php?error=emptyField");
        }
    } else {
        guardarFormData();
        header("location: ../view/activitiesCalendarView.php?error=error");
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
}else if(isset($_POST['registerAttendance'])){

    if(!isset($_POST['attendance']) || !$_POST['attendance']==true){
        header("location: ../view/activitiesCalendarView.php");

    }else if(isset($_POST['idActividadAttendance']) && isset($_POST['idUsuarioLogeado'])){
        $idActividad = $_POST['idActividadAttendance'];
        $idUsuario = $_POST['idUsuarioLogeado'];

        $actividadBusiness = new ActividadBusiness();
        $result = $actividadBusiness->insertAttendance($idActividad, $idUsuario);

        if ($result == 1) {
            header("location: ../view/activitiesCalendarView.php?success=registerAttendance");
        } else {
            header("location: ../view/activitiesCalendarView.php?error=dbError");
        }

    }else{
        header("location: ../view/activitiesCalendarView.php?error=error");
    }

}else if(isset($_POST['checkAttendance'])){

    if(isset($_POST['idActividad']) && isset($_POST['idUsuario'])){
        $idActividad = $_POST['idActividad'];
        $idUsuario = $_POST['idUsuario'];

        $actividadBusiness = new ActividadBusiness();
        $result = $actividadBusiness->askAttendance($idActividad, $idUsuario);

        echo json_encode($result); 

    }else{
        header("location: ../view/activitiesCalendarView.php?error=error");
    }

}else if(isset($_POST['deleteAttendance'])){

    if(!isset($_POST['cancel']) || !$_POST['cancel']==true){
        header("location: ../view/activitiesCalendarView.php");

    }else if(isset($_POST['idActividadDelAttendance']) && isset($_POST['idDelUsuarioLogeado'])){
        $idActividad = $_POST['idActividadDelAttendance'];
        $idUsuario = $_POST['idDelUsuarioLogeado'];

        $actividadBusiness = new ActividadBusiness();
        $result = $actividadBusiness->cancelAttendance($idActividad, $idUsuario);

        if ($result == 1) {
            header("location: ../view/activitiesCalendarView.php?success=cancelAttendance");
            
        } else {
            header("location: ../view/activitiesCalendarView.php?error=dbError");
        }

    }else{
        header("location: ../view/activitiesCalendarView.php?error=error");
    }

}else if(isset($_POST['getListAttendance'])){

    if(isset($_POST['idActividad'])){
        $idActividad = $_POST['idActividad'];

        $actividadBusiness = new ActividadBusiness();
        $list = $actividadBusiness->getListAttendance($idActividad);

        echo json_encode($list); 

    }else{
        header("location: ../view/activitiesCalendarView.php?error=error");
    }

}

