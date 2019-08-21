<?php

require ("./vendor/autoload.php");

use PHPUnit\Framework\TestCase;
use Classes\legendhtml as LegendHtml;


class LegendHtmlTest extends TestCase {
    
    public function testDeveGerarHtmlLegenda() {
        
        $legend = new LegendHtml("Teste");
        $legend->setTexto("Teste");
        
        $html = $legend->geraHtml();
        
        $this->assertEquals("<legend>Teste</legend>", $html);
    }
    
}
