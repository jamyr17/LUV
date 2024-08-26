<?php

include_once 'data.php';
include '../domain/universidadCampusColectivo.php';

class universidadCampusColectivoData extends Data
{

    public function insertTbUniversidadCampusColectivo($universidadCampusColectivo)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        // Consulta para obtener el ID máximo
        $queryGetLastId = "SELECT MAX(tbuniversidadcampuscolectivoid) AS max_id FROM tbuniversidadcampuscolectivo";
        $result = mysqli_query($conn, $queryGetLastId);

        if ($row = mysqli_fetch_assoc($result)) {
            // Obtén el ID máximo o establece 0 si la tabla está vacía
            $maxId = $row['max_id'];
            $nextId = ($maxId !== null) ? (int)$maxId + 1 : 1;
        } else {
            // Si no se obtiene resultado, asegúrate de manejar el error adecuadamente
            $nextId = 1;
        }

        $nombre = mysqli_real_escape_string($conn, $universidadCampusColectivo->getTbUniversidadCampusColectivoNombre());
        $descripcion = mysqli_real_escape_string($conn, $universidadCampusColectivo->getTbUniversidadCampusColectivoDescripcion());
        $estado = 1;

        // Consulta para insertar un nuevo registro
        $queryInsert = "INSERT INTO tbuniversidadcampuscolectivo (tbuniversidadcampuscolectivoid, tbuniversidadcampuscolectivonombre, tbuniversidadcampuscolectivodescripcion, tbuniversidadcampuscolectivoestado) 
                        VALUES ($nextId, '$nombre', '$descripcion', $estado)";

        $resultInsert = mysqli_query($conn, $queryInsert);
        mysqli_close($conn);

        return $resultInsert;
    }

    public function updateTbUniversidadCampusColectivo($universidadCampusColectivo)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $id = intval($universidadCampusColectivo->getTbUniversidadCampusColectivoId()); // Asegúrate de que $id sea un entero
        $nombre = $universidadCampusColectivo->getTbUniversidadCampusColectivoNombre();
        $descripcion = $universidadCampusColectivo->getTbUniversidadCampusColectivoDescripcion();

        $queryUpdate = "UPDATE tbuniversidadcampuscolectivo SET tbuniversidadcampuscolectivonombre='$nombre', tbuniversidadcampuscolectivodescripcion='$descripcion' WHERE tbuniversidadcampuscolectivoid=$id;";

        $result = mysqli_query($conn, $queryUpdate);
        mysqli_close($conn);

        return $result;
    }

    public function deleteTbUniversidadCampusColectivo($universidadCampusColectivoId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryDelete = "UPDATE tbuniversidadcampuscolectivo SET tbuniversidadcampuscolectivoestado = '0' WHERE tbuniversidadcampuscolectivoid=$universidadCampusColectivoId;";
        $result = mysqli_query($conn, $queryDelete);
        mysqli_close($conn);

        return $result;
    }

    public function deleteForeverTbUniversidadCampusColectivo($universidadCampusColectivoId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryDelete = "DELETE FROM tbuniversidadcampuscolectivo WHERE tbuniversidadcampuscolectivoid=" . $universidadCampusColectivoId . ";";
        $result = mysqli_query($conn, $queryDelete);
        mysqli_close($conn);

        return $result;
    }

    public function getAllTbUniversidadCampusColectivo()
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $querySelect = "SELECT * FROM tbuniversidadcampuscolectivo WHERE tbuniversidadcampuscolectivoestado = 1;";
        $result = mysqli_query($conn, $querySelect);
        mysqli_close($conn);

        $universidadCampusColectivos = [];
        while ($row = mysqli_fetch_array($result)) {
            $universidadCampusColectivoActual = new universidadCampusColectivo($row['tbuniversidadcampuscolectivoid'], $row['tbuniversidadcampuscolectivonombre'], $row['tbuniversidadcampuscolectivodescripcion'], $row['tbuniversidadcampuscolectivoestado']);
            array_push($universidadCampusColectivos, $universidadCampusColectivoActual);
        }

        return $universidadCampusColectivos;
    }

    public function getAllDeletedTbUniversidadCampusColectivo()
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $querySelect = "SELECT * FROM tbuniversidadcampuscolectivo;";
        $result = mysqli_query($conn, $querySelect);
        mysqli_close($conn);

        $universidadCampusColectivos = [];
        while ($row = mysqli_fetch_array($result)) {
            $universidadCampusColectivoActual = new universidadCampusColectivo($row['tbuniversidadcampuscolectivoid'], $row['tbuniversidadcampuscolectivonombre'], $row['tbuniversidadcampuscolectivodescripcion'], $row['tbuniversidadcampuscolectivoestado']);
            array_push($universidadCampusColectivos, $universidadCampusColectivoActual);
        }

        return $universidadCampusColectivos;
    }

    public function exist($nombre)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $query = "SELECT COUNT(*) as count FROM tbuniversidadcampuscolectivo WHERE tbuniversidadcampuscolectivonombre = ?";
        
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

        $query = "SELECT COUNT(*) as count FROM tbuniversidadcampuscolectivo WHERE tbuniversidadcampuscolectivonombre = ? AND tbuniversidadcampuscolectivoid != ?";
        
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