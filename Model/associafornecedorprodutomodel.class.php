<?php

require_once 'modelabstract.class.php';
require_once '../Classes/validacoes.class.php';
require_once '../Classes/limpanumeros.class.php';
require_once '../Classes/estados.class.php';
use \Classes\validacoes as Validacoes;
use \Classes\limpanumeros as LimpaNumeros;


class AssociaFornecedorProdutoModel extends ModelAbstract {

    private $fProFornCnpj;
    private $fProProdId;

    function __construct($fProFornCnpj = null, $fProProdId=null) {
        $this->fProFornCnpj = $fProFornCnpj;
        $this->fProProdId=$fProProdId;
    }

    public function checaAtributos() {

        $validaCnpj = new Validacoes();
        $dadosCorretos = true;
        $this->fProFornCnpj = LimpaNumeros::retiraNaoNumericos($this->fProFornCnpj);

        if ($validaCnpj->verificaOCnpj($this->fProFornCnpj)) {
            //continua
        } else {
            $this->adicionaMensagem("CNPJ Inválido!!!");
            $dadosCorretos = false;
        }

        if (is_null($this->fProProdId) || trim($this->fProProdId) == "") {
            $this->adicionaMensagem("Obs: Por favor informe o id do produto!!");
            $dadosCorretos = false;
        } else {
            if (is_numeric($this->fProProdId)) {
                //continua
            } else {
                $this->adicionaMensagem("Obs: Por favor informe somente números para o id do produto!!");
                $dadosCorretos = false;
            }
        }

        return $dadosCorretos;
    }

    function getfProFornCnpj() {
        return $this->fProFornCnpj;
    }
    
     function getfProProdId() {
        return $this->fProProdId;
    }
    
    function setfProFornCnpj($fProFornCnpj){
        $this->fProFornCnpj = $fProFornCnpj;
    }

    function fProProdId($fProProdId) {
        $this->fProProdId = $fProProdId;
    }

    

}
