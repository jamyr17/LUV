<?php

include_once 'data.php';
session_start();

class UsuarioData extends Data{

    public function insertTbUsuario($cedula, $primerNombre, $primerApellido, $nombreUsuario, $contrasena){
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
        $queryInsert = "INSERT INTO tbusuario (tbusuarioid, tbpersonaid, tbusuarionombre, tbusuariocontrasena, tbusuarioestado, tbtipousuarioid) 
                        VALUES ($nexUsuariotId, $nextPersonaId, '$nombreUsuario', '$contrasena', 1, 2)";

        $resultInsert = mysqli_query($conn, $queryInsert);
        mysqli_close($conn);

        return $resultInsert;
    }

    public function validation($nombreUsuario, $contrasena){
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryValidation = "SELECT * FROM tbusuario WHERE tbusuarionombre = '$nombreUsuario' AND tbusuariocontrasena = '$contrasena'";
        $result = mysqli_query($conn, $queryValidation);

        return $data = mysqli_fetch_array($result);
    }

    public function getUsuarioId($nombreUsuario) {
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

}