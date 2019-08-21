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

use Classes\labelhtml as LabelHtml;
use Classes\buttonhtml as ButtonHtml;
use Classes\brhtml as BrHtml;
use Classes\phtml as PHtml;
use Classes\inputhtml as InputHtml;
use Classes\legendhtml as LegendHtml;
use Classes\fieldsethtml as FieldsetHtml;
use Classes\formhtml as FormHtml;

class ProdutoView extends InterfaceHtml {

    private $form = null;
    private $prodId = null;
    private $nomeProduto = null;

    protected function montaForms($produtosModel, $acao) {
        $codigoHtml = null;

        $this->form = new FormHtml();
        $this->form->setAction("cadastraproduto.php");
        $this->form->setMethod("post");

        $codigoHtml .= "<h1>Cadastro de Produtos</h1>";

        $codigoHtml .= $this->montaFormConsulta();

        $legend = new LegendHtml("Produto");
        $fieldset = new FieldsetHtml ();
        $fieldset->setLegend($legend);

        $this->montaInputs($produtosModel);

        $botoes = $this->montaBotoesDeAcordoComAAcao($acao);
        foreach ($botoes as $buttonHtml) {
            $this->form->adicionaObjeto($buttonHtml);
        }

        $fieldset->adicionaObjeto($this->form);
        $codigoHtml .= $fieldset->geraHtml();
        //$codigoHtml .= $this->montaTabela();

        return $codigoHtml;
    }

