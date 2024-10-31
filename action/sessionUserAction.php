<?php
include_once "../business/usuarioBusiness.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Validar permiso para entrar a esta página
if (!isset($_SESSION['usuarioId']) || $_SESSION["tipoUsuario"] !== "Usuario") {
    header("location: ../view/login.php?error=accessDenied");
    exit();
}

// Procesar solicitud de cerrar sesión
if (isset($_POST["logout"])) {
    $usuarioBusiness = new UsuarioBusiness();
    $usuarioBusiness->actualizarCondicion($_SESSION['usuarioId'], 'No disponible');
    $_SESSION = array();
    session_destroy();
    header("location: ../view/login.php?success=logout");
}

