<?php
include_once 'data.php';

class AfinidadImagenData extends Data {

    public function insertSegmentacion($imagenUrl, $region, $duracion, $zoomScale, $criterio, $idUsuario) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryInsert = "INSERT INTO tbafinidadusuario (tbafinidadusuarioimagenurl, tbafinidadusuarioregion, tbafinidadusuarioduracion, tbafinidadusuariozoomscale, tbafinidadusuariocriterio, tbafinidadusuarioid) 
                        VALUES (?, ?, ?, ?, ?, ?)";
        $stmtInsert = mysqli_prepare($conn, $queryInsert);
        mysqli_stmt_bind_param($stmtInsert, 'ssdssi', $imagenUrl, $region, $duracion, $zoomScale, $criterio, $idUsuario);

        $result = mysqli_stmt_execute($stmtInsert);
        mysqli_stmt_close($stmtInsert);
        mysqli_close($conn);

        return $result;
    }

    public function updateSegmentacion($id, $duracion, $zoomScale, $criterio, $idUsuario) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryUpdate = "UPDATE tbafinidadusuario SET tbafinidadusuarioduracion = tbafinidadusuarioduracion + ?, tbafinidadusuariozoomscale = ?, tbafinidadusuariocriterio = ? 
                        WHERE tbafinidadusuarioimagenurl = ? AND tbafinidadusuarioid = ?";
        $stmtUpdate = mysqli_prepare($conn, $queryUpdate);
        mysqli_stmt_bind_param($stmtUpdate, 'dssii', $duracion, $zoomScale, $criterio, $id, $idUsuario);

        $result = mysqli_stmt_execute($stmtUpdate);
        mysqli_stmt_close($stmtUpdate);
        mysqli_close($conn);

        return $result;
    }

    public function checkIfExists($imagenUrl, $region, $idUsuario) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryCheck = "SELECT tbafinidadusuarioid, tbafinidadusuarioduracion FROM tbafinidadusuario WHERE tbafinidadusuarioimagenurl = ? AND tbafinidadusuarioregion = ? AND tbafinidadusuarioid = ?";
        $stmtCheck = mysqli_prepare($conn, $queryCheck);
        mysqli_stmt_bind_param($stmtCheck, 'ssi', $imagenUrl, $region, $idUsuario);
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
    
    public function getSegmentacionesByImagenYUsuario($imagenUrl, $idUsuario) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $query = "SELECT tbafinidadusuarioregion, tbafinidadusuarioduracion, tbafinidadusuariozoomscale, tbafinidadusuariocriterio 
                  FROM tbafinidadusuario WHERE tbafinidadusuarioimagenurl = ? AND tbafinidadusuarioid = ? AND tbafinidadusuarioestado = 1";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'si', $imagenUrl, $idUsuario);
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
