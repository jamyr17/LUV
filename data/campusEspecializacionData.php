<?php

include_once 'data.php';
include '../domain/campusEspecializacion.php';

class CampusEspecializacionData extends Data
{

    public function insertTbCampusEspecializacion($campusEspecializacion)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        // Consulta para obtener el ID máximo
        $queryGetLastId = "SELECT MAX(tbcampusespecializacionid) AS max_id FROM tbcampusespecializacion";
        $result = mysqli_query($conn, $queryGetLastId);

        if ($row = mysqli_fetch_assoc($result)) {
            // Obtén el ID máximo o establece 0 si la tabla está vacía
            $maxId = $row['max_id'];
            $nextId = ($maxId !== null) ? (int)$maxId + 1 : 1;
        } else {
            // Si no se obtiene resultado, asegúrate de manejar el error adecuadamente
            $nextId = 1;
        }

        $nombre = mysqli_real_escape_string($conn, $campusEspecializacion->getTbCampusEspecializacionNombre());
        $descripcion = mysqli_real_escape_string($conn, $campusEspecializacion->getTbCampusEspecializacionDescripcion());
        $estado = 1;

        // Consulta para insertar un nuevo registro
        $queryInsert = "INSERT INTO tbcampusespecializacion (tbcampusespecializacionid, tbcampusespecializacionnombre, tbcampusespecializaciondescripcion, tbcampusespecializacionestado) 
                        VALUES ($nextId, '$nombre', '$descripcion', $estado)";

        $resultInsert = mysqli_query($conn, $queryInsert);
        mysqli_close($conn);

        return $resultInsert;
    }

    public function updateTbCampusEspecializacion($campusEspecializacion)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $id = intval($campusEspecializacion->getTbCampusEspecializacionId()); // Asegúrate de que $id sea un entero
        $nombre = mysqli_real_escape_string($conn, $campusEspecializacion->getTbCampusEspecializacionNombre());
        $descripcion = mysqli_real_escape_string($conn, $campusEspecializacion->getTbCampusEspecializacionDescripcion());

        $queryUpdate = "UPDATE tbcampusespecializacion SET tbcampusespecializacionnombre='$nombre', tbcampusespecializaciondescripcion='$descripcion' WHERE tbcampusespecializacionid=$id;";

        $result = mysqli_query($conn, $queryUpdate);
        mysqli_close($conn);

        return $result;
    }

    public function deleteTbCampusEspecializacion($campusEspecializacionId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryDelete = "UPDATE tbcampusespecializacion SET tbcampusespecializacionestado = '0' WHERE tbcampusespecializacionid=$campusEspecializacionId;";
        $result = mysqli_query($conn, $queryDelete);
        mysqli_close($conn);

        return $result;
    }

    public function deleteForeverTbCampusEspecializacion($campusEspecializacionId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryDelete = "DELETE FROM tbcampusespecializacion WHERE tbcampusespecializacionid=$campusEspecializacionId;";
        $result = mysqli_query($conn, $queryDelete);
        mysqli_close($conn);

        return $result;
    }

    public function getAllTbCampusEspecializacion()
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $querySelect = "SELECT * FROM tbcampusespecializacion WHERE tbcampusespecializacionestado = 1;";
        $result = mysqli_query($conn, $querySelect);
        $campusEspecializaciones = [];
        while ($row = mysqli_fetch_array($result)) {
            $campusEspecializacionActual = new CampusEspecializacion($row['tbcampusespecializacionid'], $row['tbcampusespecializacionnombre'], $row['tbcampusespecializaciondescripcion'], $row['tbcampusespecializacionestado']);
            array_push($campusEspecializaciones, $campusEspecializacionActual);
        }
        mysqli_close($conn);

        return $campusEspecializaciones;
    }

    public function getAllDeletedTbCampusEspecializacion()
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $querySelect = "SELECT * FROM tbcampusespecializacion WHERE tbcampusespecializacionestado = 0;";
        $result = mysqli_query($conn, $querySelect);
        $campusEspecializaciones = [];
        while ($row = mysqli_fetch_array($result)) {
            $campusEspecializacionActual = new Genero($row['tbcampusespecializacionid'], $row['tbcampusespecializacionnombre'], $row['tbcampusespecializaciondescripcion'], $row['tbcampusespecializacionestado']);
            array_push($campusEspecializaciones, $campusEspecializacionActual);
        }
        mysqli_close($conn);

        return $campusEspecializaciones;
    }

    public function exist($nombre)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $query = "SELECT COUNT(*) as count FROM tbcampusespecializacion WHERE tbcampusespecializacionnombre = ?";
        
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
