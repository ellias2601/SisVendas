<?php
require ("./vendor/autoload.php");

use PHPUnit\Framework\TestCase;
use Classes\validacoes as Validacoes;

class ValidacoesTest extends TestCase{
    
    /**
     * @test
     **/
    
    public function testEntenderCnpjValido() {
        
       $validacoes = new Validacoes();
        $valor = $validacoes->verificaOCnpj("67.124.399/0001-93");
        $this->assertEquals(true, $valor);
        
       // $ob = new Validacoes();
       //$this->assertTrue($ob->verificaOCnpj("67.124.399/0001-93"));
    }
    
    public function testDeveEntenderCnpjInvalido(){
        $validacoes = new Validacoes();
        $valor = $validacoes->verificaOCnpj("67.112.399/0001-93");
        $this->assertEquals(false, $valor);
    }
    
    public function testDeveEntenderCnpjSemPontuacaoBarraseIfens(){
        
        //informamos um cnpj valido sem pontuacao, deve retornar true
        //$cnpjValido = new Validacoes();
        //$valor = $cnpjValido->verificaOCnpj("67112399000193");
        //$this->assertEquals(true, $valor);
        
         $ob = new Validacoes();
         $this->assertTrue($ob->verificaOCnpj("67124399000193"));
        
        
    }
}
