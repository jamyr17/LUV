<?php

include_once "../business/personalProfileBusiness.php";
include_once "../business/usuarioBusiness.php";
$personalProfileBusiness = new PersonalProfileBusiness();
$usuarioBusiness = new UsuarioBusiness();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(isset($_POST["registrar"])){
    if(isset($_SESSION['criteriaString']) && !empty([$_SESSION['criteriaString']]) && isset($_SESSION['valueString']) && !empty([$_SESSION['valueString']])){ //todos los datos

        $usuarioId = $usuarioBusiness->getIdByName($_SESSION['nombreUsuario']);
        $criterioParam = $_SESSION['criteriaString'];
        $valorParam = $_SESSION['valueString'];
        
        if($personalProfileBusiness->profileExists($usuarioId)){
            $personalProfileBusiness->updateTbPerfilPersonal($criterioParam,$valorParam, $usuarioId); 
            header("location: ../view/userPersonalProfileView.php?success=updated");
        }else{
            $personalProfileBusiness->insertTbPerfilPersonal($criterioParam,$valorParam, $usuarioId); 
            header("location: ../view/userPersonalProfileView.php?success=inserted");
        }

    }
    else{
        header("location: ../view/userPersonalProfileView.php?error=formIncomplete");
    }
    
}