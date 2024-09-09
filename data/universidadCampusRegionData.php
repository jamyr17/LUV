<?php

include_once 'data.php';
include '../domain/universidadCampusRegionDomain.php';

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

    public function deleteTbUniversidadCampusRegion($campusRegionId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryDelete = "UPDATE tbuniversidadcampusregion SET tbuniversidadcampusregionestado = '0' WHERE tbuniversidadcampusregionid=$campusRegionId;";
        $result = mysqli_query($conn, $queryDelete);
        mysqli_close($conn);

        return $result;
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
