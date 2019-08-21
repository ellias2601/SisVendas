<?php


require_once 'inputshtml.class.php';
require_once 'interfaceinputshtml.class.php';
use Classes\inputshtml as InputsHtml;
use Classes\interfaceinputshtml as InterfaceInputsHtml;


class OptionHtml extends InputsHtml implements InterfaceInputsHtml{

    private $value;
    private $selected;
    private $texto;

    function __construct($value = null, $selected = false, $texto = null) {
        parent::__construct();

        $this->value = $value;
        $this->setSelected($selected);
        $this->texto = $texto;
    }

    public function geraHtml() {
        $html = "<option value='{$this->value}'{$this->selected}>";
        $html .= $this->texto;
        $html .= "</option>";

        return $html;
    }

    function getValue() {
        return $this->value;
    }

    function getSelected() {
        return $this->selected;
    }

    function getTexto() {
        return $this->texto;
    }

    function setValue($value) {
        $this->value = $value;
    }

    function setSelected($selected = false) {
        if ($selected) {
            $this->selected = " selected='selected'";
        } else {
            $this->selected = null;
        }
    }

    function setTexto($texto) {
        $this->texto = $texto;
    }

}
