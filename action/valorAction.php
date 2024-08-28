<?php

include_once '../bussiness/valorBusiness.php'; // Incluye el archivo de negocios correspondiente

if (isset($_POST['update'])) {

    if (isset($_POST['idCriterio']) && isset($_POST['nombre']) && isset($_POST['idValor'])) {

        $idValor = $_POST['idValor']; // Aquí se corrige
        $idCriterio = $_POST['idCriterio'];
        $nombre = $_POST['nombre'];

        if (strlen($nombre) > 0) {
            if (!is_numeric($nombre)) {
                $valorBusiness = new ValorBusiness();

                $resultExist = $valorBusiness->exist($nombre);

                if ($resultExist == 1) {
                    header("Location: ../view/valorView.php?error=exist");
                } else {
                    $valor = new Valor($idValor, $nombre, $idCriterio, 1); // Estado 1 = Activo
                    $result = $valorBusiness->updateTbValor($valor);

                    if ($result == 1) {
                        header("Location: ../view/valorView.php?success=updated");
                    } else {
                        header("Location: ../view/valorView.php?error=dbError");
                    }
                }
            } else {
                header("Location: ../view/valorView.php?error=numberFormat");
            }
        } else {
            header("Location: ../view/valorView.php?error=emptyField");
        }
    } else {
        header("Location: ../view/valorView.php?error=error");
    }
} else if (isset($_POST['delete'])) {

    if (isset($_POST['idValor'])) {

        $idValor = $_POST['idValor'];

        $valorBusiness = new ValorBusiness();
        $result = $valorBusiness->deleteTbValor($idValor);

        if ($result == 1) {
            header("Location: ../view/valorView.php?success=deleted");
        } else {
            header("Location: ../view/valorView.php?error=dbError");
        }
    } else {
        header("Location: ../view/valorView.php?error=error");
    }
} else if (isset($_POST['create'])) {

    if (isset($_POST['nombre']) && isset($_POST['idCriterio'])) {

        $idCriterio = $_POST['idCriterio'];
        $nombre = $_POST['nombre'];
        $criterioId = $_POST['criterioId'];    

        if (strlen($nombre) > 0) {
            if (!is_numeric($nombre)) {
                $valorBusiness = new ValorBusiness();

                $resultExist = $valorBusiness->exist($nombre);

                if ($resultExist == 1) {
                    header("Location: ../view/valorView.php?error=exist");
                } else {
                    $valor = new Valor(0, $nombre, $idCriterio, 1); // Estado 1 = Activo
                    $result = $valorBusiness->insertTbValor($valor);

                    if ($result == 1) {
                        header("Location: ../view/valorView.php?success=inserted");
                    } else {
                        header("Location: ../view/valorView.php?error=dbError");
                    }
                }
            } else {
                header("Location: ../view/valorView.php?error=numberFormat");
            }
        } else {
            header("Location: ../view/valorView.php?error=emptyField");
        }
    } else {
        header("Location: ../view/valorView.php?error=error");
    }
} else if(isset($_GET['idCriterio'])) {
    $idCriterio = $_GET['idCriterio'];
    $valorBusiness = new ValorBusiness();
    //$valorBusiness->getAllTbValorByCriterio($idCriterio);
} else {
    header("Location: ../view/valorView.php?error=error");
}