<?php

require_once 'inputshtml.class.php';
require_once 'interfaceinputshtml.class.php';
use Classes\inputshtml as InputsHtml;
use Classes\interfaceinputshtml as InterfaceInputsHtml;

class TableHtml extends InputsHtml implements InterfaceInputsHtml {

    private $linhas;

    public function __construct($linhas = null) {
        $this->linhas = array();
    }

    public function geraHtml() {
        $html = null;

        $html .= " <style>
                table, th, td {
                    border: 1px solid black;
                }
                </style>";

        $html .= "<table>";

        foreach ($this->linhas as $linha) {
            $html .= $linha->geraHtml();
        }

        $html .= "</table>";

        return $html;
    }
    
     function adicionaLinhas($linhas) {
        $this->linhas [] = $linhas;
    }

}
