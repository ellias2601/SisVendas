<?php
namespace Classes;

require_once 'interfaceinputshtml.class.php';
use Classes\inputshtml as InputsHtml;
use Classes\interfaceinputshtml as InterfaceInputsHtml;


class BrHtml implements InterfaceInputsHtml {
    

    public function geraHtml() {
        return "<br>";
    }

}
