<?php

include_once 'data.php';
include '../domain/campusDomain.php';

class CampusData extends Data {

    public function insertTbCampus($campus){
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        // Consulta para obtener el ID máximo
        $queryGetLastId = "SELECT MAX(tbuniversidadcampusid) AS max_id FROM tbuniversidadcampus";
        $result = mysqli_query($conn, $queryGetLastId);

        if ($row = mysqli_fetch_assoc($result)) {
            $maxId = $row['max_id'];
            $nextId = ($maxId !== null) ? (int)$maxId + 1 : 1;
        } else {
            $nextId = 1;
        }

        $universidadId = mysqli_real_escape_string($conn, $campus->getTbCampusUniversidadId());
        $nombre = mysqli_real_escape_string($conn, $campus->getTbCampusNombre());
        $direccion = mysqli_real_escape_string($conn, $campus->getTbCampusDireccion());
        $latitud = mysqli_real_escape_string($conn, $campus->getTbCampusLatitud());
        $longitud = mysqli_real_escape_string($conn, $campus->getTbCampusLongitud());
        $estado = 1;
        $regionId = mysqli_real_escape_string($conn, $campus->getTbCampusRegionId());
        $especializacionId = mysqli_real_escape_string($conn, $campus->getTbCampusEspecializacionId());

        // Consulta para insertar un nuevo registro
        $queryInsert = "INSERT INTO tbuniversidadcampus (tbuniversidadcampusid, tbuniversidadid, tbuniversidadcampusnombre, tbuniversidadcampusdireccion, tbuniversidadcampuslatitud, tbuniversidadcampuslongitud, tbuniversidadcampusestado, tbuniversidadcampusregionid, tbuniversidadcampusespecializacionid) 
                        VALUES ($nextId, '$universidadId', '$nombre', '$direccion', '$latitud', '$longitud', $estado, $regionId, $especializacionId)";

        $resultInsert = mysqli_query($conn, $queryInsert);

        // Consulta para obtener el ID máximo para colectivos
        $queryGetLastIdAux = "SELECT MAX(tbuniversidadcampuscolectivoid) AS max_id FROM tbuniversidadcampuscolectivo";
        $resultAux = mysqli_query($conn, $queryGetLastIdAux);

        if ($row = mysqli_fetch_assoc($resultAux)) {
            $maxId = $row['max_id'];
            $nextIdAux = ($maxId !== null) ? (int)$maxId + 1 : 1;
        } else {
            $nextIdAux = 1;
        }

        $colectivos = $campus->getColectivos();
        if (!is_array($colectivos)) {
            throw new Exception('El método getColectivos debe devolver un array.');
        }

        foreach ($colectivos as $colectivoId) {
            if (!is_scalar($colectivoId)) {
                throw new Exception('Cada elemento de getColectivos debe ser una cadena o un número.');
            }
            $colectivoIdEscaped = mysqli_real_escape_string($conn, $colectivoId);
            $query = "INSERT INTO tbuniversidadcampusuniversidadcolectivo (tbuniversidadcampusuniversidadcolectivoid, tbuniversidadcampusid, tbuniversidadcolectivoid) VALUES ($nextIdAux, $nextId, '$colectivoIdEscaped')";
            $resultInsert = mysqli_query($conn, $query);
            $nextIdAux++;
        }

        mysqli_close($conn);

        return $resultInsert;
    }

