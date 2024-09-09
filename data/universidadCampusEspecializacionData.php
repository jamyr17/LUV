<?php

include_once 'data.php';
include '../domain/universidadCampusEspecializacionDomain.php';

class UniversidadCampusEspecializacionData extends Data
{

    public function insertTbUniversidadCampusEspecializacion($universidadCampusEspecializacion)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        // Consulta para obtener el ID máximo
        $queryGetLastId = "SELECT MAX(tbuniversidadcampusespecializacionid) AS max_id FROM tbuniversidadcampusespecializacion";
        $result = mysqli_query($conn, $queryGetLastId);

        if ($row = mysqli_fetch_assoc($result)) {
            // Obtén el ID máximo o establece 0 si la tabla está vacía
            $maxId = $row['max_id'];
            $nextId = ($maxId !== null) ? (int)$maxId + 1 : 1;
        } else {
            // Si no se obtiene resultado, asegúrate de manejar el error adecuadamente
            $nextId = 1;
        }

        $nombre = mysqli_real_escape_string($conn, $universidadCampusEspecializacion->getTbUniversidadCampusEspecializacionNombre());
        $descripcion = mysqli_real_escape_string($conn, $universidadCampusEspecializacion->getTbUniversidadCampusEspecializacionDescripcion());
        $estado = 1;

        // Consulta para insertar un nuevo registro
        $queryInsert = "INSERT INTO tbuniversidadcampusespecializacion (tbuniversidadcampusespecializacionid, tbuniversidadcampusespecializacionnombre, tbuniversidadcampusespecializaciondescripcion, tbuniversidadcampusespecializacionestado) 
                        VALUES ($nextId, '$nombre', '$descripcion', $estado)";

        $resultInsert = mysqli_query($conn, $queryInsert);
        mysqli_close($conn);

        return $resultInsert;
    }

    public function updateTbUniversidadCampusEspecializacion($universidadCampusEspecializacion)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $id = intval($universidadCampusEspecializacion->getTbUniversidadCampusEspecializacionId()); // Asegúrate de que $id sea un entero
        $nombre = mysqli_real_escape_string($conn, $universidadCampusEspecializacion->getTbUniversidadCampusEspecializacionNombre());
        $descripcion = mysqli_real_escape_string($conn, $universidadCampusEspecializacion->getTbUniversidadCampusEspecializacionDescripcion());

        $queryUpdate = "UPDATE tbuniversidadcampusespecializacion SET tbuniversidadcampusespecializacionnombre='$nombre', tbuniversidadcampusespecializaciondescripcion='$descripcion' WHERE tbuniversidadcampusespecializacionid=$id;";

        $result = mysqli_query($conn, $queryUpdate);
        mysqli_close($conn);

        return $result;
    }

    public function deleteTbUniversidadCampusEspecializacion($universidadCampusEspecializacionId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryDelete = "UPDATE tbuniversidadcampusespecializacion SET tbuniversidadcampusespecializacionestado = '0' WHERE tbuniversidadcampusespecializacionid=$universidadCampusEspecializacionId;";
        $result = mysqli_query($conn, $queryDelete);
        mysqli_close($conn);

        return $result;
    }

    public function deleteForeverTbUniversidadCampusEspecializacion($universidadCampusEspecializacionId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryDelete = "DELETE FROM tbuniversidadcampusespecializacion WHERE tbuniversidadcampusespecializacionid=$universidadCampusEspecializacionId;";
        $result = mysqli_query($conn, $queryDelete);
        mysqli_close($conn);

        return $result;
    }

    public function getAllTbUniversidadCampusEspecializacion()
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $querySelect = "SELECT * FROM tbuniversidadcampusespecializacion WHERE tbuniversidadcampusespecializacionestado = 1;";
        $result = mysqli_query($conn, $querySelect);
        $universidadCampusEspecializaciones = [];
        while ($row = mysqli_fetch_array($result)) {
            $universidadCampusEspecializacionActual = new universidadCampusEspecializacion($row['tbuniversidadcampusespecializacionid'], $row['tbuniversidadcampusespecializacionnombre'], $row['tbuniversidadcampusespecializaciondescripcion'], $row['tbuniversidadcampusespecializacionestado']);
            array_push($universidadCampusEspecializaciones, $universidadCampusEspecializacionActual);
        }
        mysqli_close($conn);

        return $universidadCampusEspecializaciones;
    }
/*
    public function getAllDeletedTbUniversidadCampusEspecializacion()
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $querySelect = "SELECT * FROM tbuniversidadcampusespecializacion WHERE tbuniversidadcampusespecializacionestado = 0;";
        $result = mysqli_query($conn, $querySelect);
        $universidadCampusEspecializaciones = [];
        while ($row = mysqli_fetch_array($result)) {
            $universidadCampusEspecializacionActual = new universidadCampusEspecializacion($row['tbuniversidadcampusespecializacionid'], $row['tbuniversidadcampusespecializacionnombre'], $row['tbuniversidadcampusespecializaciondescripcion'], $row['tbuniversidadcampusespecializacionestado']);
            array_push($universidadCampusEspecializaciones, $universidadCampusEspecializacionActual);
        }
        mysqli_close($conn);

        return $universidadCampusEspecializaciones;
    }
*/  
    public function getAllDeletedTbUniversidadCampusEspecializacion() {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');
        $query = "SELECT * FROM tbuniversidadcampusespecializacion WHERE tbuniversidadcampusespecializacionestado = 0;";
        $result = mysqli_query($conn, $query);
        $universidadCampusEspecializaciones = [];
        while ($row = mysqli_fetch_array($result)) {
            $universidadCampusEspecializacionActual = new universidadCampusEspecializacion($row['tbuniversidadcampusespecializacionid'], $row['tbuniversidadcampusespecializacionnombre'], $row['tbuniversidadcampusespecializaciondescripcion'], $row['tbuniversidadcampusespecializacionestado']);
            array_push($universidadCampusEspecializaciones, $universidadCampusEspecializacionActual);
        }
        return $universidadCampusEspecializaciones;
    }

    public function restoreTbCampusEspecializacion($universidadCampusEspecializacionId) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');
        $universidadCampusEspecializacionId = mysqli_real_escape_string($conn, $universidadCampusEspecializacionId);
        $query = "UPDATE tbuniversidadcampusespecializacion SET tbuniversidadcampusespecializacionestado = 1 WHERE tbuniversidadcampusespecializacionid = '$universidadCampusEspecializacionId';";
        $result = mysqli_query($conn, $query);
        return $result;
    }

    public function exist($nombre)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $query = "SELECT COUNT(*) as count FROM tbuniversidadcampusespecializacion WHERE tbuniversidadcampusespecializacionnombre = ?";
        
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 's', $nombre);
        
        mysqli_stmt_execute($stmt);
        
        mysqli_stmt_bind_result($stmt, $count);
        mysqli_stmt_fetch($stmt);
        
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        
        return $count > 0;
    }

    public function nameExists($nombre, $excludeId = null)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $query = "SELECT COUNT(*) as count FROM tbuniversidadcampusespecializacion WHERE tbuniversidadcampusespecializacionnombre = ? AND tbuniversidadcampusespecializacionid != ?";
        
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'si', $nombre, $excludeId);
        
        mysqli_stmt_execute($stmt);
        
        mysqli_stmt_bind_result($stmt, $count);
        mysqli_stmt_fetch($stmt);
        
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        
        return $count > 0;
    }
}
?>
