<?php
require_once 'inputshtml.class.php';
require_once 'interfaceinputshtml.class.php';
use Classes\inputshtml as InputsHtml;
use Classes\interfaceinputshtml as InterfaceInputsHtml;

class ThHtml extends InputsHtml implements InterfaceInputsHtml{
    
    private $texto;
    
    function __construct($texto = null) {
        parent::__construct();
        
        $this->texto = $texto;
    }

    
    function getTexto() {
        return $this->texto;
    }

    function setTexto($texto) {
        $this->texto = $texto;
    }

    public function geraHtml() { 
        $html = "<th>{$this->getTexto()}</th>";
        
        return $html;
    }

}