<?php
include_once 'data.php';

class UserAffinityData extends Data {

    
    public function insertSegmentacionwithoutGeneroOrientacion($imagenUrl, $region, $duracion, $zoomScale, $criterio, $afinidad, $idUsuario) {
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
    

    public function insertSegmentacion($imagenUrl, $region, $duracion, $zoomScale, $criterio, $afinidad, $afinidadGenero, $afinidadOrientacionSexual, $idUsuario) {
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
                        tbafinidadusuariozoomscale, tbafinidadusuariocriterio, tbafinidadusuarioafinidad, tbafinidadusuario, tbafinidadusuariogenero,
                        tbafinidadusuarioorientacionsexual, tbusuarioid, tbafinidadusuarioestado) 
                        VALUES ($nextId, '$imagenUrl', '$region', '$duracion', '$zoomScale', '$criterio', '$afinidad', '$afinidadGenero', '$afinidadOrientacionSexual' $idUsuario, 1)";
    
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
        mysqli_stmt_bind_param($stmtCheck, 'si', $imagenUrl, $idUsuario);
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

        // Retornar los datos o false si no existe
        return $data ? $data : false;
    }

    public function updateSegmentacion($imagenUrl, $duracion, $zoomScale, $criterio, $afinidad, $afinidadGenero, $afinidadOrientacionSexual, $idUsuario) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
    
        if (!$conn) {
            die("Error al conectar a la base de datos: " . mysqli_connect_error());
        }
    
        $conn->set_charset('utf8');
    
        // Escapar las entradas para evitar inyección SQL
        $duracion = mysqli_real_escape_string($conn, $duracion);
        $zoomScale = mysqli_real_escape_string($conn, $zoomScale);
        $criterio = mysqli_real_escape_string($conn, $criterio);
        $afinidad = mysqli_real_escape_string($conn, $afinidad);
        $imagenUrl = mysqli_real_escape_string($conn, $imagenUrl);
    
        // Actualizar la segmentación existente según imagen y usuario
        $queryUpdate = "UPDATE tbafinidadusuario 
                        SET tbafinidadusuarioduracion = ?, 
                            tbafinidadusuariozoomscale = ?, 
                            tbafinidadusuariocriterio = ?, 
                            tbafinidadusuarioafinidad = ? 
                            tbafinidadusuariogenero = ? 
                            tbafinidadusuarioorientacionsexual = ? 
                        WHERE tbafinidadusuarioimagenurl = ? 
                        AND tbusuarioid = ?";
    
        $stmtUpdate = mysqli_prepare($conn, $queryUpdate);
        mysqli_stmt_bind_param($stmtUpdate, 'dssdsi', $duracion, $zoomScale, $criterio, $afinidad, $imagenUrl, $afinidadGenero, $afinidadOrientacionSexual, $idUsuario);
    
        $result = mysqli_stmt_execute($stmtUpdate);
        if (!$result) {
            die("Error en la actualización de datos: " . mysqli_error($conn));
        }
    
        mysqli_stmt_close($stmtUpdate);
        mysqli_close($conn);
    
