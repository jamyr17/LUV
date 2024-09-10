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
    
        // Modifica la consulta para hacer un JOIN con las tablas necesarias
        $querySelect = "
            SELECT 
                p.tbperfilusuariopersonalid AS id,
                p.tbperfilusuariopersonalcriterio AS criterio,
                p.tbperfilusuariopersonalvalor AS valor,
                p.tbusuarioid AS usuarioId,
                p.tbperfilusuariopersonalestado AS estado,
                u.tbusuarionombre AS nombreUsuario,
                u.tbusuarioimagen AS pfp,
                per.tbpersonaprimernombre AS primerNombre,
                per.tbpersonaprimerapellido AS primerApellido
            FROM 
                tbperfilusuariopersonal p
            JOIN 
                tbusuario u ON p.tbusuarioid = u.tbusuarioid
            JOIN 
                tbpersona per ON u.tbpersonaid = per.tbpersonaid
            WHERE 
                p.tbperfilusuariopersonalestado = 1;
        ";
    
        $result = mysqli_query($conn, $querySelect);
    
        $profiles = [];
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $profile = [
                'id' => $row['id'],
                'criterio' => $row['criterio'],
                'valor' => $row['valor'],
                'usuarioId' => $row['usuarioId'],
                'estado' => $row['estado'],
                'nombreUsuario' => $row['nombreUsuario'],
                'primerNombre' => $row['primerNombre'],
                'primerApellido' => $row['primerApellido'],
                'pfp' => $row['pfp']
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


    
    public function perfilDeseadoByIdUsuario($usuarioId) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
    
        if (!$conn) {
            // Error de conexión a la base de datos
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(['error' => 'Error de conexión a la base de datos']);
            exit();  // Asegura que el script se detiene después de enviar la respuesta
        }
    
        $conn->set_charset('utf8');
    
        // Evitar inyección SQL
        $usuarioId = mysqli_real_escape_string($conn, $usuarioId);
    
        $query = "SELECT tbperfilusuariodeseadocriterio, tbperfilusuariodeseadovalor 
                  FROM tbperfilusuariodeseado 
                  WHERE tbusuarioid = '$usuarioId' AND tbperfilusuariodeseadoestado = 1";
    
        $result = mysqli_query($conn, $query);
    
        if (!$result) {
            // Error en la consulta SQL
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(['error' => 'Error en la consulta SQL']);
            mysqli_close($conn);
            exit();  // Asegura que el script se detiene después de enviar la respuesta
        }
    
        $data = [];
    
        while ($row = mysqli_fetch_assoc($result)) {
            $criterios = explode(',', $row['tbperfilusuariodeseadocriterio']);
            $valores = explode(',', $row['tbperfilusuariodeseadovalor']);
    
            for ($i = 0; $i < count($criterios); $i++) {
                $data[] = [
                    'criterio' => $criterios[$i],
                    'valor' => $valores[$i] ?? null
                ];
            }
        }
    
        mysqli_close($conn);
    
        // Verificar si hay datos
        if (empty($data)) {
            // No se encontraron datos para el usuario
            header('HTTP/1.1 404 Not Found');
            echo json_encode(['error' => 'No se encontraron datos para el usuario']);
            exit();  // Asegura que el script se detiene después de enviar la respuesta
        }
    
        // Si hay datos, enviarlos en formato JSON
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();  // Asegura que no se ejecuta ningún código adicional después de la respuesta
    }
    
    
    
}