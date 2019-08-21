<?php


require_once '../ADO/loginado.class.php';
require_once '../Model/loginmodel.class.php';
require_once '../View/loginview.class.php';



class LoginController {

    private $loginView = null;
    private $loginModel = null;
    private $loginAdo = null;

    public function __construct() {
        $this->loginAdo = new LoginAdo();
        $this->loginModel = new LoginModel();
        $this->loginView = new LoginView("Login");

        $this->acao = $this->loginView->getBt();

        switch ($this->acao) {
            case "logar":
                $this->logar();

                break;

            case "deslogar":
                $this->deslogar();

                break;

            default :

                break;
        }
        $this->loginView->displayInterface($this->loginModel, $this->acao);
    }

    protected function logar() {
        $this->loginModel = $this->loginView->recebEDadosDoFormulario();

        $buscou = $loginModel = $this->loginAdo->buscaIdCliente($this->loginModel->getLoginCpf());
        if ($buscou) {
           
            header ("location:/ModuloFornecedor/Modulos/cadastraprecompras.php");
            
            $this->loginModel = $loginModel;
            $this->loginView->adicionaMensagem("Login efetuado!");
            //iniciar
            
            $this->acao = "deslogar";
            
        } else {
            $this->loginView->adicionaMensagem("Cpf não cadastrado na base de dados!!");
        }
    }

    protected function deslogar() {
        
        //finalizar
        
    }

}
