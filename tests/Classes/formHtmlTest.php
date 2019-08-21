<?php
require ("./vendor/autoload.php");

use PHPUnit\Framework\TestCase;
use Classes\formhtml as FormHtml;
use Classes\labelhtml as LabelHtml;
use Classes\inputhtml as InputHtml;

class FormHtmlTest extends TestCase {
    
    public function testDeveGerarHtmlDoFormulario(){
        
        $form = new FormHtml();
        $form->setAction("cadastraproduto.php");
        $form->setMethod("post");
        
        $label = new LabelHtml();
        $label->setTexto("Produto");
        
        $form->adicionaObjeto($label);
        
        $input = new InputHtml();
        $input->setType("text");
        $input->setName("prodNome");
        $input->setValue("proNome");
        
        $form->adicionaObjeto($input);
        
        $html = $form->geraHtml();
        
        $this->assertEquals("<form action='cadastraproduto.php' method='post'><label>Produto</label>"
        . "<input type='text' name='prodNome' value='proNome'></input></form>", $html);
    }
   
}
