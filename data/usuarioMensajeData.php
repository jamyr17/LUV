<?php
include_once 'data.php';

class UsuarioMensajeData extends Data {

  public function getMensajes($usuarioId, $amigoId, $lastMessageId = 0) {
    $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
    $conn->set_charset('utf8');

    // Selecciona los campos necesarios incluyendo la fecha y el estado de visto
    $query = "SELECT tbusuariomensajeid, tbusuariomensajeentradaid, tbusuariomensajesalidaid, tbusuariomensajedescripcion, tbusuariomensajefecha
              FROM tbusuariomensaje 
              WHERE ((tbusuariomensajeentradaid = $usuarioId AND tbusuariomensajesalidaid = $amigoId) 
                     OR (tbusuariomensajeentradaid = $amigoId AND tbusuariomensajesalidaid = $usuarioId))
                AND tbusuariomensajeid > $lastMessageId
              ORDER BY tbusuariomensajeid";

    $result = mysqli_query($conn, $query);
    $mensajes = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $mensajes[] = $row;
    }
    mysqli_close($conn);
    return $mensajes;
}


    public function enviarMensaje($usuarioId, $amigoId, $mensaje) {
      $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
      $conn->set_charset('utf8');
  
      $query = "INSERT INTO tbusuariomensaje (tbusuariomensajeentradaid, tbusuariomensajesalidaid, tbusuariomensajedescripcion, tbusuariomensajefecha) 
                VALUES ($usuarioId, $amigoId, '$mensaje', NOW())";
  
      $result = mysqli_query($conn, $query);
      mysqli_close($conn);
      return $result;
  }

    public function getUsuariosParaChat() {
      $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
      $conn->set_charset('utf8');
  
      $sessionUserId = $_SESSION['usuarioId'];
      $query = "SELECT tbusuarioid AS id, tbusuarionombre AS nombre 
                FROM tbusuario 
                WHERE tbusuarioestado = 1 AND tbusuarioid != $sessionUserId";
      $result = mysqli_query($conn, $query);
  
      $usuarios = [];
      while ($row = mysqli_fetch_assoc($result)) {
          $usuarios[] = $row;
      }
  
      mysqli_close($conn);
      return $usuarios;
  }
  
  
}
?>
