<?php

include_once 'data.php';
include_once '../domain/actividadDomain.php';

class ActividadData extends Data
{
    
    public function insertTbActividad($actividad)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryGetLastId = "SELECT MAX(tbactividadid) AS max_id FROM tbactividad";
        $result = mysqli_query($conn, $queryGetLastId);

        if ($row = mysqli_fetch_assoc($result)) {
            $maxId = $row['max_id'];
            $nextId = ($maxId !== null) ? (int)$maxId + 1 : 1;
        } else {
            $nextId = 1;
        }
    
        $queryInsert = "INSERT INTO tbactividad (
            tbactividadid, tbusuarioid, tbactividadtitulo, tbactividaddescripcion, tbactividadfechainicio,
            tbactividadfechatermina, tbactividaddireccion, tbactividadlatitud,
            tbactividadlongitud, tbactividadestado, tbactividadanonimo
        ) VALUES (
            $nextId, 
            '{$actividad->getTbUsuarioId()}',
            '{$actividad->getTbActividadTitulo()}', 
            '{$actividad->getTbActividadDescripcion()}', 
            '{$actividad->getTbActividadFechaInicio()}', 
            '{$actividad->getTbActividadFechaTermina()}', 
            '{$actividad->getTbActividadDireccion()}', 
            '{$actividad->getTbActividadLatitud()}', 
            '{$actividad->getTbActividadLongitud()}', 
            '{$actividad->getTbActividadEstado()}', 
            '{$actividad->getTbActividadAnonimo()}'
        )";

        //colectivos (N:N)
        $queryGetLastId = "SELECT MAX(tbactividaduniversidadcampuscolectivoid) AS max_id FROM tbactividaduniversidadcampuscolectivo";
        $result = mysqli_query($conn, $queryGetLastId);

        if ($row = mysqli_fetch_assoc($result)) {
            $maxId = $row['max_id'];
            $nextIdAux = ($maxId !== null) ? (int) $maxId + 1 : 1;
        } else {
            $nextIdAux = 1;
        }

        $colectivos = $actividad->getTbActividadColectivos();

        foreach ($colectivos as $colectivoId) {
            $colectivoIdEscaped = mysqli_real_escape_string($conn, $colectivoId);
            $query = "INSERT INTO tbactividaduniversidadcampuscolectivo (tbactividaduniversidadcampuscolectivoid, tbactividadid, tbcampuscolectivoid) 
            VALUES ($nextIdAux, $nextId, '$colectivoIdEscaped')";
            $resultInsert = mysqli_query($conn, $query);
            $nextIdAux++;
        }
    
        $resultInsert = mysqli_query($conn, $queryInsert);
        mysqli_close($conn);

        return $resultInsert;
    }

    public function getTbActividad(){
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $query  = "SELECT * FROM tbactividad WHERE tbactividadestado = 1;";

        $result = mysqli_query($conn, $query);
        mysqli_close($conn);

        $actividades = [];
        while ($row = mysqli_fetch_array($result)) {
            $actividadNueva = new Actividad($row['tbactividadid'], $row['tbusuarioid'], $row['tbactividadtitulo'], 
            $row['tbactividaddescripcion'], $row['tbactividadfechainicio'], $row['tbactividadfechatermina'], 
            $row['tbactividaddireccion'], $row['tbactividadlatitud'], $row['tbactividadlongitud'],
            $row['tbactividadestado'], $row['tbactividadanonimo'], 1
        );
            array_push($actividades, $actividadNueva);
        }

        return $actividades;
    }

    public function updateTbActividad($actividad)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $id = intval($actividad->getTbActividadId()); 

        $queryUpdate = "UPDATE tbactividad SET tbactividadtitulo='{$actividad->getTbActividadTitulo()}', tbactividaddescripcion='{$actividad->getTbActividadDescripcion()}', 
        tbactividadfechainicio='{$actividad->getTbActividadFechaInicio()}', tbactividadfechatermina='{$actividad->getTbActividadFechaTermina()}', 
        tbactividaddireccion='{$actividad->getTbActividadDireccion()}', tbactividadanonimo='{$actividad->getTbActividadAnonimo()}'
        WHERE tbactividadid=$id;";

        // Eliminar los colectivos actuales asociados con la actividad
        $queryDeleteColectivos = "DELETE FROM tbactividaduniversidadcampuscolectivo WHERE tbactividadid=$id;";
        $resultDelete = mysqli_query($conn, $queryDeleteColectivos);

        if (!$resultDelete) {
            echo "Error al eliminar colectivos: " . mysqli_error($conn);
            mysqli_close($conn);
            return $resultDelete;
        }

        // Insertar los nuevos colectivos
        $colectivos = $actividad->getTbActividadColectivos();        

        // Obtener el ID máximo actual para los colectivos
        $queryGetLastIdAux = "SELECT MAX(tbactividaduniversidadcampuscolectivoid) AS max_id FROM tbactividaduniversidadcampuscolectivo";
        $resultAux = mysqli_query($conn, $queryGetLastIdAux);

        if ($row = mysqli_fetch_assoc($resultAux)) {
            $maxId = $row['max_id'];
            $nextIdAux = ($maxId !== null) ? (int)$maxId + 1 : 1;
        } else {
            $nextIdAux = 1;
        }

        foreach ($colectivos as $colectivoId) {
            $colectivoIdEscaped = mysqli_real_escape_string($conn, $colectivoId);
            $queryInsertColectivo = "INSERT INTO tbactividaduniversidadcampuscolectivo (tbactividaduniversidadcampuscolectivoid, tbactividadid, tbcampuscolectivoid) VALUES ($nextIdAux, $id, '$colectivoIdEscaped')";
            $resultInsert = mysqli_query($conn, $queryInsertColectivo);
            if (!$resultInsert) {
                echo "Error al insertar colectivo: " . mysqli_error($conn);
                mysqli_close($conn);
                return $resultInsert;
            }
            $nextIdAux++;
        }

        $result = mysqli_query($conn, $queryUpdate);
        mysqli_close($conn);

        return $result;
    }

    public function deleteTbActividad($actividadId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryDelete = "UPDATE tbactividad SET tbactividadestado = '0' WHERE tbactividadid=$actividadId;";
        $result = mysqli_query($conn, $queryDelete);
        mysqli_close($conn);

        return $result;
    }

    public function deleteForeverTbActividad($actividadId)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryDelete = "DELETE FROM tbactividad WHERE tbactividadid=$actividadId;";
        $result = mysqli_query($conn, $queryDelete);
        mysqli_close($conn);

        return $result;
    }

    public function exist($titulo)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $query = "SELECT COUNT(*) as count FROM tbactividad WHERE tbactividadtitulo = ?";
        
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 's', $titulo);
        
        mysqli_stmt_execute($stmt);
        
        mysqli_stmt_bind_result($stmt, $count);
        mysqli_stmt_fetch($stmt);
        
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        
        return $count > 0;
    }

    public function nameExists($titulo, $idActividad, $excludeId = null)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $query = "SELECT COUNT(*) as count FROM tbactividad WHERE tbactividadtitulo = ? AND tbactividadid != ?";
        
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'si', $titulo, $idActividad);
        
        mysqli_stmt_execute($stmt);
        
        mysqli_stmt_bind_result($stmt, $count);
        mysqli_stmt_fetch($stmt);
        
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        
        return $count > 0;
    }

    public function getAllTbActividadTitulos()
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $querySelect = "SELECT tbactividadtitulo FROM tbactividad WHERE tbactividadestado = 1;";
        $result = mysqli_query($conn, $querySelect);

        if (!$result) {
            // Manejo de errores de consulta
            die('Error en la consulta: ' . mysqli_error($conn));
        }

        $titulos = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $titulos[] = $row['tbactividadtitulo'];
        }

        mysqli_close($conn);

        return $titulos;
    }

    public function autocomplete($term) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');
    
        $sql = "SELECT tbactividadtitulo FROM tbactividad WHERE tbactividadtitulo LIKE ?";
        $stmt = $conn->prepare($sql);
        $searchTerm = "%$term%";
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $suggestions = [];
        while ($row = $result->fetch_assoc()) {
            $suggestions[] = $row['tbactividadtitulo'];
        }
    
        $stmt->close();
        $conn->close();
    
        return $suggestions;
    }

    public function getAllDeletedTbActividad() {
        // Conexión a la base de datos
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');
    
        // Consulta para obtener las actividades eliminadas (tbactividadestado = 0)
        $query = "SELECT * FROM tbactividad WHERE tbactividadestado = 0;";
        $result = mysqli_query($conn, $query);
    
        $actividadesEliminadas = [];
        
       // Recorrer cada fila obtenida y crear una instancia de la clase Actividad
while ($row = mysqli_fetch_array($result)) {
    // Valor por defecto para colectivos
    $colectivos = [];

    // Crear la instancia de la actividad con los 11 parámetros
    $actividadActual = new Actividad(
        $row['tbactividadid'],
        $row['tbusuarioid'],
        $row['tbactividadtitulo'],
        $row['tbactividaddescripcion'],
        $row['tbactividadfechainicio'],
        $row['tbactividadfechatermina'],
        $row['tbactividaddireccion'],
        $row['tbactividadlatitud'],
        $row['tbactividadlongitud'],
        $row['tbactividadestado'],
        $row['tbactividadanonimo'],
        $colectivos // Valor predeterminado para colectivos
    );
    
            // Agregar la actividad a la lista de actividades eliminadas
            array_push($actividadesEliminadas, $actividadActual);
        }
    
        // Cerrar la conexión a la base de datos
        mysqli_close($conn);
    
        // Retornar la lista de actividades eliminadas
        return $actividadesEliminadas;
    }

    public function restoreTbActividad($actividadId) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');
        $actividadId = mysqli_real_escape_string($conn, $actividadId);
        $query = "UPDATE tbactividad SET tbactividadestado = 1 WHERE tbactividadid = '$actividadId';";
        $result = mysqli_query($conn, $query);
        return $result;
    }

}