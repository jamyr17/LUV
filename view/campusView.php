<?php
include "../action/sessionAction.php";
include '../bussiness/universidadBussiness.php';
include '../bussiness/campusBussiness.php';
include '../bussiness/universidadCampusRegionBussiness.php';
include '../bussiness/universidadCampusColectivoBussiness.php';
include '../bussiness/universidadCampusEspecializacionBussiness.php';

$universidadBusiness = new UniversidadBusiness();
$campusBussiness = new CampusBusiness();
$campusRegionBussiness = new UniversidadCampusRegionBussiness();
$campusColectivoBussiness = new UniversidadCampusColectivoBussiness();
$campusEspecializacionBussiness = new UniversidadCampusEspecializacionBussiness();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Management</title>
    <script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAShWeubkR1_4C7NOevWTEwZsM14px3H74&libraries=places&callback=initMap">
    </script>
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
                    "numberFormat" => "ingreso de valores numéricos.",
                    "dbError" => "un problema al procesar la transacción.",
                    "exist" => "que dicho campus ya existe.",
                    default => "un problema inesperado.",
                };
            } else if (isset($_GET['success'])) {
                $mensaje = match ($_GET['success']) {
                    "inserted" => "Campus creado correctamente.",
                    "updated" => "Campus actualizado correctamente.",
                    "deleted" => "Campus eliminado correctamente.",
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
                <form method="post" action="../action/sessionAction.php">
                    <button type="submit" class="btn btn-success" name="logout" id="logout">Cerrar sesión</button>
                </form>

                <div class="text-center mb-4">
                    <h3>Agregar un nuevo campus</h3>
                    <p class="text-muted">Complete el formulario para añadir un nuevo campus</p>
                </div>

                <div class="container d-flex justify-content-center">
                    <form method="post" action="../action/campusAction.php" style="width: 50vw; min-width:300px;">
                        <input type="hidden" name="idCampus" value="">

                        <label for="idUniversidad">Universidad:</label>
                        <select id="idUniversidad" name="idUniversidad" class="form-control">
                            <?php
                            $universidades = $universidadBusiness->getAllTbUniversidad();
                            if ($universidades != null) {
                                foreach ($universidades as $universidad) {
                                    $id = htmlspecialchars($universidad->getTbUniversidadId());
                                    $nombre = htmlspecialchars($universidad->getTbUniversidadNombre());
                                    echo '<option value="' . $id . '">' . $nombre . '</option>';
                                }
                            }
                            ?>
                        </select><br>

                        <div class="row">
                            <div class="col">
                                <label for="nombre" class="form-label">Nombre: </label>
                                <input required type="text" name="nombre" id="nombre" class="form-control" placeholder="Campus Omar Dengo" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <label for="direccion" class="form-label">Dirección: </label>
                                <input type="text" id="direccion" name="direccion" class="form-control" placeholder="Ingresa el nombre de una sede..." />
                                <input type="hidden" id="latitud" name="latitud"/>
                                <input type="hidden" id="longitud" name="longitud"/>
                            </div>
                        </div>

                        <label for="idRegion">Seleccione su región: </label>
                        <select name="idRegion" id="idRegion" class="form-control">
                            <?php
                            $campusRegiones = $campusRegionBussiness->getAllTbUniversidadCampusRegion();
                            if ($campusRegiones != null) {
                                foreach ($campusRegiones as $campusRegion) {
                                    echo '<option value="' . htmlspecialchars($campusRegion->getTbUniversidadCampusRegionId()) . '"> ' . htmlspecialchars($campusRegion->getTbUniversidadCampusRegionNombre()) . '</option>';
                                }
                            }
                            ?>
                        </select><br>

                        <label for="colectivos">Seleccione los colectivos: </label>
                        <select name="colectivos[]" id="colectivos" multiple class="form-control">
                            <?php
                            $campusColectivos = $campusColectivoBussiness->getAllTbUniversidadCampusColectivo();
                            if ($campusColectivos != null) {
                                foreach ($campusColectivos as $campusColectivo) {
                                    echo '<option value="' . htmlspecialchars($campusColectivo->getTbUniversidadCampusColectivoId()) . '">' . htmlspecialchars($campusColectivo->getTbUniversidadCampusColectivoNombre()) . '</option>';
                                }
                            }
                            ?>
                        </select><br>

                        <label for="idEspecializacion">Seleccione su especialización: </label>
                        <select name="idEspecializacion" id="idEspecializacion" class="form-control">
                            <?php
                            $campusEspecializaciones = $campusEspecializacionBussiness->getAllTbUniversidadCampusEspecializacion();
                            if ($campusEspecializaciones != null) {
                                foreach ($campusEspecializaciones as $campusEspecializacion) {
                                    echo '<option value="' . htmlspecialchars($campusEspecializacion->getTbUniversidadCampusEspecializacionId()) . '">' . htmlspecialchars($campusEspecializacion->getTbUniversidadCampusEspecializacionNombre()) . '</option>';
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
                <h3>Campus registrados</h3>
            </div>

            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Dirección</th>
                        <th>Región</th>
                        <th>Especialización</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $campus = $campusBussiness->getAllTbCampus();
                    $mensajeActualizar = "¿Desea actualizar este campus?";
                    $mensajeEliminar = "¿Desea eliminar este campus?";
                    if ($campus != null) {
                        foreach ($campus as $camp) {
                            echo '<tr>';
                            echo '<form method="post" enctype="multipart/form-data" action="../action/campusAction.php">';
                            echo '<input type="hidden" name="idCampus" value="' . htmlspecialchars($camp->getTbCampusId()) . '">';
                            echo '<input type="hidden" name="idUniversidad" value="' . htmlspecialchars($camp->getTbCampusUniversidadId()) . '">';
                            echo '<td>' . htmlspecialchars($camp->getTbCampusId()) . '</td>';
                            echo '<td><input type="text" name="nombre" id="nombre" value="' . htmlspecialchars($camp->getTbCampusNombre()) . '" class="form-control" /></td>';
                            echo '<td><input type="text" name="direccion" id="direccion" value="' . htmlspecialchars($camp->getTbCampusDireccion()) . '" class="form-control" /></td>';

                            // Región select box
                            echo '<td>';
                            echo '<select name="region" class="form-control">';
                            $campusRegiones = $campusRegionBussiness->getAllTbUniversidadCampusRegion();
                            foreach ($campusRegiones as $campusRegion) {
                                $selected = ($campusRegion->getTbUniversidadCampusRegionId() == $camp->getTbCampusRegionId()) ? 'selected' : '';
                                echo '<option value="' . htmlspecialchars($campusRegion->getTbUniversidadCampusRegionId()) . '" ' . $selected . '>' . htmlspecialchars($campusRegion->getTbUniversidadCampusRegionNombre()) . '</option>';
                            }
                            echo '</select>';
                            echo '</td>';

                            // Especialización select box
                            echo '<td>';
                            echo '<select name="especializacion" class="form-control">';
                            $campusEspecializaciones = $campusEspecializacionBussiness->getAllTbUniversidadCampusEspecializacion();
                            foreach ($campusEspecializaciones as $campusEspecializacion) {
                                $selected = ($campusEspecializacion->getTbUniversidadCampusEspecializacionId() == $camp->getTbCampusEspecializacionId()) ? 'selected' : '';
                                echo '<option value="' . htmlspecialchars($campusEspecializacion->getTbUniversidadCampusEspecializacionId()) . '" ' . $selected . '>' . htmlspecialchars($campusEspecializacion->getTbUniversidadCampusEspecializacionNombre()) . '</option>';
                            }
                            echo '</select>';
                            echo '</td>';

                            // Acciones
                            echo '<td>';
                            echo '<button type="submit" name="update" onclick="return actionConfirmation(\'' . $mensajeActualizar . '\')" class="btn btn-primary">Actualizar</button>';
                            echo ' <button type="submit" name="delete" onclick="return actionConfirmation(\'' . $mensajeEliminar . '\')" class="btn btn-danger">Eliminar</button>';
                            echo '</td>';

                            echo '</form>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="6" class="text-center">No hay campus registrados</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </div>
</body>

</html>