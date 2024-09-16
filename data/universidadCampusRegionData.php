<?php

include_once 'data.php';
include_once '../domain/universidadCampusRegionDomain.php';
include_once '../business/campusBusiness.php';

class UniversidadCampusRegionData extends Data
{
    public function insertTbUniversidadCampusRegion($campusRegion)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryGetLastId = "SELECT MAX(tbuniversidadcampusregionid) AS max_id FROM tbuniversidadcampusregion";
        $result = mysqli_query($conn, $queryGetLastId);

        if ($row = mysqli_fetch_assoc($result)) {
            $maxId = $row['max_id'];
            $nextId = ($maxId !== null) ? (int)$maxId + 1 : 1;
        } else {
            $nextId = 1;
        }

        $nombre = mysqli_real_escape_string($conn, $campusRegion->getTbUniversidadCampusRegionNombre());
        $descripcion = mysqli_real_escape_string($conn, $campusRegion->getTbUniversidadCampusRegionDescripcion());
        $estado = 1;

        $queryInsert = "INSERT INTO tbuniversidadcampusregion (tbuniversidadcampusregionid, tbuniversidadcampusregionnombre, tbuniversidadcampusregiondescripcion, tbuniversidadcampusregionestado) 
                        VALUES ($nextId, '$nombre', '$descripcion', $estado)";

        $resultInsert = mysqli_query($conn, $queryInsert);
        mysqli_close($conn);

        return $resultInsert;
    }

    public function updateTbUniversidadCampusRegion($campusRegion)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $id = intval($campusRegion->getTbUniversidadCampusRegionId()); 
        $nombre = mysqli_real_escape_string($conn, $campusRegion->getTbUniversidadCampusRegionNombre());
        $descripcion = mysqli_real_escape_string($conn, $campusRegion->getTbUniversidadCampusRegionDescripcion());

        $queryUpdate = "UPDATE tbuniversidadcampusregion SET tbuniversidadcampusregionnombre='$nombre', tbuniversidadcampusregiondescripcion='$descripcion' WHERE tbuniversidadcampusregionid=$id;";

        $result = mysqli_query($conn, $queryUpdate);
        mysqli_close($conn);

        return $result;
    }
