<?php

include_once "../bussiness/wantedProfileBusiness.php";
$wantedProfileBusiness = new WantedProfileBussiness();

//Nuevo registro de perfil deseado
if(isset($_POST["search"])){
    if(isset($_POST["criteriaString"]) && isset($_POST["valuesString"]) && isset($_POST["percentagesString"]) && isset($_POST["totalPercentageInp"])){ //todos los datos
        
        if($_POST["totalPercentageInp"]!=100){ //no repartió correctamente los porcentajes
            header("location: ../view/userWantedProfileView.php?error=percentageIncomplete");
        }

        // insertar el registro de lo deseado por el usuario:
        $criterioParam = $_POST["criteriaString"];
        $valorParam = $_POST["valuesString"];
        $porcentajeParam = $_POST["percentagesString"];
        $wantedProfileBusiness->insertTbPerfilDeseado($criterioParam,$valorParam,$porcentajeParam); 

        // filtrar perfiles segun lo que desea el usuario: 
        $allPerfiles = $wantedProfileBusiness->getAllTbPerfiles();

        // si no hay perfiles registrados
        if(empty($allPerfiles)){
            header("location: ../view/userWantedProfileView.php?error=noProfiles");
        }else{
            $perfilesFiltrados = filterProfiles($allPerfiles, $criterioParam, $valorParam, $porcentajeParam);

            // guardar los perfiles filtrados en sesión
            session_start(); 
            $_SESSION['perfilesMatcheados'] = $perfilesFiltrados;
            header("location: ../view/userProfileRecommendationsView.php");
        }
               
    }
    else{
        header("location: ../view/userWantedProfileView.php?error=formIncomplete");
    }
     
}

// Definir función para filtrar y ordenar perfiles
function filterProfiles($allPerfiles, $criterioParam, $valorParam, $porcentajeParam) {
    $criterioDividido = explode(',', $criterioParam);
    $valorDividido = explode(',', $valorParam);
    $porcentajeDividido = explode(',', $porcentajeParam);

    foreach ($allPerfiles as $indicePerfil => $perfil) {
        // Inicializa 'ponderado' si no existe
        if (!isset($allPerfiles[$indicePerfil]['ponderado'])) {
            $allPerfiles[$indicePerfil]['ponderado'] = 0;
        }

        $criteriosPerfil = explode(',', $perfil['criterio']);
        
        foreach ($criterioDividido as $indiceCriterioDeseado => $criterioDeseado) {
            foreach ($criteriosPerfil as $indiceCriterioPerfil => $criterioPerfil) {
                // Comparar los criterios
                if ($criterioDeseado === $criterioPerfil) {
                    $valorCriterio = explode(',', $perfil['valor'])[$indiceCriterioPerfil]; 

                    if ($valorCriterio == $valorDividido[$indiceCriterioDeseado]) {
                        $allPerfiles[$indicePerfil]['ponderado'] += $porcentajeDividido[$indiceCriterioDeseado];
                    }
                }
            }
        }
    }

    // Filtrar los perfiles con un ponderado mayor al 30%
    $perfilesFiltrados = array_filter($allPerfiles, function($perfil) {
        return $perfil['ponderado'] > 30;
    });

    // Ordenar los perfiles filtrados de mayor a menor ponderado
    usort($perfilesFiltrados, function($a, $b) {
        return $b['ponderado'] <=> $a['ponderado'];
    });

    // Limitar a los 20 perfiles con mayor ponderado si hay más de 20
    if (count($perfilesFiltrados) > 20) {
        $perfilesFiltrados = array_slice($perfilesFiltrados, 0, 20);
    }

    return $perfilesFiltrados;
}
