<?php

include_once 'data.php';
include_once '../domain/universidadCampusEspecializacionDomain.php';
include_once '../business/campusBusiness.php';

class UniversidadCampusEspecializacionData extends Data
{

    public function insertTbUniversidadCampusEspecializacion($universidadCampusEspecializacion)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        // Consulta para obtener el ID máximo
        $queryGetLastId = "SELECT MAX(tbuniversidadcampusespecializacionid) AS max_id FROM tbuniversidadcampusespecializacion";
        $result = mysqli_query($conn, $queryGetLastId);

        if ($row = mysqli_fetch_assoc($result)) {
            // Obtén el ID máximo o establece 0 si la tabla está vacía
            $maxId = $row['max_id'];
            $nextId = ($maxId !== null) ? (int)$maxId + 1 : 1;
        } else {
            // Si no se obtiene resultado, asegúrate de manejar el error adecuadamente
            $nextId = 1;
        }

        $nombre = mysqli_real_escape_string($conn, $universidadCampusEspecializacion->getTbUniversidadCampusEspecializacionNombre());
        $descripcion = mysqli_real_escape_string($conn, $universidadCampusEspecializacion->getTbUniversidadCampusEspecializacionDescripcion());
        $estado = 1;

        // Consulta para insertar un nuevo registro
        $queryInsert = "INSERT INTO tbuniversidadcampusespecializacion (tbuniversidadcampusespecializacionid, tbuniversidadcampusespecializacionnombre, tbuniversidadcampusespecializaciondescripcion, tbuniversidadcampusespecializacionestado) 
                        VALUES ($nextId, '$nombre', '$descripcion', $estado)";

        $resultInsert = mysqli_query($conn, $queryInsert);
        mysqli_close($conn);

        return $resultInsert;
    }

    public function updateTbUniversidadCampusEspecializacion($universidadCampusEspecializacion)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $id = intval($universidadCampusEspecializacion->getTbUniversidadCampusEspecializacionId()); // Asegúrate de que $id sea un entero
        $nombre = mysqli_real_escape_string($conn, $universidadCampusEspecializacion->getTbUniversidadCampusEspecializacionNombre());
        $descripcion = mysqli_real_escape_string($conn, $universidadCampusEspecializacion->getTbUniversidadCampusEspecializacionDescripcion());

        $queryUpdate = "UPDATE tbuniversidadcampusespecializacion SET tbuniversidadcampusespecializacionnombre='$nombre', tbuniversidadcampusespecializaciondescripcion='$descripcion' WHERE tbuniversidadcampusespecializacionid=$id;";

        $result = mysqli_query($conn, $queryUpdate);
        mysqli_close($conn);

        return $result;
    }
/*
    public function deleteTbUniversidadCampusEspecializacion($universidadCampusEspecializacionId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryDelete = "UPDATE tbuniversidadcampusespecializacion SET tbuniversidadcampusespecializacionestado = '0' WHERE tbuniversidadcampusespecializacionid=$universidadCampusEspecializacionId;";
        $result = mysqli_query($conn, $queryDelete);
        mysqli_close($conn);

        return $result;
    }
*/  

    public function checkAssociatedCampusSpecialization($specializationId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        // Paso 1: Verificar cuántos campus están asociados a la especialización
        $queryCountCampus = "SELECT COUNT(*) as totalCampus FROM tbuniversidadcampus WHERE tbuniversidadcampusespecializacionid = $specializationId AND tbuniversidadcampusestado = 1;";
        $resultCount = mysqli_query($conn, $queryCountCampus);

        if ($row = mysqli_fetch_assoc($resultCount)) {
            $totalCampus = $row['totalCampus'];

            if ($totalCampus > 0) {
                // Obtener los nombres de los campus asociados
                $queryCampusDetails = "SELECT tbuniversidadcampusnombre FROM tbuniversidadcampus WHERE tbuniversidadcampusespecializacionid = $specializationId AND tbuniversidadcampusestado = 1;";
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
                    'message' => "La especialización tiene $totalCampus campus asociados: $campusList. ¿Está seguro de que desea eliminarla?",
                    'totalCampus' => $totalCampus
                ];
            }
        }

        // Cierre de conexión
        mysqli_close($conn);
        return ['status' => 'proceed']; // No tiene campus asociados
    }

        public function deleteSpecializationById($specializationId){
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        // Eliminar la especialización
        $queryDelete = "UPDATE tbuniversidadcampusespecializacion SET tbuniversidadcampusespecializacionestado = '0' WHERE tbuniversidadcampusespecializacionid = $specializationId;";
        $resultDelete = mysqli_query($conn, $queryDelete);

        // Eliminar los campus asociados a la especialización
        $campusBusiness = new CampusBusiness();
        $resultDeleteCampus =  $campusBusiness->deleteTbCampusBySpecializationId($specializationId);

        mysqli_close($conn);

        if ($resultDelete && $resultDeleteCampus) {
            return ['status' => 'success', 'message' => 'Especialización eliminada correctamente.'];
        } else {
            return ['status' => 'error', 'message' => 'Error al eliminar la especialización.'];
        }
    }

    public function deleteForeverTbUniversidadCampusEspecializacion($universidadCampusEspecializacionId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryDelete = "DELETE FROM tbuniversidadcampusespecializacion WHERE tbuniversidadcampusespecializacionid=$universidadCampusEspecializacionId;";
        $result = mysqli_query($conn, $queryDelete);
        mysqli_close($conn);

        return $result;
    }

    public function getAllTbUniversidadCampusEspecializacion()
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $querySelect = "SELECT * FROM tbuniversidadcampusespecializacion WHERE tbuniversidadcampusespecializacionestado = 1;";
        $result = mysqli_query($conn, $querySelect);
        $universidadCampusEspecializaciones = [];
        while ($row = mysqli_fetch_array($result)) {
            $universidadCampusEspecializacionActual = new universidadCampusEspecializacion($row['tbuniversidadcampusespecializacionid'], $row['tbuniversidadcampusespecializacionnombre'], $row['tbuniversidadcampusespecializaciondescripcion'], $row['tbuniversidadcampusespecializacionestado']);
            array_push($universidadCampusEspecializaciones, $universidadCampusEspecializacionActual);
        }
        mysqli_close($conn);

        return $universidadCampusEspecializaciones;
    }

    public function getAllTbUniversidadCampusEspecializacionNombres()
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $querySelect = "SELECT tbuniversidadcampusespecializacionnombre FROM tbuniversidadcampusespecializacion WHERE tbuniversidadcampusespecializacionestado = 1;";
        $result = mysqli_query($conn, $querySelect);

        if (!$result) {
            // Manejo de errores de consulta
            die('Error en la consulta: ' . mysqli_error($conn));
        }

        $nombres = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $nombres[] = $row['tbuniversidadcampusespecializacionnombre'];
        }

        mysqli_close($conn);

        return $nombres;
    }
