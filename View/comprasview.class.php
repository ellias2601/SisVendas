<?php

require_once '../View/interfacehtml.class.php';
require_once '../ADO/produtoado.class.php';
require_once '../Model/produtomodel.class.php';
require_once '../Classes/tablehtml.class.php';
require_once '../Classes/tdhtml.class.php';
require_once '../Classes/thhtml.class.php';
require_once '../Classes/trhtml.class.php';
require_once '../ADO/fornecedorado.class.php';
require_once '../Model/fornecedormodel.class.php';
require_once '../Model/associafornecedorprodutomodel.class.php';
require_once '../Model/precomprasmodel.class.php';
require_once '../Model/cestasdecomprasmodel.class.php';
require_once '../ADO/precomprasado.class.php';

use Classes\labelhtml as LabelHtml;
use Classes\buttonhtml as ButtonHtml;
use Classes\brhtml as BrHtml;
use Classes\phtml as PHtml;
use Classes\inputhtml as InputHtml;
use Classes\legendhtml as LegendHtml;
use Classes\fieldsethtml as FieldsetHtml;
use Classes\formhtml as FormHtml;


class ComprasView extends InterfaceHtml {
    
   
    private $form = null;
    
    protected function montaForms($produtosModel, $acao) {
        
         $codigoHtml = null;
        
        //session_start(); 

        $this->form = new FormHtml("Concluir Compras");
        $this->form->setAction("realizacompras.php");
        $this->form->setMethod("post");

        $codigoHtml .= "<h1>Concluir Compra</h1>";

       
        $legend = new LegendHtml("Confirmação de Dados do Cliente");
        $fieldset = new FieldsetHtml();
        $fieldset->setLegend($legend);
        //$botoes=$this->setBt(false);
        
        $this->montaInputs($produtosModel);
        
        $botoes = $this->montaBotoesDeAcordoComAAcao3($acao);
        foreach ($botoes as $buttonHtml) {
            $this->form->adicionaObjeto($buttonHtml);
        }

        $fieldset->adicionaObjeto($this->form);
        $codigoHtml .= $fieldset->geraHtml();
        
        
        $codigoHtml .= $this->montaFormProdCompra();
        

        return $codigoHtml;
    }

    public function recebeDadosDoFormulario() {
        //session_start();
         $clieCpf = null;
        if (isset($_POST['clieCpf'])) {
            $clieCpf = $_POST['clieCpf'];
        }

        $clieEmail = null;
        if (isset($_POST['clieEmail'])) {
            $clieEmail = $_POST['clieEmail'];
        }
        
    }
    
    private function montaFormProdCompra() {
       // session_start();
        $produtoAdo = new ProdutoAdo();
        $buscou = $produtosModel = $produtoAdo->buscaTodosOsProdutosDaCesta($_SESSION['cestId']);
        if ($buscou) {
            //continua
        } else {
            if ($buscou === 0) {
                //parent::adicionaMensagem("Ainda nao existem produtos na cesta de compras");
                //continua
            } else {
                parent::adicionaMensagem("Ocorreu um erro ao buscar os produtos da cesta de compras! Contate o responsável pelo sistema.");
            }
            $produtosModel = array();
        }

        $tabela = "<table border = 1>";
        $tabela .= "<tr><td>Codigo do Produto</td><td>Nome do Produto</td><td>Preço em R$</td> <td>Quantidade em Estoque</td></tr>";

        foreach ($produtosModel as $produtoModel) {
            
            
            $tabela .= "<tr><td>" . $produtoModel->getProdId() . "</td><td>" . $produtoModel->getProdNome() . "</td><td>" . $produtoModel->getProdValor() . "</td><td>" . $produtoModel->getProdQtde() . "</td></tr>";
        }
        
        

        return $tabela;
    }
    
