<?php

include_once 'data.php';
include '../domain/campus.php';

class CampusData extends Data
{

    public function insertTbCampus($campus)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        // Consulta para obtener el ID máximo
        $queryGetLastId = "SELECT MAX(tbuniversidadcampusid) AS max_id FROM tbcampus";
        $result = mysqli_query($conn, $queryGetLastId);

        if ($row = mysqli_fetch_assoc($result)) {
            $maxId = $row['max_id'];
            $nextId = ($maxId !== null) ? (int)$maxId + 1 : 1;
        } else {
            $nextId = 1;
        }

        $universidadId = mysqli_real_escape_string($conn, $campus->getTbCampusUniversidadId());
        $nombre = mysqli_real_escape_string($conn, $campus->getTbCampusNombre());
        $direccion = mysqli_real_escape_string($conn, $campus->getTbCampusDireccion());
        $estado = 1;

        // Consulta para insertar un nuevo registro
        $queryInsert = "INSERT INTO tbcampus (tbuniversidadcampusid, tbuniversidadid, tbuniversidadcampusnombre, tbuniversidadcampusdireccion, tbuniversidadcampusestado) 
                        VALUES ($nextId, '$universidadId', '$nombre','$direccion', $estado)";

        $resultInsert = mysqli_query($conn, $queryInsert);
        mysqli_close($conn);

        return $resultInsert;
    }

    public function updateTbCampus($campus)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $id = intval($campus->getTbCampusId()); // Asegúrate de que $id sea un entero
        $nombre = $campus->getTbCampusNombre();
        $direccion = $campus->getTbCampusDireccion();

        $queryUpdate = "UPDATE tbcampus SET tbuniversidadcampusnombre='$nombre', tbuniversidadcampusdireccion='$direccion' WHERE tbuniversidadcampusid=$id;";

        $result = mysqli_query($conn, $queryUpdate);
        mysqli_close($conn);

        return $result;
    }

    public function deleteTbCampus($campusId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryDelete = "UPDATE tbcampus SET tbuniversidadcampusestado = '0' WHERE tbuniversidadcampusid=$campusId;";
        $result = mysqli_query($conn, $queryDelete);
        mysqli_close($conn);

        return $result;
    }

    public function deleteForeverTbCampus($campusId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryDelete = "DELETE FROM tbcampus WHERE tbuniversidadcampusid=" . $campusId . ";";
        $result = mysqli_query($conn, $queryDelete);
        mysqli_close($conn);

        return $result;
    }

    public function getAllTbCampus()
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $querySelect = "SELECT * FROM tbcampus WHERE tbuniversidadcampusestado = 1;";
        $result = mysqli_query($conn, $querySelect);
        mysqli_close($conn);

        $campus = [];
        while ($row = mysqli_fetch_array($result)) {
            $campusActual = new Campus($row['tbuniversidadcampusid'], $row['tbuniversidadid'], $row['tbuniversidadcampusnombre'], $row['tbuniversidadcampusdireccion'], $row['tbuniversidadcampusestado']);
            array_push($campus, $campusActual);
        }

        return $campus;
    }

    public function getAllTbCampusByUniversidad($idU)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $querySelect = "SELECT * FROM tbcampus WHERE tbuniversidadcampusestado = 1 AND tbuniversidadid =" . $idU . ";";

        $result = mysqli_query($conn, $querySelect);

        mysqli_close($conn);

        $mensajeActualizar = "¿Desea actualizar este campus?";
        $mensajeEliminar = "¿Desea eliminar este campus?";

        while ($row = mysqli_fetch_array($result)) {
            echo '<tr>';
            echo '<form method="post" enctype="multipart/form-data" action="../bussiness/campusAction.php" class="campus-form">';
            echo '<input type="hidden" name="idCampus" value="' . $row['tbuniversidadcampusid'] . '">';
            echo '<input type="hidden" name="idUniversidad" value="' . $row['tbuniversidadid'] . '">';
            echo '<td>' . $row['tbuniversidadcampusid'] . '</td>';
            echo '<td><input type="text" name="nombre" value="' . htmlspecialchars($row['tbuniversidadcampusnombre']) . '" class="form-control" /></td>';
            echo '<td><input type="text" name="direccion" value="' . htmlspecialchars($row['tbuniversidadcampusdireccion']) . '" class="form-control" /></td>';
            echo '<td>';
            echo "<button type='submit' class='btn btn-warning me-2' name='update' id='update' onclick='return actionConfirmation(\"$mensajeActualizar\")' >Actualizar</button>";
            echo "<button type='submit' class='btn btn-danger' id='delete' name='delete' onclick='return actionConfirmation(\"$mensajeEliminar\")'>Eliminar</button>";
            echo '</td>';
            echo '</form>';
            echo '</tr>';
        }
    }

    public function getAllDeletedTbCampus()
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $querySelect = "SELECT * FROM tbcampus;";
        $result = mysqli_query($conn, $querySelect);
        mysqli_close($conn);

        $campus = [];
        while ($row = mysqli_fetch_array($result)) {
            $campusActual = new Campus($row['tbuniversidadcampusid'], $row['tbuniversidadid'], $row['tbuniversidadcampusnombre'], $row['tbuniversidadcampusdireccion'], $row['tbuniversidadcampusestado']);
            array_push($campus, $campusActual);
        }

        return $campus;
    }

    public function exist($nombre)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $query = "SELECT COUNT(*) as count FROM tbcampus WHERE tbuniversidadcampusnombre = ?";

        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 's', $nombre);

        mysqli_stmt_execute($stmt);

        mysqli_stmt_bind_result($stmt, $count);
        mysqli_stmt_fetch($stmt);

        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        return $count > 0;
    }
}
