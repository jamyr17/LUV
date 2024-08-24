<?php

include_once "../bussiness/wantedProfileBusiness.php";
$wantedProfileBusiness = new WantedProfileBussiness();

//Nuevo registro de perfil deseado
if(isset($_POST["registrar"])){
    if(isset($_POST["criteriaString"]) && isset($_POST["valuesString"]) && isset($_POST["percentagesString"]) && isset($_POST["totalPercentageInp"])){ //todos los datos
        if($_POST["totalPercentageInp"]!=100){ //no repartió correctamente los porcentajes
            header("location: ../view/userWantedProfileView.php?error=percentageIncomplete");
        }

        $criterio = $_POST["criteriaString"];
        $valor = $_POST["valuesString"];
        $porcentaje = $_POST["percentagesString"];

        $wantedProfileBusiness->insertTbPerfilDeseado($criterio,$valor,$porcentaje);
        header("location: ../view/userWantedProfileView.php?success=inserted");
    }
    else{
        header("location: ../view/userWantedProfileView.php?error=formIncomplete");
    }
    
}