<?php
include_once 'data.php';

class UserAffinityData extends Data {

    public function insertSegmentacion($imagenUrl, $region, $duracion, $zoomScale, $criterio, $afinidad, $idUsuario) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        
        if (!$conn) {
            die("Error al conectar a la base de datos: " . mysqli_connect_error());
        }
    
        $conn->set_charset('utf8');
    
        $queryGetLastId = "SELECT MAX(tbafinidadusuarioid) AS max_id FROM tbafinidadusuario";
        $result = mysqli_query($conn, $queryGetLastId);
    
        if (!$result) {
            die("Error en la consulta de obtención del último ID: " . mysqli_error($conn));
        }
    
        if ($row = mysqli_fetch_assoc($result)) {
            $maxId = $row['max_id'];
            $nextId = ($maxId !== null) ? (int)$maxId + 1 : 1;
        } else {
            $nextId = 1;
        }
    
        // Escapar las entradas para evitar inyección SQL
        $imagenUrl = mysqli_real_escape_string($conn, $imagenUrl);
        $region = mysqli_real_escape_string($conn, $region);
        $duracion = mysqli_real_escape_string($conn, $duracion);
        $zoomScale = mysqli_real_escape_string($conn, $zoomScale);
        $criterio = mysqli_real_escape_string($conn, $criterio);
        $afinidad = mysqli_real_escape_string($conn, $afinidad);
        $idUsuario = (int)$idUsuario;
    
        // Crear la consulta INSERT con los valores concatenados
        $queryInsert = "INSERT INTO tbafinidadusuario 
                        (tbafinidadusuarioid, tbafinidadusuarioimagenurl, tbafinidadusuarioregion, tbafinidadusuarioduracion, 
                        tbafinidadusuariozoomscale, tbafinidadusuariocriterio, tbafinidadusuarioafinidad, tbusuarioid, tbafinidadusuarioestado) 
                        VALUES ($nextId, '$imagenUrl', '$region', '$duracion', '$zoomScale', '$criterio', '$afinidad', $idUsuario, 1)";
    
        // Ejecutar la consulta
        $resultInsert = mysqli_query($conn, $queryInsert);
    
        if (!$resultInsert) {
            die("Error en la inserción de datos: " . mysqli_error($conn));
        }
    
        mysqli_close($conn);
    
        return $resultInsert;
    }
    
    
    
    public function checkIfExists($imagenUrl, $idUsuario) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryCheck = "SELECT tbafinidadusuarioid, tbafinidadusuarioduracion FROM tbafinidadusuario WHERE tbafinidadusuarioimagenurl = ? AND tbusuarioid = ?";
        $stmtCheck = mysqli_prepare($conn, $queryCheck);
        mysqli_stmt_bind_param($stmtCheck, 'ssi', $imagenUrl, $idUsuario);
        mysqli_stmt_execute($stmtCheck);
        mysqli_stmt_bind_result($stmtCheck, $id, $existingDuracion);
        mysqli_stmt_fetch($stmtCheck);

        $data = null;
        if ($id) {
            $data = [
                'id' => $id,
                'duracion' => $existingDuracion
            ];
        }

        mysqli_stmt_close($stmtCheck);
        mysqli_close($conn);

        return $data;
    }

    public function updateSegmentacion($id, $duracion, $zoomScale, $criterio, $idUsuario) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryUpdate = "UPDATE tbafinidadusuario SET tbafinidadusuarioduracion = tbafinidadusuarioduracion + ?, tbafinidadusuariozoomscale = ?, tbafinidadusuariocriterio = ? 
                        WHERE tbafinidadusuarioimagenurl = ? AND tbusuarioid = ?";
        $stmtUpdate = mysqli_prepare($conn, $queryUpdate);
        mysqli_stmt_bind_param($stmtUpdate, 'dssii', $duracion, $zoomScale, $criterio, $id, $idUsuario);

        $result = mysqli_stmt_execute($stmtUpdate);
        mysqli_stmt_close($stmtUpdate);
        mysqli_close($conn);

        return $result;
    }

    public function getSegmentacionesByUsuarioAndUrl($idUsuario, $imagenUrl) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $query = "SELECT tbafinidadusuarioimagenurl, tbafinidadusuarioregion, tbafinidadusuarioduracion, tbafinidadusuariozoomscale, tbafinidadusuariocriterio 
                  FROM tbafinidadusuario WHERE tbafinidadusuarioid = ? AND tbafinidadusuarioimagenurl = ? AND tbafinidadusuarioestado = 1";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'i', $idUsuario, $imagenUrl);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $segmentaciones = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $segmentaciones[] = $row;
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        return $segmentaciones;
    }

    public function getSegmentacionesByUsuario($idUsuario) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $query = "SELECT tbafinidadusuarioimagenurl, tbafinidadusuarioregion, tbafinidadusuarioduracion, tbafinidadusuariozoomscale, tbafinidadusuariocriterio 
                  FROM tbafinidadusuario WHERE tbafinidadusuarioid = ? AND tbafinidadusuarioestado = 1";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'i', $idUsuario);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $segmentaciones = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $segmentaciones[] = $row;
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        return $segmentaciones;
    }

}
?>
