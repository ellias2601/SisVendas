<?php
namespace Classes;

require_once 'inputshtml.class.php';
require_once 'interfaceinputshtml.class.php';
use Classes\inputshtml as InputsHtml;
use Classes\interfaceinputshtml as InterfaceInputsHtml;

class TexteAreaHtml extends InputsHtml implements InterfaceInputsHtml{

    private $name;
    private $value;
    private $texto = null;

    function __construct($name = null, $value = null, $texto = null) {
        parent::__construct();
        $this->name = $name;
        $this->value = $value;
        $this->texto = $texto;
    }

    public function geraHtml() {
        $html = null;
        $html .= "<textarea  name='{$this->name}' value='{$this->value}'>";
        $html .= $this->texto;
        $html .= "</textarea>";
        return $html;
    }

    function getName() {
        return $this->name;
    }

    function getTexto() {
        return $this->texto;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setTexto($texto) {
        $this->texto = $texto;
    }

    function getValue() {
        return $this->value;
    }

    function setValue($value) {
        $this->value = $value;
    }

}
