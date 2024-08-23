<?php

include_once "../bussiness/wantedProfileBusiness.php";
$wantedProfileBusiness = new WantedProfileBussiness();

//Nuevo registro de perfil deseado
if(isset($_POST["registrar"])){
    if(isset($_POST["criteriaString"]) && isset($_POST["valuesString"]) && isset($_POST["percentagesString"]) && isset($_POST["totalPercentageInp"])){ //todos los datos
        if($_POST["totalPercentageInp"]!=100){ //no repartió correctamente los porcentajes
            echo "Distribuya correctamente los porcentajes.";
        }

        $criterio = $_POST["criteriaString"];
        $valor = $_POST["valuesString"];
        $porcentaje = $_POST["percentagesString"];

        $wantedProfileBusiness->insertTbPerfilDeseado($criterio,$valor,$porcentaje);
    }
    else{
        echo "Ocurrió un error en el envío del formulario.";
    }
    

    if (isset($_POST["filtrado"])) {
        if (isset($_POST["criterio"]) && isset($_POST["valor"])) {
            $criterio = $_POST["criterio"];
            $valor = $_POST["valor"];

            $allPerfiles = $wantedProfileBusiness->getAllTbPerfiles();

            $perfilesFiltrados = [];
    
            if ($allPerfiles) {

                foreach ($allPerfiles as $perfil) {
                    if ($perfil['tbperfilusuariopersonalcriterio'] == $criterio && $perfil['ttbperfilusuariopersonalvalor'] == $valor) {
                        $perfilesFiltrados[] = $perfil;
                    }
                }
    
                if (!empty($perfilesFiltrados)) {
                    foreach ($perfilesFiltrados as $perfil) {
                        echo "Criterio: " . $perfil['tbperfilusuariopersonalcriterio'] . " Valor: " . $perfil['tbperfilusuariopersonalvalor'] . "<br>";
                    }
                } else {
                    echo "No se encontraron perfiles deseados que coincidan con los criterios de filtrado.";
                }
            } else {
                echo "No se encontraron perfiles deseados que coincidan con los criterios de filtrado.";
            }
        } else {
            echo "Debe proporcionar un criterio y un valor para realizar el filtrado.";
        }
    }
    
}