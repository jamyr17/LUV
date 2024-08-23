<?php

include '../bussiness/valorBusiness.php'; // Incluye el archivo de negocios correspondiente

// Mostrar errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_POST['create'])) {

    if (isset($_POST['nombre']) && isset($_POST['criterioId'])) {

        $nombre = $_POST['nombre'];
        $criterioId = $_POST['criterioId'];

        if (strlen($nombre) > 0) {
            if (!is_numeric($nombre)) {
                $valorBusiness = new ValorBusiness();

                $resultExist = $valorBusiness->exist($nombre);

                if ($resultExist) {
                    header("Location: ../view/valorView.php?error=exist");
                } else {
                    $valor = new Valor(0, $nombre, $criterioId, 1); // Estado 1 = Activo
                    $result = $valorBusiness->insertTbValor($valor);

                    if ($result) {
                        header("Location: ../view/valorView.php?success=inserted");
                    } else {
                        echo "Error al insertar el valor.";
                        // Puedes agregar más detalles aquí si es necesario
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
} else if (isset($_POST['update'])) {

    if (isset($_POST['idValor']) && isset($_POST['nombre']) && isset($_POST['criterioId'])) {

        $idValor = $_POST['idValor'];
        $nombre = $_POST['nombre'];
        $criterioId = $_POST['criterioId'];

        if (strlen($nombre) > 0) {
            if (!is_numeric($nombre)) {
                $valorBusiness = new ValorBusiness();

                $valor = new Valor($idValor, $nombre, $criterioId, 1); // Estado 1 = Activo
                $result = $valorBusiness->updateTbValor($valor);

                if ($result) {
                    header("Location: ../view/valorView.php?success=updated");
                } else {
                    echo "Error al actualizar el valor.";
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

        if ($result) {
            header("Location: ../view/valorView.php?success=deleted");
        } else {
            echo "Error al eliminar el valor.";
        }
    } else {
        header("Location: ../view/valorView.php?error=error");
    }
} else {
    header("Location: ../view/valorView.php?error=error");
}
?>
