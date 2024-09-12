<?php

include_once 'data.php';
include '../domain/criterioDomain.php';

class CriterioData extends Data
{

    public function insertTbCriterio($criterio)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        // Consulta para obtener el ID máximo
        $queryGetLastId = "SELECT MAX(tbcriterioid) AS max_id FROM tbcriterio";
        $result = mysqli_query($conn, $queryGetLastId);

        if ($row = mysqli_fetch_assoc($result)) {
            // Obtén el ID máximo o establece 0 si la tabla está vacía
            $maxId = $row['max_id'];
            $nextId = ($maxId !== null) ? (int)$maxId + 1 : 1;
        } else {
            // Si no se obtiene resultado, asegúrate de manejar el error adecuadamente
            $nextId = 1;
        }

        $nombre = mysqli_real_escape_string($conn, $criterio->getTbCriterioNombre());
        $estado = 1;

        // Consulta para insertar un nuevo registro
        $queryInsert = "INSERT INTO tbcriterio (tbcriterioid, tbcriterionombre, tbcriterioestado) 
                        VALUES ($nextId, '$nombre', $estado)";

        $resultInsert = mysqli_query($conn, $queryInsert);
        mysqli_close($conn);

        return $resultInsert;
    }

    public function updateTbCriterio($criterio)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');
        
        $id = intval($criterio->getTbCriterioId()); // Asegúrate de que $id sea un entero
        $nombre = mysqli_real_escape_string($conn, $criterio->getTbCriterioNombre());
        $estado = intval($criterio->getTbCriterioEstado()); // Asegúrate de que $estado sea un entero
        
            $queryUpdate = "UPDATE tbcriterio SET tbcriterionombre='$nombre', tbcriterioestado=$estado WHERE tbcriterioid=$id;";
            $result = mysqli_query($conn, $queryUpdate);

        mysqli_close($conn);
    
        return $result;
    }
    
/*
    public function deleteTbCriterio($criterioId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryDelete = "UPDATE tbcriterio SET tbcriterioestado = '0' WHERE tbcriterioid=$criterioId;";
        $result = mysqli_query($conn, $queryDelete);
        mysqli_close($conn);

        return $result;
    }
*/  
    public function checkAssociatedValues($criterioId){
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        // Paso 1: Verificar cuántos valores están asociados al criterio
        $queryCountValues = "SELECT COUNT(*) as totalValues FROM tbvalor WHERE tbcriterioid = $criterioId AND tbvalorestado = 1;";
        $resultCount = mysqli_query($conn, $queryCountValues);

        if ($row = mysqli_fetch_assoc($resultCount)) {
            $totalValues = $row['totalValues'];

            if ($totalValues > 0) {
                // Obtener los nombres de los valores asociados
                $queryValuesDetails = "SELECT tbvalornombre FROM tbvalor WHERE tbcriterioid = $criterioId AND tbvalorestado = 1;";
                $resultValues = mysqli_query($conn, $queryValuesDetails);
                $valueNames = [];
                while ($valueRow = mysqli_fetch_assoc($resultValues)) {
                    $valueNames[] = $valueRow['tbvalornombre'];
                }
                $valueList = implode(', ', $valueNames);

                // Devolver el mensaje con la lista de valores asociados
                mysqli_close($conn);
                return [
                    'status' => 'confirm',
                    'message' => "El criterio tiene $totalValues valores asociados: $valueList. ¿Está seguro de que desea eliminarlo?",
                    'totalValues' => $totalValues
                ];
            }
        }

        // Cierre de conexión
        mysqli_close($conn);
        return ['status' => 'proceed']; // No tiene valores asociados
    }


    public function deleteCriterioById($criterioId) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');
        
        // Eliminar el criterio
        $queryDeleteCriterio = "UPDATE tbcriterio SET tbcriterioestado = '0' WHERE tbcriterioid=$criterioId;";
        $resultDeleteCriterio = mysqli_query($conn, $queryDeleteCriterio);
    
        // Eliminar valores asociados
        $queryDeleteValues = "UPDATE tbvalor SET tbvalorestado = '0' WHERE tbcriterioid=$criterioId;";
        $resultDeleteValues = mysqli_query($conn, $queryDeleteValues);
        
        mysqli_close($conn);
        
        if ($resultDeleteCriterio && $resultDeleteValues) {
            return ['status' => 'success', 'message' => 'Criterio y valores asociados eliminados correctamente.'];
        } else {
            return ['status' => 'error', 'message' => 'Error al eliminar el criterio o los valores asociados.'];
        }
    }

    public function deleteForeverTbCriterio($criterioId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryDelete = "DELETE FROM tbcriterio WHERE tbcriterioid=$criterioId;";
        $result = mysqli_query($conn, $queryDelete);
        mysqli_close($conn);

        return $result;
    }

    public function getAllTbCriterio()
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $querySelect = "SELECT * FROM tbcriterio WHERE tbcriterioestado = 1;";
        $result = mysqli_query($conn, $querySelect);
        $criterios = [];
        while ($row = mysqli_fetch_array($result)) {
            $criterioActual = new Criterio($row['tbcriterioid'], $row['tbcriterionombre'], $row['tbcriterioestado']);
            array_push($criterios, $criterioActual);
        }
        mysqli_close($conn);

        return $criterios;
    }

    public function getAllTbCriterioNombres()
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $querySelect = "SELECT tbcriterionombre FROM tbcriterio WHERE tbcriterioestado = 1;";
        $result = mysqli_query($conn, $querySelect);

        if (!$result) {
            // Manejo de errores de consulta
            die('Error en la consulta: ' . mysqli_error($conn));
        }

        $nombres = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $nombres[] = $row['tbcriterionombre'];
        }

        mysqli_close($conn);

        return $nombres;
    }
