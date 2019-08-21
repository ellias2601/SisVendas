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

class PreComprasView extends InterfaceHtml {

    private $form = null;
    private $cestId = null;

    protected function montaForms($produtosModel, $acao) {
        $codigoHtml = null;

        // session_start(); 

        $this->form = new FormHtml("Pré Compras");
        $this->form->setAction("cadastraprecompras.php");
        $this->form->setMethod("post");

        $codigoHtml .= "<h1>Pré Compras</h1>";

        $codigoHtml .= $this->montaFormConsulta();

        $legend = new LegendHtml("Dados do Produto Selecionado");
        $fieldset = new FieldsetHtml();
        $fieldset->setLegend($legend);

        $this->montaInputs($produtosModel);

        //$botoes=$this->setBt(false);

        $botoes = $this->montaBotoesDeAcordoComAAcao2($acao);
        foreach ($botoes as $buttonHtml) {
            $this->form->adicionaObjeto($buttonHtml);
        }

        $fieldset->adicionaObjeto($this->form);
        $codigoHtml .= $fieldset->geraHtml();

        $codigoHtml .= $this->montaFormProdPreCompra();


        return $codigoHtml;
    }

    public function recebeDadosDoFormulario() {

        $prodId = null;
        if (isset($_POST['prodId'])) {
            $prodId = $_POST['prodId'];
        }

        $prodNome = null;
        if (isset($_POST['prodNome'])) {
            $prodNome = $_POST['prodNome'];
        }

        $prodValor = null;
        if (isset($_POST['prodValor'])) {
            $prodValor = $_POST['prodValor'];
        }

        $prodQtde = null;
        if (isset($_POST['prodQtde'])) {
            $prodQtde = $_POST['prodQtde'];
        }

        $cestId = null;
        if (isset($_POST['cestId'])) {
            $cestId = $_POST['cestId'];
        }


        $scProdutoModelFornecedor = new stdClass();
        $scProdutoModelFornecedor->produtoModel = new ProdutoModel($prodId, $prodNome, $prodValor, $prodQtde);
        $scProdutoModelFornecedor->prodId = $prodId;
        $scProdutoModelFornecedor->cestId = $cestId;


        return $scProdutoModelFornecedor;
    }

    public function recebeCestaComProduto() {
        $scCestaEProduto = new stdClass();

        $scCestaEProduto->cestId = null;
        if (isset($_POST['cestId'])) {
            $scCestaEProduto->cestId = $_POST['cestId'];
        }

        $scCestaEProduto->prodId = null;
        if (isset($_POST['prodId'])) {
            $scCestaEProduto->prodId = $_POST['prodId'];
        }
        return $scCestaEProduto;
    }

    private function montaFormConsulta() {
        $legend = new LegendHtml("Consulta");
        $fieldset = new FieldsetHtml ();
        $fieldset->setLegend($legend);

        $formHtml = new FormHtml("cadastraprecompras.php");
        $labelHtml = new LabelHtml("Produtos");
        $selectHtml = new SelectHtml("prodId");

        $produtoAdo = new ProdutoAdo();
        try {
            $buscou = $produtosModel = $produtoAdo->buscaTodosOsProdutos();
        } catch (Exception $e) {
            parent::adicionaMensagem("Ocorreu um erro ao buscar os produtos da base de dados! Contate o responsável pelo sistema.");
        }
        if ($buscou) {
            //continua
        } else {
            if ($buscou === 0) {
                parent::adicionaMensagem("Não existe nenhum produto no banco de dados.");
            } else {
                parent::adicionaMensagem("Ocorreu um erro ao buscar os produtos da base de dados! Contate o responsável pelo sistema.");
            }
            $produtosModel = array();
        }

        $optionHtml = new OptionHtml(-1, FALSE, "Selecione um produto que deseja adquirir...");
        $selectHtml->adicionaOption($optionHtml);

        foreach ($produtosModel as $produtoModel) {
            $optionHtml = new OptionHtml($produtoModel->getProdId(), FALSE, $produtoModel->getProdNome());
            $selectHtml->adicionaOption($optionHtml);
        }

        $buttonHtml = new ButtonHtml("input", "bt", "con", "CONSULTAR");

        $formHtml->adicionaObjeto($labelHtml);
        $formHtml->adicionaObjeto($selectHtml);
        $formHtml->adicionaObjeto(new BrHtml());
        $formHtml->adicionaObjeto($buttonHtml);

        $fieldset->adicionaObjeto($formHtml);

        return $fieldset->geraHtml();
    }

