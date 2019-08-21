<?php
namespace Classes;

require_once 'interfaceinputshtml.class.php';


class PHtml implements InterfaceInputsHtml {

    private $objetos;

    function __construct() {
        $this->objetos = array();
    }

    function adicionaObjeto(InterfaceInputsHtml $objeto) {
        $this->objetos[] = $objeto;
    }

    function getObjetos() {
        return $this->objetos;
    }

    public function geraHtml() {
        $p = "<p>";
        
        foreach ($this->objetos as $objeto) {
            $p .= $objeto->geraHtml();
        }
        
        $p .= "</p>";
        
        return $p;
    }

}
