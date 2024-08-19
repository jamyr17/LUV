<?php

include_once 'data.php';
include '../domain/imagen.php';

class ImagenData extends Data
{

    public function insertTbImagen($imagen)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        // Consulta para obtener el ID máximo
        $queryGetLastId = "SELECT MAX(tbimagenid) AS max_id FROM tbimagen";
        $result = mysqli_query($conn, $queryGetLastId);

        if ($row = mysqli_fetch_assoc($result)) {
            // Obtén el ID máximo o establece 0 si la tabla está vacía
            $maxId = $row['max_id'];
            $nextId = ($maxId !== null) ? (int)$maxId + 1 : 1;
        } else {
            // Si no se obtiene resultado, asegúrate de manejar el error adecuadamente
            $nextId = 1;
        }

        $nombre = mysqli_real_escape_string($conn, $imagen->gettbimagenNombre());
        $crudId = mysqli_real_escape_string($conn, $imagen->gettbimagenCrudId());
        $registroId = mysqli_real_escape_string($conn, $imagen->gettbimagenRegistroId());
        $directorio = mysqli_real_escape_string($conn, $imagen->gettbimagenDirectorio());
        $estado = 1;

        // Consulta para insertar un nuevo registro
        $queryInsert = "INSERT INTO tbimagen (tbimagenid, tbimagencrudid, tbimagenregistroid, tbimagendirectorio, tbimagennombre, tbimagenestado) 
                        VALUES ($nextId, '$crudId', '$registroId', '$directorio', '$nombre', $estado)";

        $resultInsert = mysqli_query($conn, $queryInsert);
        mysqli_close($conn);

        return $resultInsert;
    }

}
