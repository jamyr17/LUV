<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//Validar permiso para entrar a esta pagina
if ($_SESSION["tipoUsuario"] == "Usuario" || empty($_SESSION["tipoUsuario"])) {
    header("location: login.php?error=accessDenied");
    exit();
}

//Procesar solicitud de cerrar sesión
if(isset($_POST["logout"])){
    $_SESSION = array();
    session_destroy();
    header("location: ../view/login.php?success=logout");
    exit();
}