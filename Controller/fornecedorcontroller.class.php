<?php


require_once '../Controller/controllerabstract.class.php';
require_once '../Model/fornecedormodel.class.php';
require_once '../View/fornecedorview.class.php';
require_once '../ADO/fornecedorado.class.php';

class FornecedorController extends ControllerAbstract {

    private $fornecedorView = null;
    private $fornecedorModel = null;
    private $fornecedorAdo = null;

    public function __construct() {
        $this->fornecedorAdo = new FornecedorAdo();

        $this->fornecedorModel = new FornecedorModel();

        $this->fornecedorView = new FornecedorView("Cadastro de Fornecedores");

        $this->acao = $this->fornecedorView->getBt();

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

        $this->fornecedorView->displayInterface($this->fornecedorModel, $this->acao);
    }

    protected function consulta() {
        $this->fornecedorModel = $this->fornecedorView->recebeDadosDoFormulario();

        //Verifica se existe produto cadastrado do fornecedor no BD por meio do CNPJ
        $existeProdutoRelacionadoFornecedor = $fornecedorModel = $this->fornecedorAdo->checaFornecedorLigadoAProduto($this->fornecedorModel->getFornCnpj());
        
        $buscou = $fornecedorModel = $this->fornecedorAdo->buscaFornecedor($this->fornecedorModel->getFornCnpj());

        if ($buscou) {
            if ($existeProdutoRelacionadoFornecedor == FALSE) {
                $this->fornecedorModel = $fornecedorModel;
                $this->acao = "exc";
                //continua, fornecedor sem produtos relacionados, pode ser excluido (deve ser montado botão de excluir e alterar);
            } else {
                $this->fornecedorModel = $fornecedorModel;
                $this->acao = "altNoExc";
                $this->fornecedorView->adicionaMensagem("Existem produtos cadastrados deste fornecedor, portanto não poderá ser excluído!!");
                //fornecedor nao pode ser excluido, (deve ser montado somente botão de alteração);
            }
        } else {
            $this->fornecedorView->adicionaMensagem("Não foi possivel buscar os dados! Contate o responsável pelo sistema.");
        }

        //echo $existeProdutoRelacionadoFornecedor;
    }

    protected function inclui() {
        $this->fornecedorModel = $this->fornecedorView->recebeDadosDoFormulario();

        $checagemOk = $this->fornecedorModel->checaAtributos();
        if ($checagemOk) {
            $inseriu = $this->fornecedorAdo->insereObjeto($this->fornecedorModel);
            if ($inseriu) {
                $this->fornecedorView->adicionaMensagem("Tudo Certo!");
            } else {
                $this->fornecedorView->adicionaMensagem("Deu Errado!");
            }
        } else {
            $this->fornecedorView->adicionaMensagens($this->fornecedorModel->getMensagens());
        }
    }

    protected function altera() {
        $this->fornecedorModel = $this->fornecedorView->recebeDadosDoFormulario();

        $checagemOk = $this->fornecedorModel->checaAtributos();
        if ($checagemOk) {
            $alterou = $this->fornecedorAdo->alteraObjeto($this->fornecedorModel);
            if ($alterou) {
                $this->fornecedorView->adicionaMensagem("Tudo Certo! Alterado!");
                $this->acao = "inc";
            } else {
                $this->fornecedorView->adicionaMensagem("Deu Errado! Não alterado!");
            }
        } else {
            $this->fornecedorView->adicionaMensagens($this->fornecedorModel->getMensagens());
        }
    }

    protected function exclui() {
        $this->fornecedorModel = $this->fornecedorView->recebeDadosDoFormulario();

        $excluiu = $this->fornecedorAdo->excluiObjeto($this->fornecedorModel);

        if ($excluiu) {
            $this->fornecedorView->adicionaMensagem("Tudo Certo! Excluido!");
            $this->acao = "inc";
        } else {
            $this->fornecedorView->adicionaMensagem("Deu Errado! Não Excluido!");
        }
    }

}
