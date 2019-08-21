<?php

require ("./vendor/autoload.php");

use PHPUnit\Framework\TestCase;
use Classes\labelhtml as LabelHtml;

class LabelHtmlTest extends TestCase {
    
     public function testDeveGerarHtmlLabel() {
     
     $labelTest =  new LabelHtml();
     $labelTest->setTexto("Teste");
     
     $html = $labelTest->geraHtml();
     
     $this->assertEquals('<label>Teste</label>',$html);

}
     
}