        return $result;
    }    


    
    public function updateSegmentacionoorientacion($imagenUrl, $afinidadGenero, $afinidadOrientacionSexual, $idUsuario) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
    
        if (!$conn) {
            die("Error al conectar a la base de datos: " . mysqli_connect_error());
        }
    
        $conn->set_charset('utf8');
    
        // Escapar las entradas para evitar inyección SQL
        $duracion = mysqli_real_escape_string($conn, $duracion);
        $zoomScale = mysqli_real_escape_string($conn, $zoomScale);
        $criterio = mysqli_real_escape_string($conn, $criterio);
        $afinidad = mysqli_real_escape_string($conn, $afinidad);
        $imagenUrl = mysqli_real_escape_string($conn, $imagenUrl);
    
        // Actualizar la segmentación existente según imagen y usuario
        $queryUpdate = "UPDATE tbafinidadusuario 
                        SET tbafinidadusuarioafinidad = ? ,
                            tbafinidadusuariogenero = ? 
                            tbafinidadusuarioorientacionsexual = ? 
                        WHERE tbafinidadusuarioimagenurl = ? 
                        AND tbusuarioid = ?";
    
        $stmtUpdate = mysqli_prepare($conn, $queryUpdate);
        mysqli_stmt_bind_param($stmtUpdate, 'dssdsi', $imagenUrl, $afinidadGenero, $afinidadOrientacionSexual, $idUsuario);
    
        $result = mysqli_stmt_execute($stmtUpdate);
        if (!$result) {
            die("Error en la actualización de datos: " . mysqli_error($conn));
        }
    
        mysqli_stmt_close($stmtUpdate);
        mysqli_close($conn);
    
        return $result;
    }    




    
    public function updateSegmentacionsingeneroorientacion($imagenUrl, $duracion, $zoomScale, $criterio, $afinidad, $idUsuario) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
    
        if (!$conn) {
            die("Error al conectar a la base de datos: " . mysqli_connect_error());
        }
    
        $conn->set_charset('utf8');
    
        // Escapar las entradas para evitar inyección SQL
        $duracion = mysqli_real_escape_string($conn, $duracion);
        $zoomScale = mysqli_real_escape_string($conn, $zoomScale);
        $criterio = mysqli_real_escape_string($conn, $criterio);
        $afinidad = mysqli_real_escape_string($conn, $afinidad);
        $imagenUrl = mysqli_real_escape_string($conn, $imagenUrl);
    
        // Actualizar la segmentación existente según imagen y usuario
        $queryUpdate = "UPDATE tbafinidadusuario 
                        SET tbafinidadusuarioduracion = ?, 
                            tbafinidadusuariozoomscale = ?, 
                            tbafinidadusuariocriterio = ?, 
                            tbafinidadusuarioafinidad = ?
                        WHERE tbafinidadusuarioimagenurl = ? 
                        AND tbusuarioid = ?";
    
        $stmtUpdate = mysqli_prepare($conn, $queryUpdate);
        mysqli_stmt_bind_param($stmtUpdate, 'dssdsi', $duracion, $zoomScale, $criterio, $afinidad, $imagenUrl, $idUsuario);
    
        $result = mysqli_stmt_execute($stmtUpdate);
        if (!$result) {
            die("Error en la actualización de datos: " . mysqli_error($conn));
        }
    
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

    public function insertAfinidadGeneroOrientacion($imagenUrl, $genero, $orientacionSexual, $idUsuario) {
        // Conectar a la base de datos
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
    
        // Verificar conexión
        if (!$conn) {
            die("Error al conectar a la base de datos: " . mysqli_connect_error());
        }
    
        $conn->set_charset('utf8');
    
        // Verificar si ya existe un registro de afinidad para el usuario e imagen
        $queryCheck = "SELECT tbafinidadusuarioid FROM tbafinidadusuario WHERE tbafinidadusuarioimagenurl = ? AND tbusuarioid = ?";
        $stmtCheck = mysqli_prepare($conn, $queryCheck);
        mysqli_stmt_bind_param($stmtCheck, 'si', $imagenUrl, $idUsuario);
        mysqli_stmt_execute($stmtCheck);
        mysqli_stmt_bind_result($stmtCheck, $existingId);
        mysqli_stmt_fetch($stmtCheck);
        mysqli_stmt_close($stmtCheck);
    
        // Si ya existe, actualizar el registro
        if ($existingId) {
            $queryUpdate = "UPDATE tbafinidadusuario 
                            SET tbafinidadusuariogenero = ?, tbafinidadusuarioorientacionsexual = ? 
                            WHERE tbafinidadusuarioid = ?";
            $stmtUpdate = mysqli_prepare($conn, $queryUpdate);
            mysqli_stmt_bind_param($stmtUpdate, 'ssi', $genero, $orientacionSexual, $existingId);
            $result = mysqli_stmt_execute($stmtUpdate);
            mysqli_stmt_close($stmtUpdate);
        } else {
            // Si no existe, crear un nuevo registro
            // No es necesario obtener el último ID de esta manera, podemos usar auto-incremento
            $queryInsert = "INSERT INTO tbafinidadusuario (tbafinidadusuarioimagenurl, tbafinidadusuariogenero, tbafinidadusuarioorientacionsexual, tbusuarioid, tbafinidadusuarioestado)
                            VALUES (?, ?, ?, ?, 1)";
            $stmtInsert = mysqli_prepare($conn, $queryInsert);
            mysqli_stmt_bind_param($stmtInsert, 'sssi', $imagenUrl, $genero, $orientacionSexual, $idUsuario);
            $result = mysqli_stmt_execute($stmtInsert);
            mysqli_stmt_close($stmtInsert);
        }
    
        mysqli_close($conn);
    
        // Devolver el resultado de la operación
        return isset($result) ? $result : false;
    }
    
    public function isProfileModeled($userId) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');
    
        // Consulta para verificar si hay un registro en tbperfilusuariopersonal para este usuario
        $query = "SELECT COUNT(*) as count FROM tbperfilusuariopersonal WHERE tbusuarioid = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'i', $userId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $count);
        mysqli_stmt_fetch($stmt);
    
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    
        return $count > 0; // Retorna verdadero si ya existe un perfil
    }
    
}
?>
