<?php

include_once 'data.php';

class WantedProfileData extends Data{

    public function insertTbPerfilDeseado($criterio, $valor, $porcentaje, $usuarioId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryGetLastId = "SELECT MAX(tbperfilusuariodeseadoid) AS max_id FROM tbperfilusuariodeseado";
        $result = mysqli_query($conn, $queryGetLastId);

        if ($row = mysqli_fetch_assoc($result)) {
            $maxId = $row['max_id'];
            $nextId = ($maxId !== null) ? (int)$maxId + 1 : 1;
        } else {
            $nextId = 1;
        }

        $queryInsert = "INSERT INTO tbperfilusuariodeseado (tbperfilusuariodeseadoid, tbperfilusuariodeseadocriterio, tbperfilusuariodeseadovalor, tbperfilusuariodeseadoporcentaje, tbusuarioid, tbperfilusuariodeseadoestado) 
                        VALUES ($nextId, '$criterio', '$valor', '$porcentaje', '$usuarioId', 1)";

        $resultInsert = mysqli_query($conn, $queryInsert);
        mysqli_close($conn);

        return $resultInsert;
    }

    public function getAllTbPerfiles() {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $querySelect = "SELECT * FROM tbperfilusuariopersonal WHERE tbperfilusuariopersonalestado = 1;";
        $result = mysqli_query($conn, $querySelect);

        $profiles = [];
        while ($row = mysqli_fetch_array($result)) {
            $profile = [
                'id' => $row['tbperfilusuariopersonalid'],
                'criterio' => $row['tbperfilusuariopersonalcriterio'],
                'valor' => $row['tbperfilusuariopersonalvalor'],
                'usuarioId' => $row['tbusuarioid'],
                'estado' => $row['tbperfilusuariopersonalestado']
            ];
            array_push($profiles, $profile);
        }

        mysqli_close($conn);
        return $profiles;
    }

    public function profileExists($usuarioId) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $query = "SELECT tbperfilusuariodeseadoid FROM tbperfilusuariodeseado WHERE tbusuarioid = '$usuarioId' AND tbperfilusuariodeseadoestado = 1 LIMIT 1;";
        $result = mysqli_query($conn, $query);

        $exists = mysqli_num_rows($result) > 0;

        mysqli_close($conn);
        return $exists;
    }

    public function updateTbPerfilDeseado($criterio, $valor, $porcentaje, $usuarioId) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryUpdate = "UPDATE tbperfilusuariodeseado 
                        SET tbperfilusuariodeseadocriterio = '$criterio', tbperfilusuariodeseadovalor = '$valor', tbperfilusuariodeseadoporcentaje = '$porcentaje'
                        WHERE tbusuarioid = '$usuarioId' AND tbperfilusuariodeseadoestado = 1";

        $resultUpdate = mysqli_query($conn, $queryUpdate);
        mysqli_close($conn);

        return $resultUpdate;
    }
    
}