    public function updateTbCampus($campus){
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $id = intval($campus->getTbCampusId());
        $nombre = mysqli_real_escape_string($conn, $campus->getTbCampusNombre());
        $latitud = mysqli_real_escape_string($conn, $campus->getTbCampusLatitud());
        $longitud = mysqli_real_escape_string($conn, $campus->getTbCampusLongitud());
        $direccion = mysqli_real_escape_string($conn, $campus->getTbCampusDireccion());
        $regionId = mysqli_real_escape_string($conn, $campus->getTbCampusRegionId());
        $especializacionId = mysqli_real_escape_string($conn, $campus->getTbCampusEspecializacionId());

        $queryUpdate = "UPDATE tbuniversidadcampus SET 
                            tbuniversidadcampusnombre='$nombre', 
                            tbuniversidadcampusdireccion='$direccion',
                            tbuniversidadcampuslatitud='$latitud',
                            tbuniversidadcampuslongitud='$longitud',            
                            tbuniversidadcampusregionid=$regionId,
                            tbuniversidadcampusespecializacionid=$especializacionId
                        WHERE tbuniversidadcampusid=$id;";

        $result = mysqli_query($conn, $queryUpdate);

        if (!$result) {
            echo "Error: " . mysqli_error($conn);
            mysqli_close($conn);
            return $result;
        }

        // Eliminar los colectivos actuales asociados con el campus
        $queryDeleteColectivos = "DELETE FROM tbuniversidadcampusuniversidadcolectivo WHERE tbuniversidadcampusid=$id;";
        $resultDelete = mysqli_query($conn, $queryDeleteColectivos);

        if (!$resultDelete) {
            echo "Error al eliminar colectivos: " . mysqli_error($conn);
            mysqli_close($conn);
            return $resultDelete;
        }

        // Insertar los nuevos colectivos
        $colectivos = $campus->getColectivos();
        if (!is_array($colectivos)) {
            throw new Exception('El método getColectivos debe devolver un array.');
        }

        // Obtener el ID máximo actual para los colectivos
        $queryGetLastIdAux = "SELECT MAX(tbuniversidadcampuscolectivoid) AS max_id FROM tbuniversidadcampuscolectivo";
        $resultAux = mysqli_query($conn, $queryGetLastIdAux);

        if ($row = mysqli_fetch_assoc($resultAux)) {
            $maxId = $row['max_id'];
            $nextIdAux = ($maxId !== null) ? (int)$maxId + 1 : 1;
        } else {
            $nextIdAux = 1;
        }

        foreach ($colectivos as $colectivoId) {
            if (!is_scalar($colectivoId)) {
                throw new Exception('Cada elemento de getColectivos debe ser una cadena o un número.');
            }
            $colectivoIdEscaped = mysqli_real_escape_string($conn, $colectivoId);
            $queryInsertColectivo = "INSERT INTO tbuniversidadcampusuniversidadcolectivo (tbuniversidadcampusuniversidadcolectivoid, tbuniversidadcampusid, tbuniversidadcolectivoid) VALUES ($nextIdAux, $id, '$colectivoIdEscaped')";
            $resultInsert = mysqli_query($conn, $queryInsertColectivo);
            if (!$resultInsert) {
                echo "Error al insertar colectivo: " . mysqli_error($conn);
                mysqli_close($conn);
                return $resultInsert;
            }
            $nextIdAux++;
        }

        mysqli_close($conn);

        return $result;
    }

    public function deleteTbCampus($campusId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryDelete = "UPDATE tbuniversidadcampus SET tbuniversidadcampusestado = '0' WHERE tbuniversidadcampusid=$campusId;";
        $result = mysqli_query($conn, $queryDelete);
        mysqli_close($conn);

        return $result;
    }

    public function deleteTbCampusByUniversityId($universidadId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryDelete = "UPDATE tbuniversidadcampus SET tbuniversidadcampusestado = '0' WHERE tbuniversidadid=$universidadId;";
        $result = mysqli_query($conn, $queryDelete);
        mysqli_close($conn);

        return $result;
    }

    public function deleteTbCampusByRegionId($universidadId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryDelete = "UPDATE tbuniversidadcampus SET tbuniversidadcampusestado = '0' WHERE tbuniversidadcampusregionid=$universidadId;";
        $result = mysqli_query($conn, $queryDelete);
        mysqli_close($conn);

        return $result;
    }

    public function deleteTbCampusBySpecializationId($universidadId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryDelete = "UPDATE tbuniversidadcampus SET tbuniversidadcampusestado = '0' WHERE tbuniversidadcampusespecializacionid=$universidadId;";
        $result = mysqli_query($conn, $queryDelete);
        mysqli_close($conn);

        return $result;
    }

