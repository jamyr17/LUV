<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../business/usuarioBusiness.php';
include 'functions.php';

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
                
                $resultPersonExist = $usuarioBusiness->existPerson($cedula);

                if ($resultPersonExist == 1) {
                    guardarFormData();
                    header("location: ../view/registerView.php?error=existPerson");
                } else{
                    $resultUsernameExist = $usuarioBusiness->existUsername($nombreUsuario);

                    if ($resultUsernameExist == 1) {
                        guardarFormData();
                        header("location: ../view/registerView.php?error=existUsername");
                    } else {
                        
                        $result = $usuarioBusiness->insertTbUsuario($cedula, $primerNombre, $primerApellido, $nombreUsuario, $contrasena);

                        if ($result == 1) {
                            header("location: ../view/login.php?success=inserted");
                        } else {
                            guardarFormData();
                            header("location: ../view/registerView.php?error=dbError");
                        }

                    }
                }
                
            } else {
                guardarFormData();
                header("location: ../view/registerView.php?error=numberFormat");
            }
        } else {
            guardarFormData();
            header("location: ../view/registerView.php?error=emptyField");
        }

    }

}