/*
    public function getAllDeletedTbUniversidadCampusEspecializacion()
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $querySelect = "SELECT * FROM tbuniversidadcampusespecializacion WHERE tbuniversidadcampusespecializacionestado = 0;";
        $result = mysqli_query($conn, $querySelect);
        $universidadCampusEspecializaciones = [];
        while ($row = mysqli_fetch_array($result)) {
            $universidadCampusEspecializacionActual = new universidadCampusEspecializacion($row['tbuniversidadcampusespecializacionid'], $row['tbuniversidadcampusespecializacionnombre'], $row['tbuniversidadcampusespecializaciondescripcion'], $row['tbuniversidadcampusespecializacionestado']);
            array_push($universidadCampusEspecializaciones, $universidadCampusEspecializacionActual);
        }
        mysqli_close($conn);

        return $universidadCampusEspecializaciones;
    }
*/  
    public function getAllDeletedTbUniversidadCampusEspecializacion() {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');
        $query = "SELECT * FROM tbuniversidadcampusespecializacion WHERE tbuniversidadcampusespecializacionestado = 0;";
        $result = mysqli_query($conn, $query);
        $universidadCampusEspecializaciones = [];
        while ($row = mysqli_fetch_array($result)) {
            $universidadCampusEspecializacionActual = new universidadCampusEspecializacion($row['tbuniversidadcampusespecializacionid'], $row['tbuniversidadcampusespecializacionnombre'], $row['tbuniversidadcampusespecializaciondescripcion'], $row['tbuniversidadcampusespecializacionestado']);
            array_push($universidadCampusEspecializaciones, $universidadCampusEspecializacionActual);
        }
        return $universidadCampusEspecializaciones;
    }

    public function restoreTbCampusEspecializacion($universidadCampusEspecializacionId) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');
        $universidadCampusEspecializacionId = mysqli_real_escape_string($conn, $universidadCampusEspecializacionId);
        $query = "UPDATE tbuniversidadcampusespecializacion SET tbuniversidadcampusespecializacionestado = 1 WHERE tbuniversidadcampusespecializacionid = '$universidadCampusEspecializacionId';";
        $result = mysqli_query($conn, $query);
        return $result;
    }

    public function exist($nombre)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $query = "SELECT COUNT(*) as count FROM tbuniversidadcampusespecializacion WHERE tbuniversidadcampusespecializacionnombre = ?";
        
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

        $query = "SELECT COUNT(*) as count FROM tbuniversidadcampusespecializacion WHERE tbuniversidadcampusespecializacionnombre = ? AND tbuniversidadcampusespecializacionid != ?";
        
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
    
        $sql = "SELECT tbuniversidadcampusespecializacionnombre FROM tbuniversidadcampusespecializacion WHERE tbuniversidadcampusespecializacionnombre LIKE ?";
        $stmt = $conn->prepare($sql);
        $searchTerm = "%$term%";
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $suggestions = [];
        while ($row = $result->fetch_assoc()) {
            $suggestions[] = $row['tbuniversidadcampusespecializacionnombre'];
        }
    
        $stmt->close();
        $conn->close();
    
        return $suggestions;
    }
}
?>
