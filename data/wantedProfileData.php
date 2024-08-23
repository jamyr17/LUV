<?php

include_once 'data.php';

class WantedProfileData extends Data{

    public function insertTbPerfilDeseado($criterio, $valor, $porcentaje)
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

        $queryInsert = "INSERT INTO tbperfilusuariodeseado (tbperfilusuariodeseadoid, tbperfilusuariodeseadocriterio, tbperfilusuariodeseadovalor, tbperfilusuariodeseadoporcentaje,  tbperfilusuariodeseadoestado) 
                        VALUES ($nextId, '$criterio', '$valor', '$porcentaje', 1)";

        $resultInsert = mysqli_query($conn, $queryInsert);
        mysqli_close($conn);

        return $resultInsert;
    }

}