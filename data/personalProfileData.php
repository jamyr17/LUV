<?php

include_once 'data.php';

class PersonalProfileData extends Data
{

    public function insertTbPerfilPersonal($criterio, $valor,  $areaConocimiento, $genero, $orientacionSexual, $universidad, $campus, $colectivosString, $usuarioId)
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

        $queryInsert = "INSERT INTO tbperfilusuariopersonal (tbperfilusuariopersonalid, tbperfilusuariopersonalcriterio, tbperfilusuariopersonalvalor, tbareaconocimiento, tbgenero, tborientacionsexual, tbuniversidad, tbuniversidadcampus, tbuniversidadcampuscolectivo, tbusuarioid, tbperfilusuariopersonalestado) 
                        VALUES ($nextId, '$criterio', '$valor', '$areaConocimiento', '$genero', '$orientacionSexual', '$universidad', '$campus', '$colectivosString', '$usuarioId', 1)";

        $resultInsert = mysqli_query($conn, $queryInsert);
        mysqli_close($conn);

        return $resultInsert;
    }

    public function profileExists($usuarioId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $query = "SELECT tbperfilusuariopersonalid FROM tbperfilusuariopersonal WHERE tbusuarioid = '$usuarioId' AND tbperfilusuariopersonalestado = 1 LIMIT 1;";
        $result = mysqli_query($conn, $query);

        $exists = mysqli_num_rows($result) > 0;

        mysqli_close($conn);
        return $exists;
    }

    public function updateTbPerfilPersonal($criterio, $valor, $areaConocimiento, $genero, $orientacionSexual, $universidad, $campus, $colectivosString, $usuarioId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryUpdate = "UPDATE tbperfilusuariopersonal 
                        SET 
                            tbperfilusuariopersonalcriterio = '$criterio',
                            tbperfilusuariopersonalvalor = '$valor',
                            tbareaconocimiento = '$areaConocimiento',
                            tbgenero = '$genero',
                            tborientacionsexual = '$orientacionSexual',
                            tbuniversidad = '$universidad',
                            tbuniversidadcampus = '$campus',
                            tbuniversidadcampuscolectivo = '$colectivosString',
                            tbusuarioid = '$usuarioId'
                        WHERE 
                            tbusuarioid = '$usuarioId' AND tbperfilusuariopersonalestado = 1";

        $resultUpdate = mysqli_query($conn, $queryUpdate);
        mysqli_close($conn);

        return $resultUpdate;
    }

    public function perfilPersonalByIdUsuario($usuarioId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);

        if (!$conn) {
            // Error de conexión a la base de datos
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(['error' => 'Error de conexión a la base de datos']);
            exit();  // Asegura que el script se detiene después de enviar la respuesta
        }

        $conn->set_charset('utf8');

        // Consulta parametrizada para evitar inyección SQL
        $query = "SELECT tbperfilusuariopersonalcriterio, tbperfilusuariopersonalvalor 
                  FROM tbperfilusuariopersonal 
                  WHERE tbusuarioid = ? AND tbperfilusuariopersonalestado = 1";

        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'i', $usuarioId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $data = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $criterios = explode(',', $row['tbperfilusuariopersonalcriterio']);
            $valores = explode(',', $row['tbperfilusuariopersonalvalor']);

            for ($i = 0; $i < count($criterios); $i++) {
                $data[] = [
                    'criterio' => $criterios[$i],
                    'valor' => $valores[$i] ?? null  // Manejar valores faltantes
                ];
            }
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        // Verificar si hay datos
        if (empty($data)) {
            header('HTTP/1.1 404 Not Found');
            echo json_encode(['error' => 'No se encontraron datos para el usuario']);
            exit();
        }

        // Enviar los datos en formato JSON
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    public function puedeBuscarConexiones($usuarioId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);

        if (!$conn) {
            // Error de conexión a la base de datos
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(['error' => 'Error de conexión a la base de datos']);
            exit();
        }

        $conn->set_charset('utf8');

        // Consulta parametrizada para evitar inyección SQL

        // tiene que estar en tbperfilusuariopersonal, tbperfilusuariodeseado y tbafinidadusuario
        $query = "SELECT 
                    (CASE 
                        WHEN COUNT(*) = 3 THEN TRUE 
                        ELSE FALSE 
                    END) AS puedeBuscarConexiones
                FROM (
                    SELECT tbusuarioid 
                    FROM tbperfilusuariopersonal WHERE tbusuarioid = ?
                    UNION ALL
                    SELECT tbusuarioid 
                    FROM tbperfilusuariodeseado WHERE tbusuarioid = ?
                    UNION ALL
                    SELECT tbusuarioid 
                    FROM tbafinidadusuario WHERE tbusuarioid = ?
                ) AS combinados";

        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'iii', $usuarioId, $usuarioId, $usuarioId); // Usando 'iii' si todos son enteros
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // Obtener el resultado
        $row = mysqli_fetch_assoc($result);
        $puedeBuscarConexiones = $row['puedeBuscarConexiones'];

        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        // Verificar si hay datos
        if (!$puedeBuscarConexiones) {
            header('HTTP/1.1 404 Not Found');
            echo json_encode(['error' => 'El usuario no cuenta con su informacion completa']);
            exit();
        }

        // Enviar los datos en formato JSON
        header('Content-Type: application/json');
        echo json_encode($puedeBuscarConexiones);
        exit();
    }
}
