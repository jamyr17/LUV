<?php
session_start();
    
if($_SESSION["tipoUsuario"]=="Usuario" || empty($_SESSION["tipoUsuario"])){
    header("location: view/login.php?error=accessDenied");
}

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
        function deleteConfirmation() {
            var response = confirm("¿Desea eliminar este campus?")
            if (response == true) {
                return true
            } else {
                return false
            }
        }

        function updateConfirmation() {
            var response = confirm("¿Desea actualizar los datos de este campus?")
            if (response == true) {
                return true
            } else {
                return false
            }
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
        <section id="form">
            <div class="containter">
                
            <button onclick="window.location.href='../indexView.php';">Volver</button>

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

            <label for="idUniversidad">Universidad:</label>
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
                            echo '<button type="submit" class="btn btn-warning me-2" name="update" id="update" onclick="return updateConfirmation()" >Actualizar</button>';
                            echo '<button type="submit" class="btn btn-danger" name="delete" id="delete" onclick="return deleteConfirmation()" >Eliminar</button>';
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
                contentRestrictions: {country: 'cr'}
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