<?php

include_once 'data.php';
session_start();

class UsuarioData extends Data{

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
    

}