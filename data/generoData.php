<?php

include_once 'data.php';
include '../domain/generoDomain.php';

class GeneroData extends Data
{

    public function insertTbGenero($genero)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        // Consulta para obtener el ID máximo
        $queryGetLastId = "SELECT MAX(tbgeneroid) AS max_id FROM tbgenero";
        $result = mysqli_query($conn, $queryGetLastId);

        if ($row = mysqli_fetch_assoc($result)) {
            // Obtén el ID máximo o establece 0 si la tabla está vacía
            $maxId = $row['max_id'];
            $nextId = ($maxId !== null) ? (int)$maxId + 1 : 1;
        } else {
            // Si no se obtiene resultado, asegúrate de manejar el error adecuadamente
            $nextId = 1;
        }

        $nombre = mysqli_real_escape_string($conn, $genero->getTbGeneroNombre());
        $descripcion = mysqli_real_escape_string($conn, $genero->getTbGeneroDescripcion());
        $estado = 1;

        // Consulta para insertar un nuevo registro
        $queryInsert = "INSERT INTO tbgenero (tbgeneroid, tbgeneronombre, tbgenerodescripcion, tbgeneroestado) 
                        VALUES ($nextId, '$nombre', '$descripcion', $estado)";

        $resultInsert = mysqli_query($conn, $queryInsert);
        mysqli_close($conn);

        return $resultInsert;
    }

    public function updateTbGenero($genero)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $id = intval($genero->getTbGeneroId()); // Asegúrate de que $id sea un entero
        $nombre = mysqli_real_escape_string($conn, $genero->getTbGeneroNombre());
        $descripcion = mysqli_real_escape_string($conn, $genero->getTbGeneroDescripcion());

        $queryUpdate = "UPDATE tbgenero SET tbgeneronombre='$nombre', tbgenerodescripcion='$descripcion' WHERE tbgeneroid=$id;";

        $result = mysqli_query($conn, $queryUpdate);
        mysqli_close($conn);

        return $result;
    }

    public function deleteTbGenero($generoId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryDelete = "UPDATE tbgenero SET tbgeneroestado = '0' WHERE tbgeneroid=$generoId;";
        $result = mysqli_query($conn, $queryDelete);
        mysqli_close($conn);

        return $result;
    }

    public function deleteForeverTbGenero($generoId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryDelete = "DELETE FROM tbgenero WHERE tbgeneroid=$generoId;";
        $result = mysqli_query($conn, $queryDelete);
        mysqli_close($conn);

        return $result;
    }

    public function getAllTbGenero()
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $querySelect = "SELECT * FROM tbgenero WHERE tbgeneroestado = 1;";
        $result = mysqli_query($conn, $querySelect);
        $generos = [];
        while ($row = mysqli_fetch_array($result)) {
            $generoActual = new Genero($row['tbgeneroid'], $row['tbgeneronombre'], $row['tbgenerodescripcion'], $row['tbgeneroestado']);
            array_push($generos, $generoActual);
        }
        mysqli_close($conn);

        return $generos;
    }
/*
    public function getAllDeletedTbGenero()
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $querySelect = "SELECT * FROM tbgenero WHERE tbgeneroestado = 0;";
        $result = mysqli_query($conn, $querySelect);
        $generos = [];
        while ($row = mysqli_fetch_array($result)) {
            $generoActual = new Genero($row['tbgeneroid'], $row['tbgeneronombre'], $row['tbgenerodescripcion'], $row['tbgeneroestado']);
            array_push($generos, $generoActual);
        }
        mysqli_close($conn);

        return $generos;
    }
*/  
    public function getAllDeletedTbGenero() {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');
        $query = "SELECT * FROM tbgenero WHERE tbgeneroestado = 0;";
        $result = mysqli_query($conn, $query);
        $generos = [];
        while ($row = mysqli_fetch_array($result)) {
            $generoActual = new Genero($row['tbgeneroid'], $row['tbgeneronombre'], $row['tbgenerodescripcion'], $row['tbgeneroestado']);
            array_push($generos, $generoActual);
        }
        return $generos;
    }

    public function restoreTbGenero($generoId) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');
        $generoId = mysqli_real_escape_string($conn, $generoId);
        $query = "UPDATE tbgenero SET tbgeneroestado = 1 WHERE tbgeneroid = '$generoId';";
        $result = mysqli_query($conn, $query);
        return $result;
    }

    public function exist($nombre)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $query = "SELECT COUNT(*) as count FROM tbgenero WHERE tbgeneronombre = ?";
        
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 's', $nombre);
        
        mysqli_stmt_execute($stmt);
        
        mysqli_stmt_bind_result($stmt, $count);
        mysqli_stmt_fetch($stmt);
        
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        
        return $count > 0;
    }

    public function insertRequestTbGenero($genero)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        // Consulta para obtener el ID máximo
        $queryGetLastId = "SELECT MAX(tbsolicitudgeneroid) AS max_id FROM tbsolicitudgenero";
        $result = mysqli_query($conn, $queryGetLastId);

        if ($row = mysqli_fetch_assoc($result)) {
            // Obtén el ID máximo o establece 0 si la tabla está vacía
            $maxId = $row['max_id'];
            $nextId = ($maxId !== null) ? (int)$maxId + 1 : 1;
        } else {
            // Si no se obtiene resultado, asegúrate de manejar el error adecuadamente
            $nextId = 1;
        }

        $nombre = mysqli_real_escape_string($conn, $genero->getTbGeneroNombre());
        $estado = 0;

        // Consulta para insertar un nuevo registro
        $queryInsert = "INSERT INTO tbsolicitudgenero (tbsolicitudgeneroid, tbsolicitudgeneronombre, tbsolicitudgeneroestado) 
                        VALUES ($nextId, '$nombre', $estado)";

        $resultInsert = mysqli_query($conn, $queryInsert);
        mysqli_close($conn);

        return $resultInsert;
    }

    

    public function nameExists($nombre, $excludeId = null)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $query = "SELECT COUNT(*) as count FROM tbgenero WHERE tbgeneronombre = ? AND tbgeneroid != ?";
        
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'si', $nombre, $idGenero);
        
        mysqli_stmt_execute($stmt);
        
        mysqli_stmt_bind_result($stmt, $count);
        mysqli_stmt_fetch($stmt);
        
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        
        return $count > 0;
    }

}

