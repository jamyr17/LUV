<?php

include_once 'data.php';
include_once '../domain/universidadCampusColectivoDomain.php';

class universidadCampusColectivoData extends Data
{

    public function insertTbUniversidadCampusColectivo($universidadCampusColectivo)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        // Consulta para obtener el ID máximo
        $queryGetLastId = "SELECT MAX(tbuniversidadcampuscolectivoid) AS max_id FROM tbuniversidadcampuscolectivo";
        $result = mysqli_query($conn, $queryGetLastId);

        if ($row = mysqli_fetch_assoc($result)) {
            // Obtén el ID máximo o establece 0 si la tabla está vacía
            $maxId = $row['max_id'];
            $nextId = ($maxId !== null) ? (int)$maxId + 1 : 1;
        } else {
            // Si no se obtiene resultado, asegúrate de manejar el error adecuadamente
            $nextId = 1;
        }

        $nombre = mysqli_real_escape_string($conn, $universidadCampusColectivo->getTbUniversidadCampusColectivoNombre());
        $descripcion = mysqli_real_escape_string($conn, $universidadCampusColectivo->getTbUniversidadCampusColectivoDescripcion());
        $estado = mysqli_real_escape_string($conn, $universidadCampusColectivo->getTbUniversidadCampusColectivoEstado());

        $queryInsert = "INSERT INTO tbuniversidadcampuscolectivo (tbuniversidadcampuscolectivoid, tbuniversidadcampuscolectivonombre, tbuniversidadcampuscolectivodescripcion, tbuniversidadcampuscolectivoestado) 
                        VALUES ($nextId, '$nombre', '$descripcion', $estado)";

        $resultInsert = mysqli_query($conn, $queryInsert);

        mysqli_close($conn);

