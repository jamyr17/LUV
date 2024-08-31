<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function baseUrl() {
    // Detectar el protocolo (http o https)
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    
    // Obtener el nombre del host (e.g., localhost)
    $host = $_SERVER['HTTP_HOST'];
    
    // Definir la carpeta raíz del proyecto
    $projectRoot = '/LUV';

    // Construir y retornar la URL base
    return $protocol . "://" . $host . $projectRoot;
}

//Validar permiso para entrar a esta pagina
if ($_SESSION["tipoUsuario"] == "Usuario" || empty($_SESSION["tipoUsuario"])) {
    header("Location: " . baseUrl() . "/view/login.php?error=accessDenied");
    exit();
}

//Procesar solicitud de cerrar sesión
if(isset($_POST["logout"])){
    $_SESSION = array();
    session_destroy();
    header("Location: " . baseUrl() . "/view/login.php?success=logout");
    exit();
}