    public function deleteForeverTbCampus($campusId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryDelete = "DELETE FROM tbuniversidadcampus WHERE tbuniversidadcampusid=" . $campusId . ";";
        $result = mysqli_query($conn, $queryDelete);
        mysqli_close($conn);

        return $result;
    }

    public function restoreTbCampusByUniversityId($idUniversidad) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');
        $query = "UPDATE tbuniversidadcampus SET tbuniversidadcampusestado = 1 WHERE tbuniversidadid = $idUniversidad;";
        $result = mysqli_query($conn, $query);
        return $result;
    }

    public function getAllTbCampus()
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');
    
        $querySelect = "SELECT * FROM tbuniversidadcampus WHERE tbuniversidadcampusestado = 1;";
        $result = mysqli_query($conn, $querySelect);
        mysqli_close($conn);
    
        $campus = [];
        while ($row = mysqli_fetch_array($result)) {

            $campusActual = new Campus(
                $row['tbuniversidadcampusid'],
                $row['tbuniversidadid'],
                $row['tbuniversidadcampusregionid'],
                $row['tbuniversidadcampusnombre'],
                $row['tbuniversidadcampusdireccion'],
                $row['tbuniversidadcampuslatitud'],
                $row['tbuniversidadcampuslongitud'],
                $row['tbuniversidadcampusestado'],
                $row['tbuniversidadcampusespecializacionid'], 
            
            );
            array_push($campus, $campusActual);
        }
    
        return $campus;
    }
    
    public function getAllTbCampusNombres()
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $querySelect = "SELECT tbuniversidadcampusnombre FROM tbuniversidadcampus WHERE tbuniversidadcampusestado = 1;";
        $result = mysqli_query($conn, $querySelect);

        if (!$result) {
            // Manejo de errores de consulta
            die('Error en la consulta: ' . mysqli_error($conn));
        }

        $nombres = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $nombres[] = $row['tbuniversidadcampusnombre'];
        }

        mysqli_close($conn);

        return $nombres;
    }

    public function getAllTbCampusByUniversidad($idU)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $querySelect = "SELECT * FROM tbuniversidadcampus WHERE tbuniversidadcampusestado = 1 AND tbuniversidadid =" . $idU . ";";

        $result = mysqli_query($conn, $querySelect);

        mysqli_close($conn);

        $mensajeActualizar = "¿Desea actualizar este campus?";
        $mensajeEliminar = "¿Desea eliminar este campus?";

        while ($row = mysqli_fetch_array($result)) {
            echo '<tr>';
            echo '<form method="post" enctype="multipart/form-data" action="../business/campusAction.php" class="campus-form">';
            echo '<input type="hidden" name="idCampus" value="' . $row['tbuniversidadcampusid'] . '">';
            echo '<input type="hidden" name="idUniversidad" value="' . $row['tbuniversidadid'] . '">';
            echo '<td>' . $row['tbuniversidadcampusid'] . '</td>';
            echo '<td><input type="text" name="nombre" value="' . htmlspecialchars($row['tbuniversidadcampusnombre']) . '" class="form-control" /></td>';
            echo '<td><input type="text" name="direccion" value="' . htmlspecialchars($row['tbuniversidadcampusdireccion']) . '" class="form-control" /></td>';
            echo '<td>';
            echo "<button type='submit' class='btn btn-warning me-2' name='update' id='update' onclick='return actionConfirmation(\"$mensajeActualizar\")' >Actualizar</button>";
            echo "<button type='submit' class='btn btn-danger' id='delete' name='delete' onclick='return actionConfirmation(\"$mensajeEliminar\")'>Eliminar</button>";
            echo '</td>';
            echo '</form>';
            echo '</tr>';
        }
    }