/*
    public function deleteTbUniversidadCampusRegion($campusRegionId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryDelete = "UPDATE tbuniversidadcampusregion SET tbuniversidadcampusregionestado = '0' WHERE tbuniversidadcampusregionid=$campusRegionId;";
        $result = mysqli_query($conn, $queryDelete);
        mysqli_close($conn);

        return $result;
    }
*/
    public function checkAssociatedCampus($campusRegionId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        // Paso 1: Verificar cuántos campus están asociados a la región
        $queryCountCampus = "SELECT COUNT(*) as totalCampus FROM tbuniversidadcampus WHERE tbuniversidadcampusregionid = $campusRegionId AND tbuniversidadcampusestado = 1;";
        $resultCount = mysqli_query($conn, $queryCountCampus);

        if ($row = mysqli_fetch_assoc($resultCount)) {
            $totalCampus = $row['totalCampus'];

            if ($totalCampus > 0) {
                // Obtener los nombres de los campus asociados
                $queryCampusDetails = "SELECT tbuniversidadcampusnombre FROM tbuniversidadcampus WHERE tbuniversidadcampusregionid = $campusRegionId AND tbuniversidadcampusestado = 1;";
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
                    'message' => "La región tiene $totalCampus campus asociados: $campusList. ¿Está seguro de que desea eliminarla?",
                    'totalCampus' => $totalCampus
                ];
            }
        }

        // Cierre de conexión
        mysqli_close($conn);
        return ['status' => 'proceed']; // No tiene campus asociados
    }

    public function deleteRegionById($campusRegionId) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');
    
        // Eliminar la región
        $queryDelete = "UPDATE tbuniversidadcampusregion SET tbuniversidadcampusregionestado = '0' WHERE tbuniversidadcampusregionid=$campusRegionId;";
        $resultDelete = mysqli_query($conn, $queryDelete);

        // Eliminar campus asociados
        $campusBusiness = new CampusBusiness();
        $resultDeleteCampus =  $campusBusiness->deleteTbCampusByRegionId($campusRegionId);
    
        mysqli_close($conn);
    
        if ($resultDelete && $resultDeleteCampus) {
            return ['status' => 'success', 'message' => 'Región eliminada correctamente.'];
        } else {
            return ['status' => 'error', 'message' => 'Error al eliminar la región.'];
        }
    }

    public function deleteForeverTbUniversidadCampusRegion($campusRegionId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryDelete = "DELETE FROM tbuniversidadcampusregion WHERE tbuniversidadcampusregionid=$campusRegionId;";
        $result = mysqli_query($conn, $queryDelete);
        mysqli_close($conn);

        return $result;
    }

    public function getAllTbUniversidadCampusRegion()
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $querySelect = "SELECT * FROM tbuniversidadcampusregion WHERE tbuniversidadcampusregionestado = 1;";
        $result = mysqli_query($conn, $querySelect);
        $campusRegions = [];
        while ($row = mysqli_fetch_array($result)) {
            $campusRegionActual = new UniversidadCampusRegion($row['tbuniversidadcampusregionid'], $row['tbuniversidadcampusregionnombre'], $row['tbuniversidadcampusregiondescripcion'], $row['tbuniversidadcampusregionestado']);
            array_push($campusRegions, $campusRegionActual);
        }
        mysqli_close($conn);

        return $campusRegions;
    }

    public function getAllTbUniversidadCampusRegionNombres()
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $querySelect = "SELECT tbuniversidadcampusregionnombre FROM tbuniversidadcampusregion WHERE tbuniversidadcampusregionestado = 1;";
        $result = mysqli_query($conn, $querySelect);

        if (!$result) {
            // Manejo de errores de consulta
            die('Error en la consulta: ' . mysqli_error($conn));
        }

        $nombres = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $nombres[] = $row['tbuniversidadcampusregionnombre'];
        }

        mysqli_close($conn);

        return $nombres;
    }
    
    public function getAllDeletedTbUniversidadCampusRegion() {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');
        $query = "SELECT * FROM tbuniversidadcampusregion WHERE tbuniversidadcampusregionestado = 0;";
        $result = mysqli_query($conn, $query);
        $campusRegions = [];
        while ($row = mysqli_fetch_array($result)) {
            $campusRegionActual = new UniversidadCampusRegion($row['tbuniversidadcampusregionid'], $row['tbuniversidadcampusregionnombre'], $row['tbuniversidadcampusregiondescripcion'], $row['tbuniversidadcampusregionestado']);
            array_push($campusRegions, $campusRegionActual);
        }
        return $campusRegions;
    }

    public function restoreTbCampusRegion($universidadCampusRegionId) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');
        $universidadCampusRegionId = mysqli_real_escape_string($conn, $universidadCampusRegionId);
        $query = "UPDATE tbuniversidadcampusregion SET tbuniversidadcampusregionestado = 1 WHERE tbuniversidadcampusregionid = '$universidadCampusRegionId';";
        $result = mysqli_query($conn, $query);
        return $result;
    }

    public function exist($nombre)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $query = "SELECT COUNT(*) as count FROM tbuniversidadcampusregion WHERE tbuniversidadcampusregionnombre = ?";
        
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

        $query = "SELECT COUNT(*) as count FROM tbuniversidadcampusregion WHERE tbuniversidadcampusregionnombre = ? AND tbuniversidadcampusregionid != ?";
        
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'si', $nombre, $excludeId);
        
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
    
        $sql = "SELECT tbuniversidadcampusregionnombre FROM tbuniversidadcampusregion WHERE tbuniversidadcampusregionnombre LIKE ?";
        $stmt = $conn->prepare($sql);
        $searchTerm = "%$term%";
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $suggestions = [];
        while ($row = $result->fetch_assoc()) {
            $suggestions[] = $row['tbuniversidadcampusregionnombre'];
        }
    
        $stmt->close();
        $conn->close();
    
        return $suggestions;
    }
}
?>
