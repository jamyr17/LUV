<?php

include_once 'data.php';
include '../domain/imagenDomain.php';

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

    public function updateTbImagen($imagen)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $id = intval($imagen->getTbImagenId());
        $nombre = mysqli_real_escape_string($conn, $imagen->gettbimagenNombre());
        $crudId = mysqli_real_escape_string($conn, $imagen->gettbimagenCrudId());
        $registroId = mysqli_real_escape_string($conn, $imagen->gettbimagenRegistroId());
        $directorio = mysqli_real_escape_string($conn, $imagen->gettbimagenDirectorio());
        $estado = 1;

        $queryUpdate = "UPDATE tbimagen SET tbimagennombre='$nombre', tbimagencrudid='$crudId', tbimagenregistroid='$registroId', tbimagendirectorio='$directorio', tbimagenestado='$estado' WHERE tbimagenid=$id;";

        $result = mysqli_query($conn, $queryUpdate);
        mysqli_close($conn);

        return $result;
    }

    public function getAllTbImagen()
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $querySelect = "SELECT * FROM tbimagen WHERE tbimagenestado = 1;";
        $result = mysqli_query($conn, $querySelect);
        mysqli_close($conn);

        $imagen = [];
        while ($row = mysqli_fetch_array($result)) {
            $imagenActual = new Imagen($row['tbimagenid'], $row['tbimagencrudid'], $row['tbimagenregistroid'], $row['tbimagennombre'], $row['tbimagendirectorio'], $row['tbimagenestado']);
            array_push($imagen, $imagenActual);
        }

        return $imagen;
    }

    public function deleteTbImagen($imagenId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryDelete = "UPDATE tbimagen SET tbimagenestado = '0' WHERE tbimagenid=$imagenId;";
        $result = mysqli_query($conn, $queryDelete);
        mysqli_close($conn);

        return $result;
    }

    public function exist($nombre)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $query = "SELECT COUNT(*) as count FROM tbimagen WHERE tbimagennombre = ?";

        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 's', $nombre);

        mysqli_stmt_execute($stmt);

        mysqli_stmt_bind_result($stmt, $count);
        mysqli_stmt_fetch($stmt);

        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        return $count > 0;
    }

    public function getTbImagenById($id)
    {
        $conn = new mysqli($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $stmt = $conn->prepare("SELECT * FROM tbimagen WHERE tbimagenid = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $imagen = new Imagen(
                $row['tbimagenid'],
                $row['tbimagencrudid'],
                $row['tbimagenregistroid'],
                $row['tbimagennombre'],
                $row['tbimagendirectorio'],
                $row['tbimagenestado']
            );
        } else {
            $imagen = null; // O maneja el caso en que no se encuentra la imagen
        }

        $stmt->close();
        $conn->close();

        return $imagen;
    }
}
