<?php
require_once 'inputshtml.class.php';
require_once 'interfaceinputshtml.class.php';
use Classes\inputshtml as InputsHtml;
use Classes\interfaceinputshtml as InterfaceInputsHtml;

class TrHtml extends InputsHtml implements InterfaceInputsHtml{
    
    private $linhas;
    
    function __construct($linhas = null) {
        parent::__construct();

        $this->linhas = array();
    }
    
    public function geraHtml() {
        $html = null;
        $html .= "<tr>";
        foreach ($this->linhas as $linha) {
            $html .= $linha->geraHtml();
            
        }
        $html .= "</tr>";
        
        return $html;
    }
    
    function adicionaLinhas( $linha){
        $this->linhas [] = $linha;
    }
    
    public function geraTrAbertura(){
        
         $html = null;
        $html .= "<tr>";
        return $html;
    }
    
      public function geraTrFechamento(){
        
         $html = null;
        $html .= "<tr>";
        return $html;
    }
    
    

}