<?php

include_once 'data.php';
include '../domain/universidadDomain.php';

class UniversidadData extends Data
{

    public function insertTbUniversidad($universidad)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        // Consulta para obtener el ID máximo
        $queryGetLastId = "SELECT MAX(tbuniversidadid) AS max_id FROM tbuniversidad";
        $result = mysqli_query($conn, $queryGetLastId);

        if ($row = mysqli_fetch_assoc($result)) {
            $maxId = $row['max_id'];
            $nextId = ($maxId !== null) ? (int)$maxId + 1 : 1;
        } else {
            $nextId = 1;
        }

        $nombre = mysqli_real_escape_string($conn, $universidad->getTbUniversidadNombre());
        $estado = 1;

        // Consulta para insertar un nuevo registro
        $queryInsert = "INSERT INTO tbuniversidad (tbuniversidadid, tbuniversidadnombre, tbuniversidadestado) 
                        VALUES ($nextId, '$nombre', $estado)";

        $resultInsert = mysqli_query($conn, $queryInsert);
        mysqli_close($conn);

        return $resultInsert;
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
/*
    public function deleteTbUniversidad($universidadId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryDelete = "UPDATE tbuniversidad SET tbuniversidadestado = '0' WHERE tbuniversidadid=$universidadId;";
        $result = mysqli_query($conn, $queryDelete);
        mysqli_close($conn);

        return $result;
    }
*/
    public function checkAssociatedCampus($universidadId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        // Paso 1: Verificar cuántos campus están asociados a la universidad
        $queryCountCampus = "SELECT COUNT(*) as totalCampus FROM tbuniversidadcampus WHERE tbuniversidadid = $universidadId AND tbuniversidadcampusestado = 1;";
        $resultCount = mysqli_query($conn, $queryCountCampus);

        if ($row = mysqli_fetch_assoc($resultCount)) {
            $totalCampus = $row['totalCampus'];

            if ($totalCampus > 0) {
                // Obtener los nombres de los campus asociados
                $queryCampusDetails = "SELECT tbuniversidadcampusnombre FROM tbuniversidadcampus WHERE tbuniversidadid = $universidadId AND tbuniversidadcampusestado = 1;";
                $resultCampus = mysqli_query($conn, $queryCampusDetails);
                $campusNames = [];
                while ($campusRow = mysqli_fetch_assoc($resultCampus)) {
                    $campusNames[] = $campusRow['tbuniversidadcampusnombre'];
                }
                $campusList = implode(', ', $campusNames);

                // Devolver el mensaje con la lista de campus asociados
                mysqli_close($conn);
                return [
                    'status' => 'confirm',
                    'message' => "La universidad tiene $totalCampus campus asociados: $campusList. ¿Está seguro de que desea eliminarla?",
                    'totalCampus' => $totalCampus
                ];
            }
        }

        // Cierre de conexión
        mysqli_close($conn);
        return ['status' => 'proceed']; // No tiene campus asociados
    }

    public function deleteUniversityById($universidadId) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');
    
        // Eliminar la universidad
        $queryDelete = "UPDATE tbuniversidad SET tbuniversidadestado = '0' WHERE tbuniversidadid=$universidadId;";
        $resultDelete = mysqli_query($conn, $queryDelete);
    
        mysqli_close($conn);
    
        if ($resultDelete) {
            return ['status' => 'success', 'message' => 'Universidad eliminada correctamente.'];
        } else {
            return ['status' => 'error', 'message' => 'Error al eliminar la universidad.'];
        }
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

    public function getAllDeletedTbUniversidad() {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');
        $query = "SELECT * FROM tbuniversidad WHERE tbuniversidadestado = 0;";
        $result = mysqli_query($conn, $query);
        $universidades = [];
        while ($row = mysqli_fetch_array($result)) {
            $universidad = new Universidad($row['tbuniversidadid'], $row['tbuniversidadnombre'], $row['tbuniversidadestado']);
            array_push($universidades, $universidad);
        }
        return $universidades;
    }
    
    public function restoreTbUniversidad($idUniversidad) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');
        $query = "UPDATE tbuniversidad SET tbuniversidadestado = 1 WHERE tbuniversidadid = $idUniversidad;";
        $result = mysqli_query($conn, $query);
        return $result;
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

    public function insertRequestTbUniversidad($universidad)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        // Consulta para obtener el ID máximo
        $queryGetLastId = "SELECT MAX(tbsolicituduniversidadid) AS max_id FROM tbsolicituduniversidad";
        $result = mysqli_query($conn, $queryGetLastId);

        if ($row = mysqli_fetch_assoc($result)) {
            $maxId = $row['max_id'];
            $nextId = ($maxId !== null) ? (int)$maxId + 1 : 1;
        } else {
            $nextId = 1;
        }

        $nombre = mysqli_real_escape_string($conn, $universidad->getTbUniversidadNombre());
        $estado = 0;

        // Consulta para insertar un nuevo registro de solicitud
        $queryInsert = "INSERT INTO tbsolicituduniversidad (tbsolicituduniversidadid, tbsolicituduniversidadnombre, tbsolicituduniversidadestado) 
                        VALUES ($nextId, '$nombre', $estado)";

        $resultInsert = mysqli_query($conn, $queryInsert);
        mysqli_close($conn);

        return $resultInsert;
    }

}
?>