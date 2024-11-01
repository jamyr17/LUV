<?php

include_once 'data.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class UsuarioData extends Data
{

    public function insertTbUsuario($cedula, $primerNombre, $primerApellido, $nombreUsuario, $contrasena, $rutaImagen)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        // Consulta para obtener el ID máximo de tbpersona
        $queryGetLastId = "SELECT MAX(tbpersonaid) AS max_id FROM tbpersona";
        $resultPersonaId = mysqli_query($conn, $queryGetLastId);

        if ($row = mysqli_fetch_assoc($resultPersonaId)) {
            $maxId = $row['max_id'];
            $nextPersonaId = ($maxId !== null) ? (int)$maxId + 1 : 1;
        } else {
            $nextPersonaId = 1;
        }

        // Consulta para insertar un nuevo registro en tbpersona
        $queryInsert = "INSERT INTO tbpersona (tbpersonaid, tbpersonacedula, tbpersonaprimernombre, tbpersonaprimerapellido, tbpersonaestado) 
                        VALUES ($nextPersonaId, '$cedula', '$primerNombre', '$primerApellido', 1)";
        $resultInsert = mysqli_query($conn, $queryInsert);

        // Consulta para obtener el ID máximo de tbusuario
        $queryGetLastId = "SELECT MAX(tbusuarioid) AS max_id FROM tbusuario";
        $resultUsuarioId = mysqli_query($conn, $queryGetLastId);

        if ($row = mysqli_fetch_assoc($resultUsuarioId)) {
            $maxId = $row['max_id'];
            $nexUsuariotId = ($maxId !== null) ? (int)$maxId + 1 : 1;
        } else {
            $nexUsuariotId = 1;
        }

        // Consulta para insertar un nuevo registro en tbusuario
        $queryInsert = "INSERT INTO tbusuario (tbusuarioid, tbpersonaid, tbusuarionombre, tbusuariocontrasena, tbusuarioestado, tbtipousuarioid, tbusuarioimagen) 
                        VALUES ($nexUsuariotId, $nextPersonaId, '$nombreUsuario', '$contrasena', 1, 2, '$rutaImagen')";

        $resultInsert = mysqli_query($conn, $queryInsert);
        mysqli_close($conn);

        return $resultInsert;
    }

    public function validation($nombreUsuario, $contrasena)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryValidation = "SELECT * FROM tbusuario WHERE tbusuarionombre = '$nombreUsuario' AND tbusuariocontrasena = '$contrasena'";
        $result = mysqli_query($conn, $queryValidation);

        return $data = mysqli_fetch_array($result);
    }

    public function getUsuarioId($nombreUsuario)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $nombreUsuario = mysqli_real_escape_string($conn, $nombreUsuario);

        $query = "SELECT tbusuarioid FROM tbusuario WHERE tbusuarionombre = '$nombreUsuario'";
        $result = mysqli_query($conn, $query);

        if ($row = mysqli_fetch_assoc($result)) {
            return $row['tbusuarioid'];
        } else {
            return null;
        }
    }

    public function existPerson($cedula,)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $query = "SELECT COUNT(*) as count FROM tbpersona WHERE tbpersonacedula = ?";

        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 's', $cedula);

        mysqli_stmt_execute($stmt);

        mysqli_stmt_bind_result($stmt, $count);
        mysqli_stmt_fetch($stmt);

        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        return $count > 0;
    }

    public function existUsername($nombreUsuario,)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $query = "SELECT COUNT(*) as count FROM tbusuario WHERE tbusuarionombre = ?";

        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 's', $nombreUsuario);

        mysqli_stmt_execute($stmt);

        mysqli_stmt_bind_result($stmt, $count);
        mysqli_stmt_fetch($stmt);

        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        return $count > 0;
    }

    public function actualizarCondicion($usuarioId, $condicion) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');
    
        $query = "UPDATE tbusuario SET tbusuariocondicion = '$condicion' WHERE tbusuarioid = $usuarioId";
        $result = mysqli_query($conn, $query);
        mysqli_close($conn);
        return $result;
    }

    public function getTbAfinidadUsuarioGenero($usuarioId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        // Consulta para obtener los géneros de afinidad según el usuarioId
        $query = "SELECT tbafinidadusuariogenero FROM tbafinidadusuario WHERE tbusuarioid = ?";
        
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'i', $usuarioId);

        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $generoString);
        mysqli_stmt_fetch($stmt);

        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        // Convertir la cadena en un arreglo de géneros
        $generos = $generoString ? explode(', ', $generoString) : [];

        return $generos;
    }

    public function getTbAfinidadUsuarioOrientacionSexual($usuarioId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        // Consulta para obtener las orientaciones sexuales de afinidad según el usuarioId
        $query = "SELECT tbafinidadusuarioorientacionsexual FROM tbafinidadusuario WHERE tbusuarioid = ?";
        
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'i', $usuarioId);

        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $orientacionString);
        mysqli_stmt_fetch($stmt);

        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        // Convertir la cadena en un arreglo de orientaciones sexuales
        $orientaciones = $orientacionString ? explode(', ', $orientacionString) : [];

        return $orientaciones;
    }


    public function getUsernamesByGenderAndOrientation($generos, $orientaciones){
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        // Crear placeholders para los géneros y orientaciones
        $generoPlaceholders = implode(',', array_fill(0, count($generos), '?'));
        $orientacionPlaceholders = implode(',', array_fill(0, count($orientaciones), '?'));

        // Consulta SQL para buscar los usuarios que coincidan con el género y la orientación
        $query = "SELECT usuario.tbusuarionombre
                FROM tbusuario usuario
                INNER JOIN tbperfilusuariopersonal perfilpersonal
                ON usuario.tbusuarioid = perfilpersonal.tbusuarioid 
                WHERE perfilpersonal.tbgenero IN ($generoPlaceholders) 
                AND perfilpersonal.tborientacionsexual IN ($orientacionPlaceholders)";

        $stmt = mysqli_prepare($conn, $query);

        // Vincular los valores de los géneros y orientaciones a los placeholders
        $types = str_repeat('s', count($generos) + count($orientaciones));
        $params = array_merge($generos, $orientaciones);
        mysqli_stmt_bind_param($stmt, $types, ...$params);

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // Obtener los usuarios encontrados
        $usuarios = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $usuarios[] = $row;
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        return $usuarios;
    }

}