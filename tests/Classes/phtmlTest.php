<?php

require ("./vendor/autoload.php");

use PHPUnit\Framework\TestCase;
use Classes\phtml as PHtml;
use Classes\labelhtml as LabelHtml;

class PHtmlTest extends TestCase {
    
    public function testDeveGerarHtmlParagrafo() {
        
        $labelTest =  new LabelHtml();
        $labelTest->setTexto("Teste");
        
        $paragrafo = new PHtml();
        $paragrafo->adicionaObjeto($labelTest);
        
        $html = $paragrafo->geraHtml();
        $this->assertEquals('<p><label>Teste</label></p>', $html);
    }
}
