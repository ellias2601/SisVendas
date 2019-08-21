<?php
namespace Classes;

require_once 'interfaceinputshtml.class.php';
use Classes\inputshtml as InputsHtml;
use Classes\interfaceinputshtml as InterfaceInputsHtml;

class FieldsetHtml implements InterfaceInputsHtml {

    private $legend = null;
    private $objetos;

    public function __construct() {
        $this->objetos = array();
    }

    public function adicionaObjeto(InterfaceInputsHtml $objeto) {
        $this->objetos [] = $objeto;
    }

    public function geraHtml() {
        $fieldset = "<fieldset>";

        if (is_null($this->legend)) {
            //continua..
        } else {
            $fieldset .= $this->legend->geraHtml();
        }

        foreach ($this->objetos as $objeto) {
            $fieldset .= $objeto->geraHtml();
        }

        $fieldset .= "</fieldset>";

        return $fieldset;
    }

    function getLegend() {
        return $this->legend;
    }

    function getObjetos() {
        return $this->objetos;
    }

    function setLegend(LegendHtml $legend) {
        $this->legend = $legend;
    }

}