     private function montaInputs() {
        
        @session_start();
        
        $p = new PHtml();
        $br = new BrHtml();

        $this->form->adicionaObjeto($br);

        $labelCestId = new LabelHtml();
        $labelCestId->setTexto("Este é o ID da sua cesta de compras, caso necessite de suporte: ");

        $this->form->adicionaObjeto($labelCestId);

        $exibeCestCliente = new LabelHtml();
        
        $exibeCestCliente->setTexto($_SESSION['cestId']);

        $this->form->adicionaObjeto($exibeCestCliente);

        $p = new PHtml();

        $labelClieCpf = new LabelHtml();
        $labelClieCpf->setTexto("Confira seu CPF por favor: ");

        $p->adicionaObjeto($labelClieCpf);

        $exibeCpfCliente = new LabelHtml();
        $exibeCpfCliente->setTexto($_SESSION['clieCpf']);

        $p->adicionaObjeto($exibeCpfCliente);
        $p->adicionaObjeto($br);
        $p->adicionaObjeto($br);
        $this->form->adicionaObjeto($p);
  
   
        $labelBandeiraCartao = new LabelHtml();
        $labelBandeiraCartao->setTexto("Bandeira do Cartão: ");
        
        $this->form->adicionaObjeto($labelBandeiraCartao);
        
        
        $selectBandeiraCartao = new SelectHtml();
        
        $mastercard = new OptionHtml();
        $mastercard->setTexto("Mastercard");
        
        $visa = new OptionHtml();
        $visa->setTexto("Visa");
        
        $american = new OptionHtml();
        $american->setTexto("American Express");
        
        $hipercard = new OptionHtml();
        $hipercard->setTexto("Hipercard");
        
        $selectBandeiraCartao->adicionaOption($mastercard);
        $selectBandeiraCartao->adicionaOption($visa);
        $selectBandeiraCartao->adicionaOption($american);
        $selectBandeiraCartao->adicionaOption($hipercard);
        
        $this->form->adicionaObjeto($selectBandeiraCartao);
        
        $this->form->adicionaObjeto($br);
        $this->form->adicionaObjeto($br);
        
        
        
        //label codigo cartao
        $titularLabel = new LabelHtml();
        $titularLabel->setTexto("Nome do Titular: ");
        $this->form->adicionaObjeto($titularLabel);
        
        //input codigo cartao
        
        $titularInput = new InputHtml();
        $titularInput->setType("text");
        $this->form->adicionaObjeto($titularInput);
        
        $this->form->adicionaObjeto($br);
        $this->form->adicionaObjeto($br);
        
 
        //label numero cartao
        $labelNumeroCartao = new LabelHtml();
        $labelNumeroCartao->setTexto("Número do Cartão: ");
        
        $this->form->adicionaObjeto($labelNumeroCartao);
        
        //input numero cartao
        $inputNumeroCartao = new InputHtml();
        $inputNumeroCartao->setType("text");
        $this->form->adicionaObjeto($inputNumeroCartao);
        $this->form->adicionaObjeto($br);
        $this->form->adicionaObjeto($br);
        
        //label validade cartao
        $validadeCartao = new LabelHtml();
        $validadeCartao->setTexto("Validade do Cartão: ");
        $this->form->adicionaObjeto($validadeCartao);
        
        //input validade cartao
        
        $inputValidadeCartao = new InputHtml();
        $inputValidadeCartao->setType("text");
        $this->form->adicionaObjeto($inputValidadeCartao);
        
        
        $this->form->adicionaObjeto($br);
        $this->form->adicionaObjeto($br);
        
        //label codigo cartao
        $codigoCartaoLabel = new LabelHtml();
        $codigoCartaoLabel->setTexto("Código de Segurança do Cartão: ");
        $this->form->adicionaObjeto($codigoCartaoLabel);
        
        //input codigo cartao
        
        $codigoCartaoInput = new InputHtml();
        $codigoCartaoInput->setType("text");
        $this->form->adicionaObjeto($codigoCartaoInput);
        
        $this->form->adicionaObjeto($br);
        $this->form->adicionaObjeto($br);
        

    }

}
