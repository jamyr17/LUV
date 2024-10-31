<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once '../business/usuarioBusiness.php';
include_once 'functions.php';

$usuarioBusiness = new UsuarioBusiness();

if (isset($_POST['login'])) {
    if (isset($_POST['nombreUsuario']) && isset($_POST['contrasena'])) {
        $nombreUsuario = $_POST['nombreUsuario'];
        $contrasena = $_POST['contrasena'];

        // Validación de credenciales
        $result = $usuarioBusiness->loginValidation($nombreUsuario, $contrasena);

        // Verifica si el usuario es válido
        if ($result) {
            $_SESSION['usuarioId'] = $result['tbusuarioid'];
            $_SESSION['nombreUsuario'] = $nombreUsuario;
            $_SESSION['tipoUsuario'] = ($result['tbtipousuarioid'] == 1) ? 'Administrador' : 'Usuario';

            // Actualiza el estado de disponibilidad a "Disponible"
            $usuarioBusiness->actualizarCondicion($_SESSION['usuarioId'], 'Disponible');

            // Redirige según el tipo de usuario
            $redirectUrl = ($result['tbtipousuarioid'] == 1) ? "../index.php" : "../view/userNavigateView.php";
            header("location: $redirectUrl");
            exit();
        } else {
            // Credenciales incorrectas
            header("location: ../view/login.php?error=noValidated");
            exit();
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

        if (strlen($cedula) > 0 && strlen($primerNombre) > 0 && strlen($primerApellido) > 0 && strlen($nombreUsuario) > 0 && strlen($contrasena) > 0) {
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
                        if(isset($_FILES['pfp'])){
                            $directorioImagenes = '../resources/img/profile/';
                            $nombreArchivoImagen = strtolower(str_replace(' ', '-', $nombreUsuario));

                            $resultImagen = procesarImagen('pfp', $directorioImagenes, $nombreArchivoImagen);

                            if (!$resultImagen) {
                                guardarFormData();
                                header("location: ../view/registerView.php?error=imageUpload");
                                exit();
                            }

                        }

                        $result = $usuarioBusiness->insertTbUsuario($cedula, $primerNombre, $primerApellido, $nombreUsuario, $contrasena, $resultImagen);

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