        return ['result' => $resultInsert, 'id' => $nextId];
    }

    public function updateTbUniversidadCampusColectivo($universidadCampusColectivo)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $id = intval($universidadCampusColectivo->getTbUniversidadCampusColectivoId()); // Asegúrate de que $id sea un entero
        $nombre = $universidadCampusColectivo->getTbUniversidadCampusColectivoNombre();
        $descripcion = $universidadCampusColectivo->getTbUniversidadCampusColectivoDescripcion();

        $queryUpdate = "UPDATE tbuniversidadcampuscolectivo SET tbuniversidadcampuscolectivonombre='$nombre', tbuniversidadcampuscolectivodescripcion='$descripcion' WHERE tbuniversidadcampuscolectivoid=$id;";

        $result = mysqli_query($conn, $queryUpdate);
        mysqli_close($conn);

        return $result;
    }
    /*
    public function deleteTbUniversidadCampusColectivo($universidadCampusColectivoId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryDelete = "UPDATE tbuniversidadcampuscolectivo SET tbuniversidadcampuscolectivoestado = '0' WHERE tbuniversidadcampuscolectivoid=$universidadCampusColectivoId;";
        $result = mysqli_query($conn, $queryDelete);
        mysqli_close($conn);

        return $result;
        }
*/
    public function checkAssociatedCampusColectivo($colectivoId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        // Verificar cuántos campus activos están asociados al colectivo
        $queryCountCampus = "SELECT COUNT(*) as totalCampus 
                            FROM tbuniversidadcampusuniversidadcolectivo uc
                            INNER JOIN tbuniversidadcampus c ON uc.tbuniversidadcampusid = c.tbuniversidadcampusid
                            WHERE uc.tbuniversidadcolectivoid = $colectivoId
                            AND c.tbuniversidadcampusestado = 1;";
        $resultCount = mysqli_query($conn, $queryCountCampus);

        if ($row = mysqli_fetch_assoc($resultCount)) {
            $totalCampus = $row['totalCampus'];

            if ($totalCampus > 0) {
                // Obtener los nombres de los campus activos asociados
                $queryCampusDetails = "SELECT c.tbuniversidadcampusnombre 
                                    FROM tbuniversidadcampus c
                                    INNER JOIN tbuniversidadcampusuniversidadcolectivo uc ON c.tbuniversidadcampusid = uc.tbuniversidadcampusid
                                    WHERE uc.tbuniversidadcolectivoid = $colectivoId
                                    AND c.tbuniversidadcampusestado = 1;";
                $resultCampus = mysqli_query($conn, $queryCampusDetails);
                $campusNames = [];
                while ($campusRow = mysqli_fetch_assoc($resultCampus)) {
                    $campusNames[] = $campusRow['tbuniversidadcampusnombre'];
                }
                $campusList = implode(', ', $campusNames);

                // Devolver el mensaje con la lista de campus asociados
                mysqli_close($conn);
                return [
                    'status' => 'confirm',
                    'message' => "El colectivo tiene $totalCampus campus asociados: $campusList. ¿Está seguro de que desea eliminarlo?",
                    'totalCampus' => $totalCampus
                ];
            }
        }

        // Cierre de conexión
        mysqli_close($conn);
        return ['status' => 'proceed']; // No tiene campus asociados
    }

    public function deleteColectivoById($colectivoId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        // Primero, eliminar en la tabla de campus
        $queryCampusDelete = "UPDATE tbuniversidadcampus 
                              SET tbuniversidadcampusestado = '0'
                              WHERE tbuniversidadcampusid IN (
                                  SELECT tbuniversidadcampusid 
                                  FROM tbuniversidadcampusuniversidadcolectivo 
                                  WHERE tbuniversidadcolectivoid = $colectivoId
                              )";
        $resultCampusDelete = mysqli_query($conn, $queryCampusDelete);

        // Después, eliminar las asociaciones en la tabla de relación
        $queryDeleteAssociations = "DELETE FROM tbuniversidadcampusuniversidadcolectivo 
                                    WHERE tbuniversidadcolectivoid = $colectivoId;";
        $resultDeleteAssociations = mysqli_query($conn, $queryDeleteAssociations);

        // Finalmente, marcar el colectivo como eliminado (cambiar su estado a '0')
        $queryDeleteColectivo = "UPDATE tbuniversidadcampuscolectivo 
                                SET tbuniversidadcampuscolectivoestado = '0' 
                                WHERE tbuniversidadcampuscolectivoid = $colectivoId;";
        $resultDeleteColectivo = mysqli_query($conn, $queryDeleteColectivo);

        mysqli_close($conn);

        if ($resultDeleteColectivo && $resultDeleteAssociations && $resultCampusDelete) {
            return ['status' => 'success', 'message' => 'Colectivo eliminado correctamente.'];
        } else {
            return ['status' => 'error', 'message' => 'Error al eliminar el colectivo.'];
        }
    }

    public function deleteForeverTbUniversidadCampusColectivo($universidadCampusColectivoId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryDelete = "DELETE FROM tbuniversidadcampuscolectivo WHERE tbuniversidadcampuscolectivoid=" . $universidadCampusColectivoId . ";";
        $result = mysqli_query($conn, $queryDelete);
        mysqli_close($conn);

        return $result;
    }

    public function getAllTbUniversidadCampusColectivo()
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $querySelect = "SELECT * FROM tbuniversidadcampuscolectivo;";
        $result = mysqli_query($conn, $querySelect);
        mysqli_close($conn);

        $universidadCampusColectivos = [];
        while ($row = mysqli_fetch_array($result)) {
            $universidadCampusColectivoActual = new universidadCampusColectivo($row['tbuniversidadcampuscolectivoid'], $row['tbuniversidadcampuscolectivonombre'], $row['tbuniversidadcampuscolectivodescripcion'], $row['tbuniversidadcampuscolectivoestado']);
            array_push($universidadCampusColectivos, $universidadCampusColectivoActual);
        }

        return $universidadCampusColectivos;
    }

    public function getAllTbUniversidadCampusColectivoNombres()
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $querySelect = "SELECT tbuniversidadcampuscolectivonombre FROM tbuniversidadcampuscolectivo WHERE tbuniversidadcampuscolectivoestado = 1;";
        $result = mysqli_query($conn, $querySelect);

        if (!$result) {
            // Manejo de errores de consulta
            die('Error en la consulta: ' . mysqli_error($conn));
        }

        $nombres = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $nombres[] = $row['tbuniversidadcampuscolectivonombre'];
        }

        mysqli_close($conn);

        return $nombres;
    }

    public function getAllDeletedTbUniversidadCampusColectivo()
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');
        $query = "SELECT * FROM tbuniversidadcampuscolectivo WHERE tbuniversidadcampuscolectivoestado = 0;";
        $result = mysqli_query($conn, $query);
        $universidadCampusColectivos = [];
        while ($row = mysqli_fetch_array($result)) {
            $universidadCampusColectivoActual = new universidadCampusColectivo($row['tbuniversidadcampuscolectivoid'], $row['tbuniversidadcampuscolectivonombre'], $row['tbuniversidadcampuscolectivodescripcion'], $row['tbuniversidadcampuscolectivoestado']);
            array_push($universidadCampusColectivos, $universidadCampusColectivoActual);
        }
        return $universidadCampusColectivos;
    }

    public function restoreTbCampusColectivo($universidadCampusColectivoId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');
        $universidadCampusColectivoId = mysqli_real_escape_string($conn, $universidadCampusColectivoId);
        $query = "UPDATE tbuniversidadcampuscolectivo 
                              SET tbuniversidadcampuscolectivoestado = '1' 
                              WHERE tbuniversidadcampuscolectivoid = $universidadCampusColectivoId;";
        $result = mysqli_query($conn, $query);
        return $result;
    }

    public function exist($nombre)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $query = "SELECT COUNT(*) as count FROM tbuniversidadcampuscolectivo WHERE tbuniversidadcampuscolectivonombre = ?";

        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 's', $nombre);

        mysqli_stmt_execute($stmt);

        mysqli_stmt_bind_result($stmt, $count);
        mysqli_stmt_fetch($stmt);

        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        return $count > 0;
    }


    public function nameExists($nombre, $excludeId = null)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $query = "SELECT COUNT(*) as count FROM tbuniversidadcampuscolectivo WHERE tbuniversidadcampuscolectivonombre = ? AND tbuniversidadcampuscolectivoid != ?";

        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'si', $nombre, $excludeId);

        mysqli_stmt_execute($stmt);

        mysqli_stmt_bind_result($stmt, $count);
        mysqli_stmt_fetch($stmt);

        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        return $count > 0;
    }

    public function getColectivosByCampusId($campusId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $query = "SELECT tbuniversidadcampuscolectivoid, tbuniversidadcampuscolectivonombre 
                  FROM tbuniversidadcampuscolectivo
                  INNER JOIN tbuniversidadcampusuniversidadcolectivo 
                  ON tbuniversidadcampuscolectivo.tbuniversidadcampuscolectivoid = tbuniversidadcampusuniversidadcolectivo.tbuniversidadcolectivoid
                  WHERE tbuniversidadcampusuniversidadcolectivo.tbuniversidadcampusid = ?";

        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $campusId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $colectivos = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $colectivo = new universidadCampusColectivo(
                $row['tbuniversidadcampuscolectivoid'],
                $row['tbuniversidadcampuscolectivonombre'],
                '',
                1
            );
            $colectivos[] = $colectivo;
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        return $colectivos;
    }


    public function getColectivosByCampusName($campusNombre)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $query = "SELECT DISTINCT tbuniversidadcampuscolectivo.tbuniversidadcampuscolectivoid, 
                tbuniversidadcampuscolectivo.tbuniversidadcampuscolectivonombre 
                FROM tbuniversidadcampuscolectivo 
                INNER JOIN tbuniversidadcampusuniversidadcolectivo 
                ON tbuniversidadcampuscolectivo.tbuniversidadcampuscolectivoid = tbuniversidadcampusuniversidadcolectivo.tbuniversidadcolectivoid
                INNER JOIN tbuniversidadcampus 
                ON tbuniversidadcampus.tbuniversidadcampusid = tbuniversidadcampusuniversidadcolectivo.tbuniversidadcampusid
                WHERE tbuniversidadcampus.tbuniversidadcampusnombre = ?";

        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $campusNombre);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $colectivos = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $colectivo = new universidadCampusColectivo(
                $row['tbuniversidadcampuscolectivoid'],
                $row['tbuniversidadcampuscolectivonombre'],
                '',
                1
            );
            $colectivos[] = $colectivo;
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        return $colectivos;
    }

    public function autocomplete($term)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $sql = "SELECT tbuniversidadcampuscolectivonombre FROM tbuniversidadcampuscolectivo WHERE tbuniversidadcampuscolectivonombre LIKE ?";
        $stmt = $conn->prepare($sql);
        $searchTerm = "%$term%";
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();

        $suggestions = [];
        while ($row = $result->fetch_assoc()) {
            $suggestions[] = $row['tbuniversidadcampuscolectivonombre'];
        }

        $stmt->close();
        $conn->close();

        return $suggestions;
    }
}