/*
    public function getAllDeletedTbCriterio()
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $querySelect = "SELECT * FROM tbcriterio WHERE tbcriterioestado = 0;";
        $result = mysqli_query($conn, $querySelect);
        $criterios = [];
        while ($row = mysqli_fetch_array($result)) {
            $criterioActual = new Criterio($row['tbcriterioid'], $row['tbcriterionombre'], $row['tbcriterioestado']);
            array_push($criterios, $criterioActual);
        }
        mysqli_close($conn);

        return $criterios;
    }
*/  
    public function getAllDeletedTbCriterio() {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');
        $query = "SELECT * FROM tbcriterio WHERE tbcriterioestado = 0;";
        $result = mysqli_query($conn, $query);
        $criterios = [];
        while ($row = mysqli_fetch_array($result)) {
            $criterioActual = new Criterio($row['tbcriterioid'], $row['tbcriterionombre'], $row['tbcriterioestado']);
            array_push($criterios, $criterioActual);
        }
        return $criterios;
    }

    public function restoreTbCriterio($criterioId) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');
        $criterioId = mysqli_real_escape_string($conn, $criterioId);
        $query = "UPDATE tbcriterio SET tbcriterioestado = 1 WHERE tbcriterioid = '$criterioId';";
        $result = mysqli_query($conn, $query);
        return $result;
    }

    public function exist($nombre)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $query = "SELECT COUNT(*) as count FROM tbcriterio WHERE tbcriterionombre = ?";
        
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 's', $nombre);
        
        mysqli_stmt_execute($stmt);
        
        mysqli_stmt_bind_result($stmt, $count);
        mysqli_stmt_fetch($stmt);
        
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        
        return $count > 0;
    }

    public function insertRequestTbCriterio($criterio){
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        // Consulta para obtener el ID máximo
        $queryGetLastId = "SELECT MAX(tbsolicitudcriterioid) AS max_id FROM tbsolicitudcriterio";
        $result = mysqli_query($conn, $queryGetLastId);

        if ($row = mysqli_fetch_assoc($result)) {
            // Obtén el ID máximo o establece 0 si la tabla está vacía
            $maxId = $row['max_id'];
            $nextId = ($maxId !== null) ? (int)$maxId + 1 : 1;
        } else {
            // Si no se obtiene resultado, asegúrate de manejar el error adecuadamente
            $nextId = 1;
        }

        $nombre = mysqli_real_escape_string($conn, $criterio->getTbCriterioNombre());
        $estado = 0;

        // Consulta para insertar un nuevo registro
        $queryInsert = "INSERT INTO tbsolicitudcriterio (tbsolicitudcriterioid, tbsolicitudcriterionombre, tbsolicitudcriterioestado) 
                        VALUES ($nextId, '$nombre', $estado)";

        $resultInsert = mysqli_query($conn, $queryInsert);
        mysqli_close($conn);

        return $resultInsert;
    }

    public function getCriterioNombreById($idCriterio){
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $query = "SELECT tbcriterionombre FROM tbcriterio WHERE tbcriterioid = ? AND tbcriterioestado = 1";
        
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'i', $idCriterio);  // 'i' indica que el parámetro es un entero
        
        mysqli_stmt_execute($stmt);
        
        mysqli_stmt_bind_result($stmt, $nombre);
        mysqli_stmt_fetch($stmt);
        
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        
        return $nombre;
    }

    public function autocomplete($term) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');
    
        $sql = "SELECT tbcriterionombre FROM tbcriterio WHERE tbcriterionombre LIKE ?";
        $stmt = $conn->prepare($sql);
        $searchTerm = "%$term%";
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $suggestions = [];
        while ($row = $result->fetch_assoc()) {
            $suggestions[] = $row['tbcriterionombre'];
        }
    
        $stmt->close();
        $conn->close();
    
        return $suggestions;
    }

}
