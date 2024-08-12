<?php
include '../bussiness/universidadBussiness.php';
$universidadBusiness = new UniversidadBusiness();
include '../bussiness/campusBussiness.php';
$campusBusiness = new CampusBusiness();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LUV</title>
    <script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAShWeubkR1_4C7NOevWTEwZsM14px3H74&libraries=places&callback=initMap">
    </script>
    <script>
        function actionConfirmation(mensaje) {
            var response = confirm(mensaje)
            if (response == true) {
                return true
            } else {
                return false
            }
        }

        function showMessage(mensaje) {
            alert(mensaje);
        }
    </script>
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        #direccion {
            width: 70%;
        }
    </style>
</head>

<body>

    <header>
        <nav class="navbar bg-body-tertiary">

        </nav>
    </header>

    <div class="container mt-3">

        <section id="alerts">
            <?php

            if (isset($_GET['error'])) {
                $mensaje = "Ocurrió un error debido a ";
                $mensaje .= match (true) {
                    $_GET['error'] == "emptyField" => "campo(s) vacío(s).",
                    $_GET['error'] == "numberFormat" => "ingreso de valores númericos.",
                    $_GET['error'] == "dbError" => "un problema al procesar la transacción.",
                    $_GET['error'] == "exist" => "que dicho campus ya existe.",
                    default => "un problema inesperado.",
                };
            } else if (isset($_GET['success'])) {
                $mensaje = match (true) {
                    $_GET['success'] == "inserted" => "Campus creado correctamente.",
                    $_GET['success'] == "updated" => "Campus actualizado correctamente.",
                    $_GET['success'] == "deleted" => "Campus eliminado correctamente.",
                    default => "Transacción realizada.",
                };
            }

            if (isset($mensaje)) {
                echo "<script>showMessage('$mensaje')</script>";
            }

            ?>

        </section>
        <section id="form">
            <div class="containter">

                <div class="text-center mb-4">
                    <h3>Agregar un nuevo campus</h3>
                    <p class="text-muted">Complete el formulario para añadir un nuevo campus</p>
                </div>

                <div class="container d-flex justify-content-center">
                    <form method="post" action="../bussiness/campusAction.php" style="width: 50vvw; min-width:300px;">
                        <input type="hidden" name="campus" value="<?php echo htmlspecialchars($idCampus); ?>">

                        <label for="idUniversidad">Universidad:</label>
                        <select id="idUniversidad" name="idUniversidad">
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
                                <input required type="text" name="nombre" id="nombre" class="form-control" placeholder="campus Omar Dengo" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <label for="direccion" class="form-label">Dirección: </label>
                                <input type="text" id="direccion" name="direccion" placeholder="Ingresa el nombre de una sede..." />
                            </div>
                        </div>

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

            <!-- <label for="idUniversidad">Universidad:</label>
            <select id="idU" name="idU">
                <?php
                if ($universidades != null) {
                    foreach ($universidades as $universidad) {
                        $id = htmlspecialchars($universidad->getTbUniversidadId());
                        $nombre = htmlspecialchars($universidad->getTbUniversidadNombre());
                        echo '<option value="' . $id . '">' . $nombre . '</option>';
                    }
                }
                ?>
            </select><br>
            -->
            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Dirección</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    $campus = $campusBusiness->getAllTbCampus(); // Que reciba el id de la universidad
                    $mensajeActualizar = "¿Desea actualizar este campus?";
                    $mensajeEliminar = "¿Desea eliminar este campus?";
                    if ($campus != null) {
                        foreach ($campus as $camp) {
                            echo '<tr>';
                            echo '<form method="post" enctype="multipart/form-data" action="../bussiness/campusAction.php">';
                            echo '<input type="hidden" name="idCampus" value="' . htmlspecialchars($camp->getTbCampusId()) . '">';
                            echo '<input type="hidden" name="idUniversidad" value="' . htmlspecialchars($camp->getTbCampusUniversidadId()) . '">';
                            echo '<td>' . htmlspecialchars($camp->getTbCampusId()) . '</td>';
                            echo '<td><input type="text" name="nombre" id="nombre" value="' . htmlspecialchars($camp->getTbCampusNombre()) . '" class="form-control" /></td>';
                            echo '<td><input type="text" name="direccion" id="direccion" value="' . htmlspecialchars($camp->getTbCampusDireccion()) . '" class="form-control" /></td>';
                            echo '<td>';
                            echo "<button type='submit' class='btn btn-warning me-2' name='update' id='update' onclick='return actionConfirmation(\"$mensajeActualizar\")' >Actualizar</button>";
                            echo "<button type='submit' class='btn btn-danger' name='delete' id='delete' onclick='return actionConfirmation(\"$mensajeEliminar\")'>Eliminar</button>";
                            echo '</td>';
                            echo '</form>';
                            echo '</tr>';
                        }
                    }

                    ?>
                </tbody>
            </table>
        </section>
    </div>

    <script>
        const searchInput = document.querySelector('input[name="direccion"]');


        document.addEventListener('DOMContentLoaded', function() {
            var autocomplete = new google.maps.places.Autocomplete(searchInput, {
                contentRestrictions: {
                    country: 'cr'
                }
            });
            autocomplete.addListener('place_changed', function() {
                var near_place = autocomplete.getPlace();
                console.log(near_place);
            });
        });
    </script>

</body>

<footer>
</footer>

</html>
