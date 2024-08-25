<?php
session_start();

if ($_SESSION["tipoUsuario"] == "Usuario" || empty($_SESSION["tipoUsuario"])) {
    header("location: ./login.php?error=accessDenied");
    exit(); // Asegúrate de detener la ejecución del script después de redirigir
}

include '../bussiness/valorBusiness.php';
include '../bussiness/criterioBusiness.php';

$valorBusiness = new ValorBusiness();
$criterioBusiness = new CriterioBusiness();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LUV</title>
    <script>
        function actionConfirmation(mensaje) {
            return confirm(mensaje);
        }

        function showMessage(mensaje) {
            alert(mensaje);
        }
    </script>
</head>

<body>

    <header>
        <nav class="navbar bg-body-tertiary"></nav>
    </header>

    <div class="container mt-3">

        <section id="alerts">
            <?php
            if (isset($_GET['error'])) {
                $mensaje = "Ocurrió un error debido a ";
                $mensaje .= match ($_GET['error']) {
                    "emptyField" => "campo(s) vacío(s).",
                    "numberFormat" => "ingreso de valores númericos.",
                    "dbError" => "un problema al procesar la transacción.",
                    "exist" => "que dicho valor ya existe.",
                    default => "un problema inesperado.",
                };
            } else if (isset($_GET['success'])) {
                $mensaje = match ($_GET['success']) {
                    "inserted" => "Valor creado correctamente.",
                    "updated" => "Valor actualizado correctamente.",
                    "deleted" => "Valor eliminado correctamente.",
                    default => "Transacción realizada.",
                };
            }

            if (isset($mensaje)) {
                echo "<script>showMessage('$mensaje')</script>";
            }
            ?>
        </section>

        <section id="form">
            <div class="container">
                <button onclick="window.location.href='../indexView.php';">Volver</button>

                <div class="text-center mb-4">
                    <h3>Agregar un nuevo valor</h3>
                    <p class="text-muted">Complete el formulario para añadir un nuevo valor</p>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label for="nombre" class="form-label">Nombre: </label>
                        <input required type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre del valor" />
                    </div>
                </div>

                <div class="container d-flex justify-content-center">
                <form method="post" action="../action/valorAction.php" style="width: 50vw; min-width:300px;">
                    <input type="hidden" name="idValor" value="<?php echo htmlspecialchars($idValor); ?>">

                        <label for="criterioId">Criterio:</label>
                        <select name="criterioId" id="criterioId">
                            <?php
                            $criterios = $criterioBusiness->getAllTbCriterio();
                            if ($criterios != null) {
                                foreach ($criterios as $criterio) {  
                                    echo '<option value="' . htmlspecialchars($criterio->getTbCriterioId()) . '">' . htmlspecialchars($criterio->getTbCriterioNombre()) . '</option>';
                                }
                            }
                            ?>
                        </select><br>

                        <div>
                            <button type="submit" class="btn btn-success" name="create" id="create">Crear</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <section id="table">
            <div class="text-center mb-4">
                <h3>Valores registrados</h3>
            </div>

            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $valores = $valorBusiness->getAllTbValor();
                    $mensajeActualizar = "¿Desea actualizar este valor?";
                    $mensajeEliminar = "¿Desea eliminar este valor?";
                    if ($valores != null) {
                        foreach ($valores as $valor) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($valor->getTbValorId()) . '</td>';
                            echo '<td><form method="post" action="../action/valorAction.php">';
                            echo '<input type="hidden" name="idValor" value="' . htmlspecialchars($valor->getTbValorId()) . '">';
                            echo '<input type="text" name="nombre" value="' . htmlspecialchars($valor->getTbValorNombre()) . '" class="form-control" />';
                            echo '</td>';
                            //echo '<td>' . htmlspecialchars($valor->getTbCriterioId()) . '</td>';
                            echo '<td>';
                            echo "<button type='submit' class='btn btn-warning me-2' name='update' id='update' onclick='return actionConfirmation(\"$mensajeActualizar\")'>Actualizar</button>";
                            echo "<button type='submit' class='btn btn-danger' name='delete' id='delete' onclick='return actionConfirmation(\"$mensajeEliminar\")'>Eliminar</button>";
                            echo '</form>';
                            echo '</td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </div>

    <script>
        // Additional scripts if needed
    </script>

</body>

<footer>
</footer>

</html>
