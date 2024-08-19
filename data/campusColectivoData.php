<?php

include_once 'data.php';
include '../domain/campusColectivo.php';

class CampusColectivoData extends Data
{

    public function insertTbCampusColectivo($campusColectivo)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        // Consulta para obtener el ID máximo
        $queryGetLastId = "SELECT MAX(tbcampuscolectivoid) AS max_id FROM tbcampuscolectivo";
        $result = mysqli_query($conn, $queryGetLastId);

        if ($row = mysqli_fetch_assoc($result)) {
            // Obtén el ID máximo o establece 0 si la tabla está vacía
            $maxId = $row['max_id'];
            $nextId = ($maxId !== null) ? (int)$maxId + 1 : 1;
        } else {
            // Si no se obtiene resultado, asegúrate de manejar el error adecuadamente
            $nextId = 1;
        }

        $nombre = mysqli_real_escape_string($conn, $campusColectivo->getTbCampusColectivoNombre());
        $descripcion = mysqli_real_escape_string($conn, $campusColectivo->getTbCampusColectivoDescripcion());
        $estado = 1;

        // Consulta para insertar un nuevo registro
        $queryInsert = "INSERT INTO tbcampuscolectivo (tbcampuscolectivoid, tbcampuscolectivonombre, tbcampuscolectivodescripcion, tbcampuscolectivoestado) 
                        VALUES ($nextId, '$nombre', '$descripcion', $estado)";

        $resultInsert = mysqli_query($conn, $queryInsert);
        mysqli_close($conn);

        return $resultInsert;
    }

    public function updateTbCampusColectivo($campusColectivo)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $id = intval($campusColectivo->getTbCampusColectivoId()); // Asegúrate de que $id sea un entero
        $nombre = $campusColectivo->getTbCampusColectivoNombre();
        $descripcion = $campusColectivo->getTbCampusColectivoDescripcion();

        $queryUpdate = "UPDATE tbcampuscolectivo SET tbcampuscolectivonombre='$nombre', tbcampuscolectivodescripcion='$descripcion' WHERE tbcampuscolectivoid=$id;";

        $result = mysqli_query($conn, $queryUpdate);
        mysqli_close($conn);

        return $result;
    }

    public function deleteTbCampusColectivo($campusColectivoId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryDelete = "UPDATE tbcampuscolectivo SET tbcampuscolectivoestado = '0' WHERE tbcampuscolectivoid=$campusColectivoId;";
        $result = mysqli_query($conn, $queryDelete);
        mysqli_close($conn);

        return $result;
    }

    public function deleteForeverTbCampusColectivo($campusColectivoId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryDelete = "DELETE FROM tbcampuscolectivo WHERE tbcampuscolectivoid=" . $campusColectivoId . ";";
        $result = mysqli_query($conn, $queryDelete);
        mysqli_close($conn);

        return $result;
    }

    public function getAllTbCampusColectivo()
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $querySelect = "SELECT * FROM tbcampuscolectivo WHERE tbcampuscolectivoestado = 1;";
        $result = mysqli_query($conn, $querySelect);
        mysqli_close($conn);

        $campusColectivos = [];
        while ($row = mysqli_fetch_array($result)) {
            $campusColectivoActual = new CampusColectivo($row['tbcampuscolectivoid'], $row['tbcampuscolectivonombre'], $row['tbcampuscolectivodescripcion'], $row['tbcampuscolectivoestado']);
            array_push($campusColectivos, $campusColectivoActual);
        }

        return $campusColectivos;
    }

    public function getAllDeletedTbCampusColectivo()
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $querySelect = "SELECT * FROM tbcampuscolectivo;";
        $result = mysqli_query($conn, $querySelect);
        mysqli_close($conn);

        $campusColectivos = [];
        while ($row = mysqli_fetch_array($result)) {
            $campusColectivoActual = new CampusColectivo($row['tbcampuscolectivoid'], $row['tbcampuscolectivonombre'], $row['tbcampuscolectivodescripcion'], $row['tbcampuscolectivoestado']);
            array_push($campusColectivos, $campusColectivoActual);
        }

        return $campusColectivos;
    }

    public function exist($nombre)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $query = "SELECT COUNT(*) as count FROM tbcampuscolectivo WHERE tbcampuscolectivonombre = ?";
        
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

?>
