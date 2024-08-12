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

        function cargarCampus(idUniversidad) {
            var conexion;

            if (idUniversidad == "") {
                document.getElementById("txtHint").innerHTML();
                return;
            }

            if (window.XMLHttpRequest) {
                conexion = new XMLHttpRequest();
            }

            conexion.onreadystatechange = function() {
                if (conexion.readyState == 4 && conexion.status == 200) {
                    document.getElementById('tbcampus').innerHTML = conexion.responseText;
                }
            }

            conexion.open("GET", "../bussiness/campusAction.php?idU=" + idUniversidad, true);
            conexion.send();
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

            <label for="idU">Universidad:</label>
            <select id="cargarCampus" name="cargarCampus" onclick="cargarCampus(this.value)">
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

            <table id="tbcampus" class="table mt-3">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Dirección</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <form method="post" enctype="multipart/form-data" action="../bussiness/campusAction.php" class="campus-form">
                </form>
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