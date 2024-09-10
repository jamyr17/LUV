<?php

include_once '../business/valorBusiness.php'; 
include 'functions.php';

$maxLength = 255;

function agregarValorSiNoExiste($nombreArchivo, $valor) {
    $filePath = "../resources/criterios/{$nombreArchivo}.dat";

    // Leer el archivo y obtener los contenidos
    $contenidoActual = [];
    if (file_exists($filePath)) {
        $contenidoActual = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    }

    // Verificar si el valor ya existe en el archivo
    if (!in_array($valor, $contenidoActual)) {
        // Si el valor no existe, agregarlo al archivo
        $file = fopen($filePath, 'a');
        if ($file) {
            fwrite($file, ",". $valor . PHP_EOL);
            fclose($file);
            return "Valor '{$valor}' agregado al archivo.";
        } else {
            return "Error: No se pudo abrir el archivo {$filePath} para escritura.";
        }
    } else {
        return "El valor '{$valor}' ya existe en el archivo.";
    }
}



if (isset($_POST['update'])) {

    if (isset($_POST['idCriterio']) && isset($_POST['nombre']) && isset($_POST['idValor'])) {

        $idValor = $_POST['idValor']; // Aquí se corrige
        $idCriterio = $_POST['idCriterio'];
        $nombre = $_POST['nombre'];

        if (strlen($nombre) > $maxLength) {
            guardarFormData();
            header("Location: ../view/valorView.php?error=nameTooLong");
            exit();
        }

        if (strlen($nombre) > 0) {
            if (!is_numeric($nombre)) {
                $valorBusiness = new ValorBusiness();

                $resultExist = $valorBusiness->nameExist($nombre,$idValor);
                if ($resultExist == 1) {
                    guardarFormData();
                    header("Location: ../view/valorView.php?error=exist");
                } else {
                    $valor = new Valor($idValor, $nombre, $idCriterio, 1); // Estado 1 = Activo
                    $result = $valorBusiness->updateTbValor($valor);

                    if ($result == 1) {
                        header("Location: ../view/valorView.php?success=updated");
                    } else {
                        guardarFormData();
                        header("Location: ../view/valorView.php?error=dbError");
                    }
                }
            } else {
                guardarFormData();
                header("Location: ../view/valorView.php?error=numberFormat");
            }
        } else {
            guardarFormData();
            header("Location: ../view/valorView.php?error=emptyField");
        }
    } else {
        guardarFormData();
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

    if (isset($_POST['nombre']) && isset($_POST['idCriterio']) && isset($_POST['criterioNombre'])) {

        $idCriterio = $_POST['idCriterio'];
        $nombre = $_POST['nombre'];
        $criterioNombre = $_POST['criterioNombre'];  // Ahora tienes el nombre del criterio directamente

        if (strlen($nombre) > $maxLength) {
            guardarFormData();
            header("Location: ../view/valorView.php?error=nameTooLong");
            exit();
        }

        if (strlen($nombre) > 0) {
            if (!is_numeric($nombre)) {
                $valorBusiness = new ValorBusiness();
    
                $resultExist = $valorBusiness->exist($nombre);
    
                if ($resultExist == 1) {
                    guardarFormData();
                    header("Location: ../view/valorView.php?error=exist");
                } 
                
                $nombresExistentes = $valorBusiness->getAllTbValorNombres();
                if (esNombreSimilar($nombre, $nombresExistentes)) {
                    guardarFormData();
                    header("location: ../view/valorView.php?error=alike");
                    exit();
                } else {
                    $mensaje = agregarValorSiNoExiste($criterioNombre, $nombre);
    
                    $valor = new Valor(0, $nombre, $idCriterio, 1);
                    $result = $valorBusiness->insertTbValor($valor);
    
                    if ($result == 1) {
                        header("Location: ../view/valorView.php?success=inserted");
                    } else {
                        guardarFormData();
                        header("Location: ../view/valorView.php?error=dbError");
                    }
                }
            } else {
                guardarFormData();
                header("Location: ../view/valorView.php?error=numberFormat");
            }
        } else {
            guardarFormData();
            header("Location: ../view/valorView.php?error=emptyField");
        }
    } else if (isset($_GET['idCriterio'])) {
        $idCriterio = $_GET['idCriterio'];
        $valorBusiness = new ValorBusiness();
        //$valorBusiness->getAllTbValorByCriterio($idCriterio);
    } else {
        header("Location: ../view/valorView.php?error=error");
    }
}else if (isset($_POST['restore'])) {

    if (isset($_POST['idValor'])) {
        $idValor = $_POST['idValor'];
        $ValorBusiness = new ValorBusiness();
        $result = $ValorBusiness->restoreTbValor($idValor);

        if ($result == 1) {
            header("location: ../view/valorView.php?success=restored");
        } else {
            header("location: ../view/valorView.php?error=dbError");
        }
    } else {
        header("location: ../view/valorView.php?error=error");
    }
}
