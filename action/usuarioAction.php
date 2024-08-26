<?php
session_start();

include '../bussiness/usuarioBusiness.php';

$usuarioBusiness = new UsuarioBusiness();

if (isset($_POST['login'])){
    if (isset($_POST['nombreUsuario']) && isset($_POST['contrasena'])){
        $nombreUsuario = $_POST['nombreUsuario'];
        $contrasena = $_POST['contrasena'];

        $result = $usuarioBusiness->loginValidation($nombreUsuario, $contrasena);
        $_SESSION['nombreUsuario'] = $nombreUsuario;

        if($result['tbtipousuarioid']==1){ //Administrador   
            $_SESSION['tipoUsuario'] = 'Administrador';
            header("location: ../indexView.php");
        } else if($result['tbtipousuarioid']==2){ //Usuario
            $_SESSION['tipoUsuario'] = 'Usuario';
            header("location: ../view/userNavigateView.php");
        }else{
            header("location: ../view/login.php?error=noValidated");
        }

    }

}

if(isset($_POST['newUser'])){
    if (isset($_POST['cedula']) && isset($_POST['primerNombre']) && isset($_POST['primerApellido']) && isset($_POST['nombreUsuario']) && isset($_POST['contrasena'])){
        $cedula = $_POST['cedula'];
        $primerNombre = $_POST['primerNombre'];
        $primerApellido = $_POST['primerApellido'];
        $nombreUsuario = $_POST['nombreUsuario'];
        $contrasena = $_POST['contrasena'];

        if (strlen($cedula) > 0 && strlen($primerNombre > 0 && strlen($primerApellido) > 0 && strlen($nombreUsuario) > 0 && strlen($contrasena) > 0)) {
            if (!is_numeric($primerNombre) && !is_numeric($primerApellido) && !is_numeric($nombreUsuario)) {
                
                //$resultExist = $campusBusiness->exist($nombre);

                //if ($resultExist == 1) {
                   // header("location: ../view/campusView.php?error=exist");
                //} else {
                    
                    $result = $usuarioBusiness->insertTbUsuario($cedula, $primerNombre, $primerApellido, $nombreUsuario, $contrasena);

                    if ($result == 1) {
                        header("location: ../view/login.php?success=inserted");
                    } else {
                        header("location: ../view/registerView.php?error=dbError");
                    }

                //}
                
            } else {
                header("location: ../view/registerView.php?error=numberFormat");
            }
        } else {
            header("location: ../view/registerView.php?error=emptyField");
        }

    }

}
