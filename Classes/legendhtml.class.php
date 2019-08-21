<?php
namespace Classes;

require_once 'interfaceinputshtml.class.php';
use Classes\inputshtml as InputsHtml;
use Classes\interfaceinputshtml as InterfaceInputsHtml;


class LegendHtml implements InterfaceInputsHtml {

    private $texto = null;

    public function __construct($texto) {
        $this->texto = $texto;
    }

    public function geraHtml() {
        return "<legend>" . $this->texto . "</legend>";
    }

    function getTexto() {
        return $this->texto;
    }

    function setTexto($texto) {
        $this->texto = $texto;
    }

}
