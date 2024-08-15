<<<<<<< Updated upstream
=======
<?php
    session_start();
    
    if($_SESSION["tipoUsuario"]=="Usuario" || empty($_SESSION["tipoUsuario"])){
        header("location: view/login.php?error=accessDenied");
    }
?>

>>>>>>> Stashed changes
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LUV</title>

</head>

<body>
    <div id="cruds">
<<<<<<< Updated upstream
        <h3>LUV</h3>  
=======
        <h3>Index</h3>  
>>>>>>> Stashed changes
        <table id="tabla">
            <th>CRUD</th>
            <tr>
                <td>
                    <a href="view/universidadView.php">Universidades</a>
<<<<<<< Updated upstream
                    <a href="view/campusView.php">Campus</a>
                    <a href="view/areaConocimientoView.php">Área de Conocimiento</a>
                    <a href="view/orientacionSexualView.php">Orientación Sexual</a>
                    <a href="view/generoView.php">Género</a>
                </td>
            </tr>
        </table>
    </div>
</body>
=======
<<<<<<< Updated upstream:ProgramacionParadigmas/indexView.php
                </td>
=======
                </td> 
            </tr>
            <tr>
                <td>
                    <a href="view/campusView.php">Campus</a>
                </td> 
            </tr>
            <tr>
                <td>
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
>>>>>>> Stashed changes:indexView.php
            </tr>
        </table>
    </div>
</body>

</html>
>>>>>>> Stashed changes
