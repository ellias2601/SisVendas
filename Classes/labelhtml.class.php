<?php
namespace Classes;


require_once 'inputshtml.class.php';
require_once 'interfaceinputshtml.class.php';
use Classes\inputshtml as InputsHtml;
use Classes\interfaceinputshtml as InterfaceInputsHtml;


class LabelHtml extends InputsHtml implements InterfaceInputsHtml {

    private $texto;

    function __construct($texto = null) {
        $this->texto = $texto;
    }

    public function geraHtml() {
        $html = "<label>";
        $html .= $this->texto;
        $html .= "</label>";
        return $html;
    }

    function getTexto() {
        return $this->texto;
    }

    function setTexto($texto) {
        $this->texto = $texto;
    }

    function getDisabled() {
        return NULL;
    }

    function setDisabled($disabled = false) {
        $this->disabled = NULL;
    }

}
