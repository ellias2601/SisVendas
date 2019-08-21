<?php

require ("./vendor/autoload.php");

use PHPUnit\Framework\TestCase;
use Classes\brhtml as BrHtml;


class BrHtmlTest extends TestCase{
    
    public function testDeveGerarHtmlBreak(){
        
        $testBreak = new BrHtml();
        $html = $testBreak->geraHtml();
        $this->assertEquals("<br>", $html);
    }
}
