<?php

include_once "../business/personalProfileBusiness.php";
include_once "../business/usuarioBusiness.php";
$personalProfileBusiness = new PersonalProfileBusiness();
$usuarioBusiness = new UsuarioBusiness();

if(isset($_POST["registrar"])){
    if(isset($_POST["criteriaString"]) && isset($_POST["valuesString"])){ //todos los datos

        $criterio = $_POST["criteriaString"];
        $valor = $_POST["valuesString"];

        $usuarioId = $usuarioBusiness->getIdByName($_SESSION['nombreUsuario']);
        
        if($personalProfileBusiness->profileExists($usuarioId)){
            $personalProfileBusiness->updateTbPerfilPersonal($criterio,$valor, $usuarioId); 
        }else{
            $personalProfileBusiness->insertTbPerfilPersonal($criterio,$valor, $usuarioId); 
        }

        header("location: ../view/userPersonalProfileView.php?success=inserted");
    }
    else{
        header("location: ../view/userPersonalProfileView.php?error=formIncomplete");
    }
    
}