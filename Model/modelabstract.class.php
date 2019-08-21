<?php

abstract class ModelAbstract {

    //put your code here
    protected $mensagens;

    public function __construct() {
        $this->mensagens = array();
    }

    function getMensagens() {
        return $this->mensagens;
    }

    public function adicionaMensagem($mensagem){
        $this->mensagens [] = $mensagem;
    }
    
    public abstract function checaAtributos();
}
