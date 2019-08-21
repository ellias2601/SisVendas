<?php

require_once '../Controller/controllerabstract.class.php';
require_once '../Model/produtomodel.class.php';
require_once '../View/produtoview.class.php';
require_once '../ADO/produtoado.class.php';
require_once '../ADO/associafornecedorprodutoado.class.php';
require_once '../Model/associafornecedorprodutomodel.class.php';

class ProdutoController extends ControllerAbstract {

    private $produtoView = null;
    private $produtoModel = null;
    private $produtoAdo = null;
    private $scProdutoModelFornecedor = null;
    private $associaFornecedorProdutoAdo = null;
    private $AssociaFornecedorProdutoModel = null;

    public function __construct() {

        $this->scProdutoModelFornecedor = new stdClass();
        $this->produtoAdo = new ProdutoAdo();

        $this->associaFornecedorProdutoAdo = new AssociaFornecedorProdutoAdo();
        $this->AssociaFornecedorProdutoModel = new AssociaFornecedorProdutoModel();

        $this->produtoModel = new ProdutoModel();

        $this->produtoView = new ProdutoView("Cadastro de Produtos");

        $this->acao = $this->produtoView->getBt();

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

        $this->produtoView->displayInterface($this->produtoModel, $this->acao);
    }

    protected function consulta() {
        //corrigido de acordo com novo padrao
        $this->scProdutoModelFornecedor = $this->produtoView->recebeDadosDoFormulario();
        $buscou = $produtoModel = $this->produtoAdo->buscaProduto($this->scProdutoModelFornecedor->produtoModel->getProdId());

        if ($buscou) {
            $this->produtoModel = $produtoModel;
        } else {
            $this->produtoView->adicionaMensagem("Não foi possivel buscar os dados! Contate o responsável pelo sistema.");
        }
    }

    protected function altera() {
        $this->scProdutoModelFornecedor = $this->produtoView->recebeDadosDoFormulario();
        $checagemOk = $this->scProdutoModelFornecedor->produtoModel->checaAtributos();
        if ($checagemOk) {
            $inseriu = $this->produtoAdo->alteraObjeto($this->scProdutoModelFornecedor->produtoModel);
            if ($inseriu) {
                $this->produtoView->adicionaMensagem("Tudo Certo! Alterado!");
                $this->acao = "inc";
            } else {
                $this->produtoView->adicionaMensagem("Deu Errado! Não alterado!");
            }
        } else {
            $this->produtoView->adicionaMensagens($this->scProdutoModelFornecedor->produtoModel->getMensagens());
        }
    }

    protected function inclui() {
        $this->scProdutoModelFornecedor = $this->produtoView->recebeDadosDoFormulario();
        $this->produtoModel = $this->scProdutoModelFornecedor->produtoModel;


        $checagemOk = $this->produtoModel->checaAtributos();
        if ($checagemOk) {
            //continua..
        } else {
            $this->produtoView->adicionaMensagens($this->produtoModel->getMensagens());
            return;
        }

        $this->produtoAdo->beginTransaction();

        $inseriu = $this->produtoAdo->insereObjeto($this->produtoModel);
        if ($inseriu) {
            //continua...
        } else {
            $this->produtoView->adicionaMensagem("Deu Errado!");
            $this->produtoAdo->rollBack();
            return;
        }

        //Recebe da StdClass o CNPJ do fornecedor e o id do produto 
        $fproFornCnpj = $this->scProdutoModelFornecedor->fornCnpj;
        $fproProdId = $this->produtoAdo->recuperaId("Produtos");

        //Cria uma instancia de FornecedorProdutoModel (Tabela Associativa), com seus respectivos atributos,
        //vindos da StdClass
        $associaFornecedorProdutoModel = new AssociaFornecedorProdutoModel($fproFornCnpj, $fproProdId);

        $arrayDaQueryEDosValores = $this->associaFornecedorProdutoAdo->montaInsereDoObjeto($associaFornecedorProdutoModel);

        $inseriu = $this->produtoAdo->executaPs($arrayDaQueryEDosValores[0], $arrayDaQueryEDosValores[1]);
        if ($inseriu) {
            $this->produtoView->adicionaMensagem("Tudo Certo!");
        } else {
            $this->produtoView->adicionaMensagem("Deu Errado!");
            $this->produtoAdo->rollBack();
            return;
        }

        $this->produtoAdo->commit();
    }

    protected function recuperaId() {
        $fproProdId = $this->produtoAdo->recuperaId("Produtos");
        return $fproProdId;
    }

    protected function exclui() {
        $this->scProdutoModelFornecedor = $this->produtoView->recebeDadosDoFormulario();

        $excluiu = $this->produtoAdo->excluiObjeto($this->scProdutoModelFornecedor->produtoModel);

        if ($excluiu) {
            $this->produtoView->adicionaMensagem("Tudo Certo! Excluido!");
            $this->acao = "inc";
        } else {
            $this->produtoView->adicionaMensagem("Deu Errado! Não Excluido!");
        }
    }

}
