<?php

require_once 'modelabstract.class.php';

class PreComprasModel extends ModelAbstract {
   
    private $precProdId;
    private $precCestId;
    
    function __construct($precProdId=null, $precCestId=null) {
        
        $this->precProdId = $precProdId;
        $this->precCestId = $precCestId;
    }
    
    public function checaAtributos() {
        
        $dadosCorretos=true;
        
         //valida se o id do produto foi repassado para adcionar aos ItensDaCompra
         if (is_null($this->precProdId) || trim($this->precProdId) == "") {
            $this->adicionaMensagem("Por favor selecione o produto desejado!!");
            $dadosCorretos = false;
        } else {
            if (is_numeric($this->precProdId)) {
                //continua
            } else {
                $this->adicionaMensagem("Houve um erro ao adcionar o produto a sua lista de compras!!");
                $dadosCorretos = false;
            }
        }
        
          if (is_null($this->precCestId) || trim($this->precCestId) == "") {
            $this->adicionaMensagem("Houve um erro relacionado a cesta de compras!!");
            $dadosCorretos = false;
        } else {
            if (is_numeric($this->precCestId)) {
                //continua
            } else {
                $this->adicionaMensagem("Houve um erro relacionado a cesta de compras!!");
                $dadosCorretos = false;
            }
        }

        return $dadosCorretos;
    }
    
    function getPrecProdId() {
        return $this->precProdId;
    }

    function getPrecCestId() {
        return $this->precCestId;
    }

    function setPrecProdId($precProdId) {
        $this->precProdId = $precProdId;
    }

    function setPrecCestId($precCestId) {
        $this->precCestId = $precCestId;
    }


}
