<?php

require_once 'inputshtml.class.php';
require_once 'interfaceinputshtml.class.php';
use Classes\inputshtml as InputsHtml;
use Classes\interfaceinputshtml as InterfaceInputsHtml;

class SelectHtml extends InputsHtml implements InterfaceInputsHtml {

    private $name;
    private $options;

    function __construct($name = null) {
        parent::__construct();

        $this->name = $name;
        $this->options = array();
    }

    public function geraHtml() {
        $select = "<select name='{$this->name}'>";
        foreach ($this->options as $option) {
            $select .= $option->geraHtml();
        }
        $select .= "</select>";
        return $select;
    }

    function getName() {
        return $this->name;
    }

    function getOptions() {
        return $this->options;
    }

    function setName($name) {
        $this->name = $name;
    }

//    function adicionaOptions($options) {
//        $this->options [] = $options;
//    }
    function adicionaOption(OptionHtml $option) {
        $this->options [] = $option;
    }

}
