<?php

include_once 'data.php';

class LoginData extends Data{

    public function validation($nombreUsuario, $contrasena){
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryValidation = "SELECT * FROM tbusuario WHERE tbusuarionombre = '$nombreUsuario' AND tbusuariocontrasena = '$contrasena'";
        $result = mysqli_query($conn, $queryValidation);

        return $data = mysqli_fetch_array($result);
    }

}