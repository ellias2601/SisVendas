<?php

require_once 'modelabstract.class.php';

class ItensDaCompraModel extends ModelAbstract{
   
    private $itenCompId;
    private $itenProdId;
    
    function __construct($itenCompId = null, $itenProdId = null) {
        $this->itenCompId = $itenCompId;
        $this->itenProdId = $itenProdId;
    }
    
    function getItenCompId() {
        return $this->itenCompId;
    }

    function getItenProdId() {
        return $this->itenProdId;
    }

    function setItenCompId($itenCompId) {
        $this->itenCompId = $itenCompId;
    }

    function setItenProdId($itenProdId) {
        $this->itenProdId = $itenProdId;
    }

    function checaAtributos() {
        
    }
}
