<?php

include_once 'data.php';

class PersonalProfileData extends Data{

    public function insertTbPerfilPersonal($criterio, $valor, $usuarioId)
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

        $queryInsert = "INSERT INTO tbperfilusuariopersonal (tbperfilusuariopersonalid, tbperfilusuariopersonalcriterio, tbperfilusuariopersonalvalor, tbusuarioid, tbperfilusuariopersonalestado) 
                        VALUES ($nextId, '$criterio', '$valor','$usuarioId', 1)";

        $resultInsert = mysqli_query($conn, $queryInsert);
        mysqli_close($conn);

        return $resultInsert;
    }

    public function profileExists($usuarioId) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $query = "SELECT tbperfilusuariopersonalid FROM tbperfilusuariopersonal WHERE tbusuarioid = '$usuarioId' AND tbperfilusuariopersonalestado = 1 LIMIT 1;";
        $result = mysqli_query($conn, $query);

        $exists = mysqli_num_rows($result) > 0;

        mysqli_close($conn);
        return $exists;
    }

    public function updateTbPerfilPersonal($criterio, $valor, $usuarioId) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryUpdate = "UPDATE tbperfilusuariopersonal 
                        SET tbperfilusuariopersonalcriterio = '$criterio', tbperfilusuariopersonalvalor = '$valor'
                        WHERE tbusuarioid = '$usuarioId' AND tbperfilusuariopersonalestado = 1";

        $resultUpdate = mysqli_query($conn, $queryUpdate);
        mysqli_close($conn);

        return $resultUpdate;
    }

}