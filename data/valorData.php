<?php

include_once 'data.php';
include '../domain/valorDomain.php'; 

class ValorData extends Data
{
    public function insertTbValor($valor)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        // Consulta para obtener el ID máximo
        $queryGetLastId = "SELECT MAX(tbvalorid) AS max_id FROM tbvalor";
        $result = mysqli_query($conn, $queryGetLastId);

        if ($row = mysqli_fetch_assoc($result)) {
            $maxId = $row['max_id'];
            $nextId = ($maxId !== null) ? (int)$maxId + 1 : 1;
        } else {
            $nextId = 1;
        }

        $nombre = mysqli_real_escape_string($conn, $valor->getTbValorNombre());
        $criterioId = mysqli_real_escape_string($conn, $valor->getTbCriterioId());
        $estado = 1;

        // Consulta para insertar un nuevo registro
        $queryInsert = "INSERT INTO tbvalor (tbvalorid, tbvalornombre, tbcriterioid, tbvalorestado) 
                VALUES ($nextId, '$nombre', '$criterioId', '$estado')";

        $resultInsert = mysqli_query($conn, $queryInsert);
        mysqli_close($conn);

        return $resultInsert;
    }

    public function updateTbValor($valor)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $id = intval($valor->getTbValorId());
        $nombre = mysqli_real_escape_string($conn, $valor->getTbValorNombre());
        $idcriterio = mysqli_real_escape_string($conn, $valor->getTbCriterioId());
        $estado = mysqli_real_escape_string($conn, $valor->getTbValorEstado());

        $queryUpdate = "UPDATE tbvalor 
                        SET tbvalornombre='$nombre', tbcriterioid='$idcriterio' , tbvalorestado='$estado' 
                        WHERE tbvalorid=$id";

        $result = mysqli_query($conn, $queryUpdate);
        mysqli_close($conn);

        return $result;
    }

    public function deleteTbValor($valorId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryDelete = "UPDATE tbvalor SET tbvalorestado = '0' WHERE tbvalorid=$valorId";
        $result = mysqli_query($conn, $queryDelete);
        mysqli_close($conn);

        return $result;
    }

    public function deleteForeverTbValor($valorId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryDelete = "DELETE FROM tbvalor WHERE tbvalorid=$valorId";
        $result = mysqli_query($conn, $queryDelete);
        mysqli_close($conn);

        return $result;
    }

    public function getAllTbValor()
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $querySelect = "SELECT * FROM tbvalor WHERE tbvalorestado = 1";
        $result = mysqli_query($conn, $querySelect);
        mysqli_close($conn);

        $valores = [];
        while ($row = mysqli_fetch_array($result)) {
            $valorActual = new Valor(
                $row['tbvalorid'],
                $row['tbvalornombre'],
                $row['tbcriterioid'],
                $row['tbvalorestado']
            );
            array_push($valores, $valorActual);
        }

        return $valores;
    }

    public function getAllTbValorNombres()
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $querySelect = "SELECT tbvalornombre FROM tbvalor WHERE tbvalorestado = 1;";
        $result = mysqli_query($conn, $querySelect);

        if (!$result) {
            // Manejo de errores de consulta
            die('Error en la consulta: ' . mysqli_error($conn));
        }

        $nombres = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $nombres[] = $row['tbvalornombre'];
        }

        mysqli_close($conn);

        return $nombres;
    }

/*
    public function getAllTbValorDeleted()
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $querySelect = "SELECT * FROM tbvalor";
        $result = mysqli_query($conn, $querySelect);
        mysqli_close($conn);

        $valores = [];
        while ($row = mysqli_fetch_array($result)) {
            $valorActual = new Valor(
                $row['tbvalorid'],
                $row['tbvalornombre'],
                $row['tbcriterioid'],
                $row['tbvalorestado']
            );
            array_push($valores, $valorActual);
        }

        return $valores;
    }
*/

    public function getAllDeletedTbValor() {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');
        $query = "SELECT * FROM tbvalor WHERE tbvalorestado = 0;";
        $result = mysqli_query($conn, $query);
        $valores = [];
        while ($row = mysqli_fetch_array($result)) {
            $valorActual = new Valor($row['tbvalorid'], $row['tbvalornombre'], $row['tbcriterioid'], $row['tbvalorestado']);
            array_push($valores, $valorActual);
        }
        return $valores;
    }

    public function restoreTbValor($valorId) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');
        $valorId = mysqli_real_escape_string($conn, $valorId);
        $query = "UPDATE tbvalor SET tbvalorestado = 1 WHERE tbvalorid = '$valorId';";
        $result = mysqli_query($conn, $query);
        return $result;
    }

    public function exist($nombre)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $query = "SELECT COUNT(*) as count FROM tbvalor WHERE tbvalornombre = ?";

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
    
        $query = "SELECT COUNT(*) as count FROM tbvalor WHERE tbvalornombre = ? AND tbvalorid != ?";
        
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'si', $nombre, $idValor);
        
        mysqli_stmt_execute($stmt);
        
        mysqli_stmt_bind_result($stmt, $count);
        mysqli_stmt_fetch($stmt);
        
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        
        return $count > 0;
    }

    public function autocomplete($term) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');
    
        $sql = "SELECT tbvalornombre FROM tbvalor WHERE tbvalornombre LIKE ?";
        $stmt = $conn->prepare($sql);
        $searchTerm = "%$term%";
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $suggestions = [];
        while ($row = $result->fetch_assoc()) {
            $suggestions[] = $row['tbvalornombre'];
        }
    
        $stmt->close();
        $conn->close();
    
        return $suggestions;
    }
}
?>
