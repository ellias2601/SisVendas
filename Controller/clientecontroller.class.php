<?php

require_once '../Controller/controllerabstract.class.php';
require_once '../ADO/clienteado.class.php';
require_once '../Model/clientemodel.class.php';
require_once '../View/clienteview.class.php';

class ClienteController extends ControllerAbstract {

    private $clienteView = null;
    private $clienteModel = null;
    private $clienteAdo = null;

    public function __construct() {
        $this->clienteAdo = new ClienteAdo();
        $this->clienteModel = new ClienteModel();

        $this->clienteView = new ClienteView("Cadastro de Clientes");

        $this->acao = $this->clienteView->getBt();

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

        $this->clienteView->displayInterface($this->clienteModel, $this->acao);
    }

    protected function consulta() {
        $this->clienteModel = $this->clienteView->recebeDadosDoFormulario();

        $buscou = $clienteModel = $this->clienteAdo->buscaCliente($this->clienteModel->getClieId());
        if ($buscou) {
            $this->clienteModel = $clienteModel;
        } else {
            $this->clienteView->adicionaMensagem("Não foi possivel buscar os dados! Contate o responsável pelo sistema.");
        }
    }

    protected function inclui() {
        $this->clienteModel = $this->clienteView->recebeDadosDoFormulario();
        $checagemOk = $this->clienteModel->checaAtributos();
        if ($checagemOk) {
            $inseriu = $this->clienteAdo->insereObjeto($this->clienteModel);
            if ($inseriu) {
                $this->clienteView->adicionaMensagem("Tudo Certo!");
            } else {
                $this->clienteView->adicionaMensagem("Deu Errado!");
            }
        } else {
            $this->clienteView->adicionaMensagens($this->clienteModel->getMensagens());
        }
    }

    protected function altera() {
        $this->clienteModel = $this->clienteView->recebeDadosDoFormulario();

        $checagemOk = $this->clienteModel->checaAtributos();
        if ($checagemOk) {
            $inseriu = $this->clienteAdo->alteraObjeto($this->clienteModel);
            if ($inseriu) {
                $this->clienteView->adicionaMensagem("Tudo Certo! Alterado!");
                $this->acao = "inc";
            } else {
                $this->clienteView->adicionaMensagem("Deu Errado! Não alterado!");
            }
        } else {
            $this->clienteView->adicionaMensagens($this->clienteModel->getMensagens());
        }
    }

    protected function exclui() {
        $this->clienteModel = $this->clienteView->recebeDadosDoFormulario();

        $inseriu = $this->clienteAdo->excluiObjeto($this->clienteModel);
        if ($inseriu) {
            $this->clienteView->adicionaMensagem("Tudo Certo! Excluido!");
            $this->acao = "inc";
        } else {
            $this->clienteView->adicionaMensagem("Deu Errado! Não Excluido!");
        }
    }

}
