<?php
include '../data/usuarioMensajeData.php';

class UsuarioMensajeBusiness {
    private $usuarioMensajeData;

    public function __construct() {
        $this->usuarioMensajeData = new UsuarioMensajeData();
    }

    public function getMensajes($usuarioId, $amigoId, $lastMessageId) {
        return $this->usuarioMensajeData->getMensajes($usuarioId, $amigoId, $lastMessageId);
    }

    public function enviarMensaje($usuarioId, $amigoId, $mensaje) {
        return $this->usuarioMensajeData->enviarMensaje($usuarioId, $amigoId, $mensaje);
    }

    public function getUsuariosParaChat() {
        return $this->usuarioMensajeData->getUsuariosParaChat();
    }

    public function getUsuarioDetalles($usuarioId){
        return $this->usuarioMensajeData->getUsuarioDetalles($usuarioId);
    } 
    
    public function getUsuarioAmigoDetalles($amigoId){
        return $this->usuarioMensajeData->getUsuarioAmigoDetalles($amigoId);
    }   

    public function getNombreUsuarioPorId($usuarioId){
        return $this->usuarioMensajeData->getNombreUsuarioPorId($usuarioId);
    }
}
