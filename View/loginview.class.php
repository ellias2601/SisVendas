<?php


require_once '../View/interfacehtml.class.php';
require_once '../Model/loginmodel.class.php';
require_once '../Controller/logincontroller.class.php';
require_once '../Classes/legendhtml.class.php';


use Classes\LegendHtml;
use Classes\BrHtml;
use Classes\PHtml;
use Classes\InputHtml;
use Classes\FieldsetHtml;
use Classes\FormHtml;
use Classes\LabelHtml;

class LoginView extends InterfaceHtml {

    private $form = null;

    protected function montaForms($loginModel, $acao) {
        $codigoHtml = null;
        $this->form = new FormHtml();
        $this->form->setAction("login.php");
        $this->form->setMethod("post");

        $codigoHtml .= "<h1>Login</h1>";


        $legend = new LegendHtml("Login");
        $fieldset = new FieldsetHtml();
        $fieldset->setLegend($legend);

        $this->montaInputs($loginModel);

        //monta os botoes.
        $botoes = $this->montaBotoesDeAcordoComAAcaoLogin($acao);
        foreach ($botoes as $buttonHtml) {
            $this->form->adicionaObjeto($buttonHtml);
        }

        $fieldset->adicionaObjeto($this->form);
        $codigoHtml .= $fieldset->geraHtml();

        return $codigoHtml;
    }

    public function recebeDadosDoFormulario() {
        $loginId = parent::getValorOuNull('loginId');
        $loginCpf = parent::getValorOuNull('loginCpf');

        return new LoginModel($loginId, $loginCpf);
    }

    private function montaFormConsulta() {
        //...
    }

    private function montaInputs($loginModel) {

        $p = new PHtml();
        $br = new BrHtml();

        //Input id do cliente
        $inputIdDoCliente = new InputHtml();
        $inputIdDoCliente->setType("hidden");
        $inputIdDoCliente->setName("loginId");
        $inputIdDoCliente->setValue($loginModel->getLoginId());
        
        $this->form->adicionaObjeto($inputIdDoCliente);
        
        $p = new PHtml();
        
        //Label login cpf
        $labelLoginCpf = new LabelHtml();
        $labelLoginCpf->setTexto("Informe o CPF ");
        $p->adicionaObjeto($labelLoginCpf);

        //Input login cpf
        $inputLoginCpf = new InputHtml();
        $inputLoginCpf->setType("text");
        $inputLoginCpf->setName("loginCpf");
        $inputLoginCpf->setValue($loginModel->getLoginCpf());
        $inputLoginCpf->setDisabled(true);
        $p->adicionaObjeto($inputLoginCpf);
        $this->form->adicionaObjeto($p);
        $inputLoginCpf->setDisabled(false);

        $this->form->adicionaObjeto($br);
    }

}
