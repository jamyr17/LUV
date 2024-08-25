<?php

include_once 'data.php';

class PersonalProfileData extends Data{

    public function insertTbPerfilPersonal($criterio, $valor)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryGetLastId = "SELECT MAX(tbperfilusuariopersonalid) AS max_id FROM tbperfilusuariopersonal";
        $result = mysqli_query($conn, $queryGetLastId);

        if ($row = mysqli_fetch_assoc($result)) {
            $maxId = $row['max_id'];
            $nextId = ($maxId !== null) ? (int)$maxId + 1 : 1;
        } else {
            $nextId = 1;
        }

        $queryInsert = "INSERT INTO tbperfilusuariopersonal (tbperfilusuariopersonalid, tbperfilusuariopersonalcriterio, tbperfilusuariopersonalvalor,  tbperfilusuariopersonalestado) 
                        VALUES ($nextId, '$criterio', '$valor', 1)";

        $resultInsert = mysqli_query($conn, $queryInsert);
        mysqli_close($conn);

        return $resultInsert;
    }

}