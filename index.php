<?php
    include_once 'action/sessionAdminAction.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LUV</title>

</head>

<body>
    <div>
        <form method="post" action="action/sessionAdminAction.php">
            <button type="submit" class="btn btn-success" name="logout" id="logout">Cerrar sesión</button>
        </form>
    </div>

    <div id="cruds">
        <h3>Index</h3>  
        <table id="tabla">
            <th>Instituciones</th>
            <tr>
                <td>
                    <a href="view/universidadView.php">Universidad</a>
                </td>
            </tr>
            <tr>
                <td>
                    <a href="view/campusView.php">Campus</a>
                </td> 
            </tr>
            <th>Aspectos de Campus</th>
            <tr>
                <td>
                    <a href="view/universidadCampusColectivoView.php">Campus Colectivo</a>
                </td> 
            </tr>
            <tr>
                <td>
                    <a href="view/universidadCampusRegionView.php">Campus Región</a>
                </td> 
            </tr>
            <tr>
                <td>
                    <a href="view/universidadCampusEspecializacionView.php">Campus Especialización</a>
                </td> 
            </tr>
            <tr>
                <td>
                    <a href="view/areaConocimientoView.php">Área de Conocimiento</a>
                </td> 
            </tr>
            <th>Personalidad</th>
            <tr>
                <td>
                    <a href="view/orientacionSexualView.php">Orientación Sexual</a>
                </td> 
            </tr>
            <tr>
                <td>
                    <a href="view/generoView.php">Género</a>
                </td> 
            </tr>
            <th>Administrativo</th>
            <tr>
                <td>
                    <a href="view/imagenView.php">Imagen</a>
                </td> 
            </tr>
            <tr>
                <td>
                    <a href="view/criterioView.php">Criterio</a>
                </td> 
            </tr>
            <tr>
                <td>
                    <a href="view/valorView.php">Valor</a>
                </td> 
            </tr>
            <th>Participativos</th>
            <tr>
                <td>
                    <a href="view/actividadView.php">Actividades</a>
                </td> 
            </tr>
        </table>
    </div>
</body>

</html>
