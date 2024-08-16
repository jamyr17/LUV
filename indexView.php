<?php
    session_start();
    
    if($_SESSION["tipoUsuario"]=="Usuario" || empty($_SESSION["tipoUsuario"])){
        header("location: view/login.php?error=accessDenied");
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LUV</title>

</head>

<body>
    <div id="cruds">
        <h3>LUV</h3>  
        <h3>Index</h3>  
        <table id="tabla">
            <th>CRUD</th>
            <tr>
                <td>
                    <a href="view/universidadView.php">Universidades</a>
                </td>
            </tr>
            <tr>
                <td>
                    <a href="view/campusView.php">Campus</a>
                    <a href="view/areaConocimientoView.php">Área de Conocimiento</a>
                </td> 
            </tr>
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
        </table>
    </div>
</body>

</html>
