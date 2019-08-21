<?php
namespace Classes;

require_once 'interfaceinputshtml.class.php';
use Classes\inputshtml as InputsHtml;
use Classes\interfaceinputshtml as InterfaceInputsHtml;


class FormHtml implements InterfaceInputsHtml {

    private $action;
    private $method;
    private $objetos;

    public function __construct($action=null, $method = "post") {
        $this->action = $action;
        $this->method = $method;
        
        $this->objetos = array();
    }

    public function adicionaObjeto(InterfaceInputsHtml $objeto) {
        $this->objetos [] = $objeto;
    }

    public function geraHtml() {
        $form = "<form action='{$this->action}' method='{$this->method}'>";

        foreach ($this->objetos as $objeto) {
            $form .= $objeto->geraHtml();
        }

        $form .= "</form>";

        return $form;
    }

    function getAction() {
        return $this->action;
    }

    function getMethod() {
        return $this->method;
    }

    function getObjetos() {
        return $this->objetos;
    }

    function setAction($action) {
        $this->action = $action;
    }

    function setMethod($method) {
        $this->method = $method;
    }

}
