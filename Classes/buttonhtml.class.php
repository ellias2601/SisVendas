<?php
namespace Classes;

require_once 'inputshtml.class.php';
require_once 'interfaceinputshtml.class.php';
use Classes\inputshtml as InputsHtml;
use Classes\interfaceinputshtml as InterfaceInputsHtml;


class ButtonHtml extends InputsHtml implements InterfaceInputsHtml {

    private $type;
    private $name;
    private $value;
    private $texto = null;

    function __construct($type = 'submit', $name = null, $value = null, $texto = null) {
        parent::__construct();
        $this->type = $type;
        $this->name = $name;
        $this->value = $value;
        $this->texto = $texto;
    }

    public function geraHtml() {
        $html = null;
        $html .= "<button type='{$this->type}' name='{$this->name}' value='{$this->value}'>";
        $html .= $this->texto;
        $html .= "</button>";
        return $html;
    }

    function getType() {
        return $this->type;
    }

    function getName() {
        return $this->name;
    }

    function getValue() {
        return $this->value;
    }

    function getTexto() {
        return $this->texto;
    }

    function setType($type) {
        $this->type = $type;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setValue($value) {
        $this->value = $value;
    }

    function setTexto($texto) {
        $this->texto = $texto;
    }

}
