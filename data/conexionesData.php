<?php

include_once 'data.php';

class ConexionesData extends Data{

    public function getAllTbPerfilesPorID($usuariosID) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');
        
        // Asegúrate de que $usuariosID sea un array y no esté vacío
        if (!is_array($usuariosID) || empty($usuariosID)) {
            return [];
        }
    
        // Escapar los IDs y unirlos en una cadena
        $usuariosID = array_map('intval', $usuariosID); // Asegúrate de que sean enteros
        $usuariosIDList = implode(',', $usuariosID); // Crea la cadena para la consulta
        
        $querySelect = "
            SELECT 
                p.tbperfilusuariopersonalid AS id,
                p.tbperfilusuariopersonalcriterio AS criterio,
                p.tbperfilusuariopersonalvalor AS valor,
                p.tbusuarioid AS usuarioId,
                p.tbperfilusuariopersonalestado AS estado,
                u.tbusuarionombre AS nombreUsuario,
                u.tbusuarioimagen AS pfp,
                d.tbperfilusuariodeseadoporcentaje AS porcentaje
            FROM 
                tbperfilusuariopersonal p
            JOIN 
                tbusuario u ON p.tbusuarioid = u.tbusuarioid
            JOIN 
                tbpersona per ON u.tbpersonaid = per.tbpersonaid
            JOIN
                tbperfilusuariodeseado d ON u.tbusuarioid = d.tbusuarioid
            WHERE 
                p.tbusuarioid IN ($usuariosIDList) AND p.tbperfilusuariopersonalestado = 1;
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
                'porcentaje' => $row['porcentaje'],
                'pfp' => $row['pfp']
            ];
            array_push($profiles, $profile);
        }
    
        mysqli_close($conn);
        return $profiles;
    }
}    
?>