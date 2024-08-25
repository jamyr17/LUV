<?php

include_once "../bussiness/personalProfileBusiness.php";
$personalProfileBusiness = new PersonalProfileBusiness();

if(isset($_POST["registrar"])){
    if(isset($_POST["criteriaString"]) && isset($_POST["valuesString"])){ //todos los datos

        $criterio = $_POST["criteriaString"];
        $valor = $_POST["valuesString"];

        $personalProfileBusiness->insertTbPerfilPersonal($criterio,$valor);
        header("location: ../view/userPersonalProfileView.php?success=inserted");
    }
    else{
        header("location: ../view/userPersonalProfileView.php?error=formIncomplete");
    }
    
}