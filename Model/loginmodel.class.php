<?php

require_once 'modelabstract.class.php';

//use Model\ModelAbstract;

class LoginModel extends ModelAbstract {

    private $loginId;
    private $loginCpf;

    function __construct($loginId = null, $loginCpf = null) {
        $this->loginId = $loginId;
        $this->loginCpf = $loginCpf;
        
    }

    public function checaAtributos() {
        $validaCpf = new ValidaCpf();
        $dadosCorretos = true;

        if ($validaCpf->verificaOCpf($this->loginCpf)) {
            //continua...
        } else {
            $this->adicionaMensagem("CPF inválido!");
            $dadosCorretos = false;
        }

        return $dadosCorretos;
    }

    function getLoginId() {
        return $this->loginId;
    }

    function setLoginId($loginId) {
        $this->loginId = $loginId;
    }

    function getLoginCpf() {
        return $this->loginCpf;
    }

    function setLoginCpf($loginCpf) {
        $this->loginCpf = $loginCpf;
    }

}