    private function montaInputs($produtoModel) {
        $p = new PHtml();
        $br = new BrHtml();

        $this->form->adicionaObjeto($br);

        $labelIdProduto = new LabelHtml();
        $labelIdProduto->setTexto("Código do produto: ");

        $this->form->adicionaObjeto($labelIdProduto);

        $exibeIdProduto = new LabelHtml();
        $exibeIdProduto->setTexto($produtoModel->getProdId());

        $this->form->adicionaObjeto($exibeIdProduto);

        $p = new PHtml();

        $labelNomeProduto = new LabelHtml();
        $labelNomeProduto->setTexto("Nome do produto: ");

        $p->adicionaObjeto($labelNomeProduto);

        $exibeNomeProduto = new LabelHtml();
        $exibeNomeProduto->setTexto($produtoModel->getProdNome());

        $p->adicionaObjeto($exibeNomeProduto);
        $this->form->adicionaObjeto($p);


        $p = new PHtml();

        $labelValorProduto = new LabelHtml();
        $labelValorProduto->setTexto("Preço em R$: ");

        $p->adicionaObjeto($labelValorProduto);

        $exibeValorProduto = new LabelHtml();
        $exibeValorProduto->setTexto($produtoModel->getProdValor());

        $p->adicionaObjeto($exibeValorProduto);
        $this->form->adicionaObjeto($p);


        $p = new PHtml();

        $labelQtdeProduto = new LabelHtml();
        $labelQtdeProduto->setTexto("Quantidade em estoque: ");

        $p->adicionaObjeto($labelQtdeProduto);

        $exibeQtdeProduto = new LabelHtml();
        $exibeQtdeProduto->setTexto($produtoModel->getProdQtde());

        $p->adicionaObjeto($exibeQtdeProduto);
        $this->form->adicionaObjeto($p);

        $p = new PHtml();

        $inputProdId = new InputHtml();
        $inputProdId->setType("hidden");
        $inputProdId->setName("prodId");
        $inputProdId->setValue($produtoModel->getProdId());
        $inputProdId->setDisabled(false);
        $this->form->adicionaObjeto($inputProdId);

        $inputCestId = new InputHtml();
        $inputCestId->setType("hidden");
        $inputCestId->setName("cestId");
        $inputCestId->setValue($this->getCestId());


        $_SESSION['cestId'] = $this->getCestId();
        $_SESSION['prodId'] = $produtoModel->getProdId();


        $inputCestId->setDisabled(false);
        $this->form->adicionaObjeto($inputCestId);

        $this->form->adicionaObjeto($br);
    }

    private function montaFormProdPreCompra() {

        @session_start();

        if (isset($_SESSION['cestId'])) {

            //continua
        } 
        else {
            $_SESSION['cestId'] = null;
        }

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
        $tabela .= "<tr><td>Codigo do Produto</td><td>Nome do Produto</td><td>Preço em R$</td> <td>Quantidade em Estoque</td><td>Ação</td></tr>";

        foreach ($produtosModel as $produtoModel) {
            //montar código do formulário
            $form = new FormHtml();

            $button = new ButtonHtml();
            $button->setType("submit");
            $button->setName("bt");
            $button->setValue("exc");
            $button->setTexto("Remover da Cesta");
            $form->adicionaObjeto($button);

            $inputProdId = new InputHtml();
            $inputProdId->setType("hidden");
            $inputProdId->setName("prodId");
            $inputProdId->setValue($produtoModel->getProdId());
            $inputProdId->setDisabled(false);
            $form->adicionaObjeto($inputProdId);

            $inputCestId = new InputHtml();
            $inputCestId->setType("hidden");
            $inputCestId->setName("cestId");
            $inputCestId->setValue($this->getCestId());
            $inputCestId->setDisabled(false);
            $form->adicionaObjeto($inputCestId);

            $tabela .= "<tr><td>" . $produtoModel->getProdId() . "</td><td>" . $produtoModel->getProdNome() . "</td><td>" . $produtoModel->getProdValor() . "</td><td>" . $produtoModel->getProdQtde() . "</td><td>{$form->geraHtml()}</td></tr>";
        }

        return $tabela;
    }

    function getCestId() {
        return $this->cestId;
    }

    function setCestId($cestId) {
        $this->cestId = $cestId;
    }

}
