<?php

include_once 'data.php';
include '../domain/campusregion.php';

class CampusRegionData extends Data
{
    public function insertTbCampusRegion($campusRegion)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryGetLastId = "SELECT MAX(tbcampusregionid) AS max_id FROM tbcampusregion";
        $result = mysqli_query($conn, $queryGetLastId);

        if ($row = mysqli_fetch_assoc($result)) {
            $maxId = $row['max_id'];
            $nextId = ($maxId !== null) ? (int)$maxId + 1 : 1;
        } else {
            $nextId = 1;
        }

        $nombre = mysqli_real_escape_string($conn, $campusRegion->getTbcampusregionnombre());
        $descripcion = mysqli_real_escape_string($conn, $campusRegion->getTbcampusregiondescripcion());
        $estado = 1;

        $queryInsert = "INSERT INTO tbcampusregion (tbcampusregionid, tbcampusregionnombre, tbcampusregiondescripcion, tbcampusregionestado) 
                        VALUES ($nextId, '$nombre', '$descripcion', $estado)";

        $resultInsert = mysqli_query($conn, $queryInsert);
        mysqli_close($conn);

        return $resultInsert;
    }

    public function updateTbCampusRegion($campusRegion)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $id = intval($campusRegion->getTbcampusregionid()); 
        $nombre = mysqli_real_escape_string($conn, $campusRegion->getTbcampusregionnombre());
        $descripcion = mysqli_real_escape_string($conn, $campusRegion->getTbcampusregiondescripcion());

        $queryUpdate = "UPDATE tbcampusregion SET tbcampusregionnombre='$nombre', tbcampusregiondescripcion='$descripcion' WHERE tbcampusregionid=$id;";

        $result = mysqli_query($conn, $queryUpdate);
        mysqli_close($conn);

        return $result;
    }

    public function deleteTbCampusRegion($campusRegionId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryDelete = "UPDATE tbcampusregion SET tbcampusregionestado = '0' WHERE tbcampusregionid=$campusRegionId;";
        $result = mysqli_query($conn, $queryDelete);
        mysqli_close($conn);

        return $result;
    }

    public function deleteForeverTbCampusRegion($campusRegionId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryDelete = "DELETE FROM tbcampusregion WHERE tbcampusregionid=$campusRegionId;";
        $result = mysqli_query($conn, $queryDelete);
        mysqli_close($conn);

        return $result;
    }

    public function getAllTbCampusRegion()
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $querySelect = "SELECT * FROM tbcampusregion WHERE tbcampusregionestado = 1;";
        $result = mysqli_query($conn, $querySelect);
        $campusRegions = [];
        while ($row = mysqli_fetch_array($result)) {
            $campusRegionActual = new CampusRegion($row['tbcampusregionid'], $row['tbcampusregionnombre'], $row['tbcampusregiondescripcion'], $row['tbcampusregionestado']);
            array_push($campusRegions, $campusRegionActual);
        }
        mysqli_close($conn);

        return $campusRegions;
    }

    public function getAllDeletedTbCampusRegion()
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $querySelect = "SELECT * FROM tbcampusregion WHERE tbcampusregionestado = 0;";
        $result = mysqli_query($conn, $querySelect);
        $campusRegions = [];
        while ($row = mysqli_fetch_array($result)) {
            $campusRegionActual = new CampusRegion($row['tbcampusregionid'], $row['tbcampusregionnombre'], $row['tbcampusregiondescripcion'], $row['tbcampusregionestado']);
            array_push($campusRegions, $campusRegionActual);
        }
        mysqli_close($conn);

        return $campusRegions;
    }

    public function exist($nombre)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $query = "SELECT COUNT(*) as count FROM tbcampusregion WHERE tbcampusregionnombre = ?";
        
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
