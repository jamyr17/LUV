<?php

include './usuarioBussiness.php';

session_start();

if (isset($_POST['login'])){
    if (isset($_POST['nombreUsuario']) && isset($_POST['contrasena'])){
        $nombreUsuario = $_POST['nombreUsuario'];
        $contrasena = $_POST['contrasena'];

        $usuarioBussiness = new UsuarioBussiness();

        $result = $usuarioBussiness->loginValidation($nombreUsuario, $contrasena);
        $_SESSION['nombreUsuario'] = $nombreUsuario;

        if($result['tbtipousuarioid']==1){ //Administrador   
            $_SESSION['tipoUsuario'] = 'Administrador';

        } else if($result['tbtipousuarioid']==2){ //Usuario
            $_SESSION['tipoUsuario'] = 'Usuario';
        }

        header("location: ../indexView.php");

    }

}