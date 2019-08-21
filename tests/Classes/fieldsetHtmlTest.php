<?php

require ("./vendor/autoload.php");

use PHPUnit\Framework\TestCase;
use Classes\fieldsethtml as FieldsetHtml;
use Classes\legendhtml as LegendHtml;

class FieldsetHtmlTest extends TestCase{
    
    public function testDeveGerarHtmlFieldset(){
        
        $legend = new LegendHtml("Teste");
        
        $fieldset = new FieldsetHtml();
        $fieldset->setLegend($legend);
        $html = $fieldset->geraHtml();
        
        $this->assertEquals("<fieldset><legend>Teste</legend></fieldset>", $html);
    }
}
