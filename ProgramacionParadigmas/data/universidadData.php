<?php

include_once 'data.php';
include '../domain/universidad.php';

class UniversidadData extends Data
{

    public function insertTbUniversidad($universidad)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        //query para siguiente id
        $queryGetLastId = "SELECT MAX(tbuniversidadid) AS tbuniversidadid  FROM tbuniversidad";
        $contId = mysqli_query($conn, $queryGetLastId);
        $nextId = 1;

        if ($row = mysqli_fetch_row($contId)) {
            $nextId = ($row['tbuniversidadid'] !== null) ? (int)$row['tbuniversidadid'] + 1 : 1;
        }

        $nombre = mysqli_real_escape_string($conn, $universidad->getTbUniversidadNombre());
        $estado = 1;

        $queryInsert = "INSERT INTO `tbuniversidad` (`tbuniversidadid`, `tbuniversidadnombre`, `tbuniversidadestado`) 
                        VALUES ($nextId, '$nombre', $estado)";

        $result = mysqli_query($conn, $queryInsert);
        mysqli_close($conn);

        return $result;
    }


    public function updateTbUniversidad($universidad)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $id = intval($universidad->getTbUniversidadId()); // Asegúrate de que $id sea un entero
        $nombre = $universidad->getTbUniversidadNombre();

        $queryUpdate = "UPDATE tbuniversidad SET tbuniversidadnombre='$nombre' WHERE tbuniversidadid=$id;";

        $result = mysqli_query($conn, $queryUpdate);
        mysqli_close($conn);

        return $result;
    }

    public function deleteTbUniversidad($universidadId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryDelete = "UPDATE tbuniversidad SET tbuniversidadestado = '0' WHERE tbuniversidadid=$universidadId;";
        $result = mysqli_query($conn, $queryDelete);
        mysqli_close($conn);

        return $result;
    }

    public function deleteForeverTbUniversidad($universidadId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryDelete = "DELETE FROM tbuniversidad WHERE tbuniversidadid=" . $universidadId . ";";
        $result = mysqli_query($conn, $queryDelete);
        mysqli_close($conn);

        return $result;
    }

    public function getAllTbUniversidad()
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $querySelect = "SELECT * FROM tbuniversidad WHERE tbuniversidadestado = 1;";
        $result = mysqli_query($conn, $querySelect);
        mysqli_close($conn);

        $universidades = [];
        while ($row = mysqli_fetch_array($result)) {
            $universidadActual = new Universidad($row['tbuniversidadid'], $row['tbuniversidadnombre'], $row['tbuniversidadestado']);
            array_push($universidades, $universidadActual);
        }

        return $universidades;
    }

    public function getAllDeletedTbUniversidad()
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $querySelect = "SELECT * FROM tbuniversidad;";
        $result = mysqli_query($conn, $querySelect);
        mysqli_close($conn);

        $universidades = [];
        while ($row = mysqli_fetch_array($result)) {
            $universidadActual = new Universidad($row['tbuniversidadid'], $row['tbuniversidadnombre'], $row['tbuniversidadestado']);
            array_push($universidades, $universidadActual);
        }

        return $universidades;
    }

    public function exist($nombre)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $query = "SELECT COUNT(*) as count FROM tbuniversidad WHERE tbuniversidadnombre = ?";
        
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
