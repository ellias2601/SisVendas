<?php

require_once '../Controller/controllerabstract.class.php';
require_once '../Model/associafornecedorprodutomodel.class.php';
require_once '../View/associafornecedorprodutoview.class.php';
require_once '../ADO/associafornecedorprodutoado.class.php';

class AssociaFornecedorProdutoController extends ControllerAbstract {

    private $associaFornecedorProdutoView = null;
    private $associaFornecedorProdutoModel = null;
    private $associaFornecedorProdutoAdo = null;

    public function __construct() {
        $this->associaFornecedorProdutoAdo = new AssociaFornecedorProdutoAdo();

        $this->associaFornecedorProdutoModel = new AssociaFornecedorProdutoModel();

        $this->associaFornecedorProdutoView = new AssociaFornecedorProdutoView("Associa Fornecedor e Produto");

        $this->acao = $this->associaFornecedorProdutoView->getBt();

        switch ($this->acao) {
            case "inc":
                $this->inclui();
                break;

            case "con":
                $this->consulta();
                break;

            case "alt":
                $this->altera();
                break;

            case "exc":
                $this->exclui();
                break;

            default:
                break;
        }

        $this->associaFornecedorProdutoView->displayInterface($this->associaFornecedorProdutoModel, $this->acao);
    }

    protected function consulta() {
        $this->produtoModel = $this->produtoView->recebeDadosDoFormulario();
        $buscou = $produtoModel = $this->produtoAdo->buscaProduto($this->produtoModel->getProdId());

        if ($buscou) {
            $this->produtoModel = $produtoModel;
        } else {
            $this->produtoView->adicionaMensagem("Não foi possivel buscar os dados! Contate o responsável pelo sistema.");
        }
    }

    protected function inclui() {
        $this->associaFornecedorProdutoModel = $this->associaFornecedorProdutoView->recebeDadosDoFormulario();

        $checagemOk = $this->associaFornecedorProdutoModel->checaAtributos();
        if ($checagemOk) {
            $inseriu = $this->associaFornecedorProdutoAdo->insereObjeto($this->associaFornecedorProdutoModel);
            if ($inseriu) {
                $this->associaFornecedorProdutoView->adicionaMensagem("Tudo Certo!");
            } else {
                $this->associaFornecedorProdutoView->adicionaMensagem("Deu Errado!");
            }
        } else {
            $this->associaFornecedorProdutoView->adicionaMensagens($this->associaFornecedorProdutoModel->getMensagens());
        }
    }

    protected function altera() {
        $this->produtoModel = $this->produtoView->recebeDadosDoFormulario();

        $checagemOk = $this->produtoModel->checaAtributos();
        if ($checagemOk) {
            $inseriu = $this->produtoAdo->alteraObjeto($this->produtoModel);
            if ($inseriu) {
                $this->produtoView->adicionaMensagem("Tudo Certo! Alterado!");
                $this->acao = "inc";
            } else {
                $this->produtoView->adicionaMensagem("Deu Errado! Não alterado!");
            }
        } else {
            $this->produtoView->adicionaMensagens($this->produtoModel->getMensagens());
        }
    }

    protected function exclui() {
        $this->associaFornecedorProdutoModel = $this->associaFornecedorProdutoView->recebeDadosDoFormulario();

        $inseriu = $this->associaFornecedorProdutoAdo->excluiObjeto($this->associaFornecedorProdutoModel);

        if ($inseriu) {
            $this->associaFornecedorProdutoView->adicionaMensagem("Tudo Certo! Excluido!");
            $this->acao = "inc";
        } else {
            $this->associaFornecedorProdutoView>adicionaMensagem("Deu Errado! Não Excluido!");
        }
    }

}

