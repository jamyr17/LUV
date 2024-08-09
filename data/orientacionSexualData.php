<?php

include_once 'data.php';
include '../domain/orientacionSexual.php';

class OrientacionSexualData extends Data
{

    public function insertTbOrientacionSexual($orientacionSexual)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        // Consulta para obtener el ID máximo
        $queryGetLastId = "SELECT MAX(tborientacionsexualid) AS max_id FROM tborientacionsexual";
        $result = mysqli_query($conn, $queryGetLastId);

        if ($row = mysqli_fetch_assoc($result)) {
            // Obtén el ID máximo o establece 0 si la tabla está vacía
            $maxId = $row['max_id'];
            $nextId = ($maxId !== null) ? (int)$maxId + 1 : 1;
        } else {
            // Si no se obtiene resultado, asegúrate de manejar el error adecuadamente
            $nextId = 1;
        }

        $nombre = mysqli_real_escape_string($conn, $orientacionSexual->getTbOrientacionSexualNombre());
        $descripcion = mysqli_real_escape_string($conn, $orientacionSexual->getTbOrientacionSexualDescripcion());
        $estado = 1;

        // Consulta para insertar un nuevo registro
        $queryInsert = "INSERT INTO tborientacionsexual (tborientacionsexualid, tborientacionsexualnombre, tborientacionsexualdescripcion, tborientacionsexualestado) 
                        VALUES ($nextId, '$nombre', '$descripcion', $estado)";

        $resultInsert = mysqli_query($conn, $queryInsert);
        mysqli_close($conn);

        return $resultInsert;
    }

    public function updateTbOrientacionSexual($orientacionSexual)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $id = intval($orientacionSexual->getTbOrientacionSexualId()); // Asegúrate de que $id sea un entero
        $nombre = $orientacionSexual->getTbOrientacionSexualNombre();
        $descripcion = $orientacionSexual->getTbOrientacionSexualDescripcion();

        $queryUpdate = "UPDATE tborientacionsexual SET tborientacionsexualnombre='$nombre', tborientacionsexualdescripcion='$descripcion' WHERE tborientacionsexualid=$id;";

        $result = mysqli_query($conn, $queryUpdate);
        mysqli_close($conn);

        return $result;
    }

    public function deleteTbOrientacionSexual($orientacionSexualId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryDelete = "UPDATE tborientacionsexual SET tborientacionsexualestado = '0' WHERE tborientacionsexualid=$orientacionSexualId;";
        $result = mysqli_query($conn, $queryDelete);
        mysqli_close($conn);

        return $result;
    }

    public function deleteForeverTbOrientacionSexual($orientacionSexualId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryDelete = "DELETE FROM tborientacionsexual WHERE tborientacionsexualid=" . $orientacionSexualId . ";";
        $result = mysqli_query($conn, $queryDelete);
        mysqli_close($conn);

        return $result;
    }

    public function getAllTbOrientacionSexual()
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $querySelect = "SELECT * FROM tborientacionsexual WHERE tborientacionsexualestado = 1;";
        $result = mysqli_query($conn, $querySelect);
        mysqli_close($conn);

        $orientacionesSexuales = [];
        while ($row = mysqli_fetch_array($result)) {
            $orientacionSexualActual = new OrientacionSexual($row['tborientacionsexualid'], $row['tborientacionsexualnombre'], $row['tborientacionsexualdescripcion'], $row['tborientacionsexualestado']);
            array_push($orientacionesSexuales, $orientacionSexualActual);
        }

        return $orientacionesSexuales;
    }

    public function getAllDeletedTbOrientacionSexual()
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $querySelect = "SELECT * FROM tborientacionsexual;";
        $result = mysqli_query($conn, $querySelect);
        mysqli_close($conn);

        $orientacionesSexuales = [];
        while ($row = mysqli_fetch_array($result)) {
            $orientacionSexualActual = new OrientacionSexual($row['tborientacionsexualid'], $row['tborientacionsexualnombre'], $row['tborientacionsexualdescripcion'], $row['tborientacionsexualestado']);
            array_push($orientacionesSexuales, $orientacionSexualActual);
        }

        return $orientacionesSexuales;
    }

    public function exist($nombre)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $query = "SELECT COUNT(*) as count FROM tborientacionsexual WHERE tborientacionsexualnombre = ?";
        
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