/*
    public function getAllDeletedTbCampus()
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $querySelect = "SELECT * FROM tbuniversidadcampus;";
        $result = mysqli_query($conn, $querySelect);
        mysqli_close($conn);

        $campus = [];
        while ($row = mysqli_fetch_array($result)) {
            $campusActual = new Campus($row['tbuniversidadcampusid'], $row['tbuniversidadid'], $row['tbuniversidadcampusregionid'], $row['tbuniversidadcampusnombre'], $row['tbuniversidadcampusdireccion'], $row['tbuniversidadcampuslatitud'], $row['tbuniversidadcampuslongitud'], $row['tbuniversidadcampusestado'], $row['tbuniversidadcampusespecializacionid']);
            array_push($campus, $campusActual);
        }

        return $campus;
    }
        */

        public function getAllDeletedTbCampus() {
            $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
            $conn->set_charset('utf8');
            $query = "SELECT * FROM tbuniversidadcampus WHERE tbuniversidadcampusestado = 0;";
            $result = mysqli_query($conn, $query);
            $campus = [];
            while ($row = mysqli_fetch_array($result)) {
                $campusActual = new Campus($row['tbuniversidadcampusid'], $row['tbuniversidadid'], $row['tbuniversidadcampusregionid'], $row['tbuniversidadcampusnombre'], $row['tbuniversidadcampusdireccion'], $row['tbuniversidadcampuslatitud'], $row['tbuniversidadcampuslongitud'], $row['tbuniversidadcampusestado'], $row['tbuniversidadcampusespecializacionid']);
                array_push($campus, $campusActual); // Aquí se debe usar $campusActual
            }
            return $campus;
        }
        
        
        public function restoreTbCampus($idCampus) {
            $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
            $conn->set_charset('utf8');
        
            // Escapar el ID del campus
            $idCampus = mysqli_real_escape_string($conn, $idCampus);
        
            // Actualizar el estado en la base de datos
            $query = "UPDATE tbuniversidadcampus SET tbuniversidadcampusestado = 1 WHERE tbuniversidadcampusid = '$idCampus';";
            $result = mysqli_query($conn, $query);
        
            if (!$result) {
                // Mostrar un error si la consulta falla
                echo "Error al actualizar el estado del campus: " . mysqli_error($conn);
                return false;
            }
        
            return true;
        }
        

    public function exist($nombre)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $query = "SELECT COUNT(*) as count FROM tbuniversidadcampus WHERE tbuniversidadcampusnombre = ?";

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
    
        $query = "SELECT COUNT(*) as count FROM tbuniversidadcampus WHERE tbuniversidadcampusnombre = ? AND tbuniversidadcampusid != ?";
        
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'si', $nombre, $idAreaConocimiento);
        
        mysqli_stmt_execute($stmt);
        
        mysqli_stmt_bind_result($stmt, $count);
        mysqli_stmt_fetch($stmt);
        
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        
        return $count > 0;
    }


    public function insertRequestTbCampus($campus)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        // Consulta para obtener el ID máximo
        $queryGetLastId = "SELECT MAX(tbsolicituduniversidadcampusid) AS max_id FROM tbsolicituduniversidadcampus";
        $result = mysqli_query($conn, $queryGetLastId);

        if ($row = mysqli_fetch_assoc($result)) {
            $maxId = $row['max_id'];
            $nextId = ($maxId !== null) ? (int)$maxId + 1 : 1;
        } else {
            $nextId = 1;
        }

        $universidadId = mysqli_real_escape_string($conn, $campus->getTbCampusUniversidadId());
        $nombre = mysqli_real_escape_string($conn, $campus->getTbCampusNombre());

        // Consulta para insertar un nuevo registro
        $queryInsert = "INSERT INTO tbsolicituduniversidadcampus (tbsolicituduniversidadcampusid, tbsolicituduniversidadcampusnombre, tbsolicituduniversidadid, tbsolicituduniversidadcampusestado)
                        VALUES ($nextId, '$nombre','$universidadId', '0')";

        $resultInsert = mysqli_query($conn, $queryInsert);
        mysqli_close($conn);

        return $resultInsert;
    }

    public function autocomplete($term) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');
    
        $sql = "SELECT tbuniversidadcampusnombre FROM tbuniversidadcampus WHERE tbuniversidadcampusnombre LIKE ?";
        $stmt = $conn->prepare($sql);
        $searchTerm = "%$term%";
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $suggestions = [];
        while ($row = $result->fetch_assoc()) {
            $suggestions[] = $row['tbuniversidadcampusnombre'];
        }
    
        $stmt->close();
        $conn->close();
    
        return $suggestions;
    }
} 