    public function recebeDadosDoFormulario() {

        if (isset($_POST['prodId'])) {
            $this->prodId = $_POST['prodId'];
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
        $fornCnpj = null;
        if (isset($_POST['fornCnpj'])) {
            $fornCnpj = $_POST['fornCnpj'];
        }

        $scProdutoModelFornecedor = new stdClass();
        $scProdutoModelFornecedor->produtoModel = new ProdutoModel($this->prodId, $prodNome, $prodValor, $prodQtde);
        $scProdutoModelFornecedor->fornCnpj = $fornCnpj;

        return $scProdutoModelFornecedor;
    }

    private function montaFormConsulta() {
        $legend = new LegendHtml("Consulta");
        $fieldset = new FieldsetHtml ();
        $fieldset->setLegend($legend);

        $formHtml = new FormHtml("cadastraproduto.php");
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

        $optionHtml = new OptionHtml(-1, FALSE, "Selecione um produto...");
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

        $inputIdProduto = new InputHtml();
        $inputIdProduto->setType("hidden");
        $inputIdProduto->setName("prodId");
        $inputIdProduto->setValue($produtoModel->getProdId());

        $this->form->adicionaObjeto($inputIdProduto);

        $p = new PHtml();

        $labelNomeProduto = new LabelHtml();
        $labelNomeProduto->setTexto("Informe o Nome do produto ");

        $p->adicionaObjeto($labelNomeProduto);

        $inputNomeProduto = new InputHtml();
        $inputNomeProduto->setType("text");
        $inputNomeProduto->setName("prodNome");
        $inputNomeProduto->setValue($produtoModel->getProdNome());

        $this->nomeProduto = $produtoModel->getProdNome();



        $inputNomeProduto->setDisabled(true);

        $p->adicionaObjeto($inputNomeProduto);
        $this->form->adicionaObjeto($p);
        $inputNomeProduto->setDisabled(false);

        $p = new PHtml();

        $labelValorProduto = new LabelHtml();
        $labelValorProduto->setTexto("Informe o Valor do produto ");

        $p->adicionaObjeto($labelValorProduto);

        $inputValorProduto = new InputHtml();
        $inputValorProduto->setType("text");
        $inputValorProduto->setName("prodValor");
        $inputValorProduto->setValue($produtoModel->getProdValor());
        $inputValorProduto->setDisabled(true);

        $p->adicionaObjeto($inputValorProduto);
        $this->form->adicionaObjeto($p);
        $inputValorProduto->setDisabled(false);

        $p = new PHtml();

        $labelQtdeProduto = new LabelHtml();
        $labelQtdeProduto->setTexto("Informe o Quantidade do produto ");

        $p->adicionaObjeto($labelQtdeProduto);

        $inputQtdeProduto = new InputHtml();
        $inputQtdeProduto->setType("text");
        $inputQtdeProduto->setName("prodQtde");
        $inputQtdeProduto->setValue($produtoModel->getProdQtde());
        $inputQtdeProduto->setDisabled(true);

        $p->adicionaObjeto($inputQtdeProduto);
        $this->form->adicionaObjeto($p);
        $inputQtdeProduto->setDisabled(false);

        //monta o comobox de fornecedores
        $p = new PHtml();
        $labelHtml = new LabelHtml("Fornecedores");
        $p->adicionaObjeto($labelHtml);

        $selectHtml = $this->montaComboDeFornecedores();

        $p->adicionaObjeto($selectHtml);
        $this->form->adicionaObjeto($p);

        $this->form->adicionaObjeto($br);
    }

    function montaTabela() {
        $legend = new LegendHtml("Relação Fornecedor - Produto");
        $fieldset = new FieldsetHtml();
        $fieldset->setLegend($legend);

        $formHtml = new FormHtml("cadastraproduto.php");

        $produtoAdo = new ProdutoAdo();
        try {
            $buscou = $produtosModel = $produtoAdo->checaProdutoRelacionadoFornecedor($this->nomeProduto);
        } catch (Exception $e) {
            parent::adicionaMensagem("Ocorreu um erro ao buscar os produtos da base de dados! Contate o responsável pelo sistema.");
        }
        if ($buscou) {

            /* $table = new TableHtml();
              $tr = new TrHtml();

              $th = new ThHtml();
              $th->setTexto("Produto");
              $tr->adicionaLinhas($th);

              $th = new ThHtml();
              $th->setTexto("Fornecedor");
              $tr->adicionaLinhas($th);

              $table->adicionaLinhas($tr);

              $tr = new TrHtml();

              $td = new TdHtml($buscou['prodNome']);
              $tr->adicionaLinhas($td);
              $tabela->adicionaLinhas($td);
             */

            echo "<table border = 1>";
            echo "<tr><td>Produto</td><td>Fornecedor</td></tr>";
            echo "<tr><td>" . $buscou['prodNome'] . "</td><td>" . $buscou['fornNome'] . "</td></tr>";
            
        } else {
            if ($buscou === 0) {
                //parent::adicionaMensagem("Selecione um produto por favor!!");
                //continua
            } else {
                parent::adicionaMensagem("Ocorreu um erro ao buscar os produtos da base de dados! Contate o responsável pelo sistema.");
            }
        }


        //$formHtml->adicionaObjeto($table);
        $fieldset->adicionaObjeto($formHtml);
        return $fieldset->geraHtml();
        //return $table->geraHtml();   
    }

    public function montaComboDeFornecedores() {
        $selectHtml = new SelectHtml("fornCnpj");

        $fornecedorAdo = new FornecedorAdo();
        try {
            $buscou = $fornecedoresModel = $fornecedorAdo->buscaTodosOsFornecedores();
        } catch (Exception $e) {
            parent::adicionaMensagem("Ocorreu um erro ao buscar os fornecedores da base de dados! Contate o responsável pelo sistema");
        }
        if ($buscou) {
            //continua
        } else {
            if ($buscou === 0) {
                parent::adicionaMensagem("Não existe nenhum fornecedor na base de dados.");
            } else {
                parent::adicionaMensagem("Ocorreu um erro ao buscar os fornecedores da base de dados! Contate o responsável pelo sistema");
            }
            $fornecedoresModel = array();
        }
        $option = new OptionHtml(-1, FALSE, "Selecione um fornecedor...");
        $selectHtml->adicionaOption($option);

        foreach ($fornecedoresModel as $fornecedorModel) {
            $optionHtml = new OptionHtml($fornecedorModel->getFornCnpj(), FALSE, $fornecedorModel->getFornNome());
            $selectHtml->adicionaOption($optionHtml);
        }
        return $selectHtml;
    }

}
