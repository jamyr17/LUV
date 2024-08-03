<?php

include_once 'data.php';
include '../domain/areaConocimiento.php';

class AreaConocimientoData extends Data
{

    public function insertTbAreaConocimiento($areaConocimiento)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        // Consulta para obtener el ID máximo
        $queryGetLastId = "SELECT MAX(tbareaconocimientoid) AS max_id FROM tbareaconocimiento";
        $result = mysqli_query($conn, $queryGetLastId);

        if ($row = mysqli_fetch_assoc($result)) {
            // Obtén el ID máximo o establece 0 si la tabla está vacía
            $maxId = $row['max_id'];
            $nextId = ($maxId !== null) ? (int)$maxId + 1 : 1;
        } else {
            // Si no se obtiene resultado, asegúrate de manejar el error adecuadamente
            $nextId = 1;
        }

        $nombre = mysqli_real_escape_string($conn, $areaConocimiento->getTbAreaConocimientoNombre());
        $descripcion = mysqli_real_escape_string($conn, $areaConocimiento->getTbAreaConocimientoDescripcion());
        $estado = 1;

        // Consulta para insertar un nuevo registro
        $queryInsert = "INSERT INTO tbareaconocimiento (tbareaconocimientoid, tbareaconocimientonombre, tbareaconocimientodescripcion, tbareaconocimientoestado) 
                        VALUES ($nextId, '$nombre', '$descripcion', $estado)";

        $resultInsert = mysqli_query($conn, $queryInsert);
        mysqli_close($conn);

        return $resultInsert;
    }

    public function updateTbAreaConocimiento($areaConocimiento)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $id = intval($areaConocimiento->getTbAreaConocimientoId()); // Asegúrate de que $id sea un entero
        $nombre = $areaConocimiento->getTbAreaConocimientoNombre();
        $descripcion = $areaConocimiento->getTbAreaConocimientoDescripcion();

        $queryUpdate = "UPDATE tbareaconocimiento SET tbareaconocimientonombre='$nombre', tbareaconocimientodescripcion='$descripcion' WHERE tbAreaConocimientoId=$id;";

        $result = mysqli_query($conn, $queryUpdate);
        mysqli_close($conn);

        return $result;
    }

    public function deleteTbAreaConocimiento($areaConocimientoId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryDelete = "UPDATE tbareaconocimiento SET tbareaconocimientoestado = '0' WHERE tbareaconocimientoid=$areaConocimientoId;";
        $result = mysqli_query($conn, $queryDelete);
        mysqli_close($conn);

        return $result;
    }

    public function deleteForeverTbAreaConocimiento($areaConocimientoId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryDelete = "DELETE FROM tbareaconocimiento WHERE tbareaconocimientoid=" . $areaConocimientoId . ";";
        $result = mysqli_query($conn, $queryDelete);
        mysqli_close($conn);

        return $result;
    }

    public function getAllTbAreaConocimiento()
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $querySelect = "SELECT * FROM tbareaconocimiento WHERE tbareaconocimientoestado = 1;";
        $result = mysqli_query($conn, $querySelect);
        mysqli_close($conn);

        $areasconocimiento = [];
        while ($row = mysqli_fetch_array($result)) {
            $areaConocimientoActual = new AreaConocimiento($row['tbareaconocimientoid'], $row['tbareaconocimientonombre'], $row['tbareaconocimientodescripcion'], $row['tbareaconocimientoestado']);
            array_push($areasconocimiento, $areaConocimientoActual);
        }

        return $areasconocimiento;
    }

    public function getAllDeletedTbAreaConocimiento()
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $querySelect = "SELECT * FROM tbareaconocimiento;";
        $result = mysqli_query($conn, $querySelect);
        mysqli_close($conn);

        $areasconocimiento = [];
        while ($row = mysqli_fetch_array($result)) {
            $areaConocimientoActual = new AreaConocimiento($row['tbareaconocimientoid'], $row['tbareaconocimientonombre'], $row['tbareaconocimientodescripcion'], $row['tbareaconocimientoestado']);
            array_push($areasconocimiento, $areaConocimientoActual);
        }

        return $areasconocimiento;
    }

    public function exist($nombre)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $query = "SELECT COUNT(*) as count FROM tbareaconocimiento WHERE tbareaconocimientonombre = ?";
        
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
