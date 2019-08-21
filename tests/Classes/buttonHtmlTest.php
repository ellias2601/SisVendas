<?php

require ("./vendor/autoload.php");

use PHPUnit\Framework\TestCase;
use Classes\buttonhtml as ButtonHtml;

class ButtonHtmlTest extends TestCase {
    
    public function testDeveGerarHtmlButton() {
    
        $button = new ButtonHtml();
        $button->setType("input");
        $button->setName("btTeste");
        $button->setValue("OK");
        $button->setTexto("OK");
        
        $html = $button->geraHtml();
        $this->assertEquals("<button type='input' name='btTeste' value='OK'>OK</button>", $html);
}

}
