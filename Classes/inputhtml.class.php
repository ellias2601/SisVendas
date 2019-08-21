<?php

namespace Classes;

require_once 'inputshtml.class.php';
require_once 'interfaceinputshtml.class.php';
use Classes\inputshtml as InputsHtml;
use Classes\interfaceinputshtml as InterfaceInputsHtml;


class InputHtml extends InputsHtml implements InterfaceInputsHtml {

    private $type;
    private $name;
    private $value;
    private $texto = null;
    private $checked = null;

    function __construct($type = "text", $name = null, $value = null) {
        parent::__construct();
        $this->type = $type;
        $this->name = $name;
        $this->value = $value;

        $this->setChecked();
    }

    public function geraHtml() {
        $html = null;
        $html .= "<input type='{$this->type}' name='{$this->name}' value='{$this->value}'{$this->disabled}{$this->checked}>";
        $html .= $this->texto;
        $html .= "</input>";
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

    function setType($type) {
        $this->type = $type;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setValue($value) {
        $this->value = $value;
    }

    function getTexto() {
        return $this->texto;
    }

    function setTexto($texto) {
        $this->texto = $texto;
    }

    function getChecked() {
        return $this->checked;
    }

    function setChecked($checked = false) {
        if ($checked) {
            $this->checked = " checked ='checked'";
        } else {
            $this->checked = null;
        }
    }

}
