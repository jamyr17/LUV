<?php

include_once "../bussiness/personalProfileBusiness.php";
include_once "../bussiness/usuarioBusiness.php";
$personalProfileBusiness = new PersonalProfileBusiness();
$usuarioBusiness = new UsuarioBusiness();

if(isset($_POST["registrar"])){
    if(isset($_POST["criteriaString"]) && isset($_POST["valuesString"])){ //todos los datos

        $criterio = $_POST["criteriaString"];
        $valor = $_POST["valuesString"];

        $usuarioId = $usuarioBusiness->getIdByName($_SESSION['nombreUsuario']);

        $personalProfileBusiness->insertTbPerfilPersonal($criterio,$valor, $usuarioId);
        header("location: ../view/userPersonalProfileView.php?success=inserted");
    }
    else{
        header("location: ../view/userPersonalProfileView.php?error=formIncomplete");
    }
    
}