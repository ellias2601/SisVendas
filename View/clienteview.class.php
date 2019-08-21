<?php

require_once '../View/interfacehtml.class.php';
require_once '../ADO/clienteado.class.php';
require_once '../Model/clientemodel.class.php';
use Classes\labelhtml as LabelHtml;
use Classes\buttonhtml as ButtonHtml;
use Classes\brhtml as BrHtml;
use Classes\phtml as PHtml;
use Classes\inputhtml as InputHtml;
use Classes\legendhtml as LegendHtml;
use Classes\fieldsethtml as FieldsetHtml;
use Classes\formhtml as FormHtml;

class ClienteView extends InterfaceHtml {

    private $form = null;

    protected function montaForms($clientesModel, $acao) {
        
        $codigoHtml = null;
        
        $this->form = new FormHtml();
        $this->form->setAction("cadastracliente.php");
        $this->form->setMethod("post");

        $codigoHtml.= "<h1>Cadastro de Cliente</h1>";

        $codigoHtml.=  $this->montaFormConsulta();
        
        $legend = new LegendHtml("Cliente");
        $fieldset = new FieldsetHtml ();
        $fieldset->setLegend($legend);

        $this->montaInputs($clientesModel);

        //monta os botoes.
        $botoes = $this->montaBotoesDeAcordoComAAcao($acao);
        foreach ($botoes as $buttonHtml) {
            $this->form->adicionaObjeto($buttonHtml);
        }

        $fieldset->adicionaObjeto($this->form);
        $codigoHtml .= $fieldset->geraHtml();

        return $codigoHtml;

    }

    public function recebeDadosDoFormulario() {
        $clieId = null;
        if (isset($_POST['clieId'])) {
            $clieId = $_POST['clieId'];
        }
        
        $clieNome = null;
        if (isset($_POST['clieNome'])) {
            $clieNome = $_POST['clieNome'];
        }
        
        $clieCpf = null;
        if (isset($_POST['clieCpf'])) {
            $clieCpf = $_POST['clieCpf'];
        }
        
        $clieRg = null;
        if (isset($_POST['clieRg'])) {
            $clieRg = $_POST['clieRg'];
        }
        
        $clieUfRg = null;
        if (isset($_POST['clieUfRg'])) {
            $clieUfRg = $_POST['clieUfRg'];
        }
        
        $clieRgDtExpedicao = null;
        if (isset($_POST['clieRgDtExpedicao'])) {
            $clieRgDtExpedicao = $_POST['clieRgDtExpedicao'];
        }
        
        $clieFone = null;
        if (isset($_POST['clieFone'])) {
            $clieFone = $_POST['clieFone'];
        }
        
        $clieEmail = null;
        if (isset($_POST['clieEmail'])) {
            $clieEmail = $_POST['clieEmail'];
        }
        
        return new ClienteModel($clieId, $clieNome, $clieCpf, $clieRg, $clieUfRg, $clieRgDtExpedicao, $clieFone, $clieEmail);
    }

    private function montaFormConsulta() {
        
        $legend = new LegendHtml("Consulta");
        $fieldset = new FieldsetHtml ();
        $fieldset->setLegend($legend);
        
        $formHtml = new FormHtml("cadastracliente.php");
        $labelHtml = new LabelHtml("Clientes");
        $selectHtml = new SelectHtml("clieId");

        $clienteAdo = new ClienteAdo();
        
        try {
            $buscou =   $clientesModel = $clienteAdo->buscaTodosOsClientes();
        } catch (Exception $e) {
            parent::adicionaMensagem("Ocorreu um erro ao buscar os clientes da base de dados! Contate o responsável pelo sistema.");
        }
        if ($buscou) {
            //continua
        } else {
            if ($buscou === 0) {
                parent::adicionaMensagem("Não existe nenhum cliente no banco de dados.");
            } else {
                parent::adicionaMensagem("Ocorreu um erro ao buscar os clientes da base de dados! Contate o responsável pelo sistema.");
            }
            $clientesModel = array();
        }

        $optionHtml = new OptionHtml(null, FALSE, "Selecione um cliente...");
        $selectHtml->adicionaOption($optionHtml);
      
        
        foreach ($clientesModel as $clienteModel) {
            $optionHtml = new OptionHtml($clienteModel->getClieId(), FALSE, $clienteModel->getClieNome());
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

    private function montaInputs($clienteModel) {
        $p = new PHtml();
        $br = new BrHtml();

        // Inicia Paragrafo e Adiciona ao array de objetos
        $this->form->adicionaObjeto($br);

        //Input numero do cliente
        $inputNumeroDoCliente = new InputHtml();
        $inputNumeroDoCliente->setType("hidden");
        $inputNumeroDoCliente->setName("clieId");
        $inputNumeroDoCliente->setValue($clienteModel->getClieId());
        $inputNumeroDoCliente->setDisabled(true);

        $p->adicionaObjeto($inputNumeroDoCliente);

        $this->form->adicionaObjeto($p);

        $inputNumeroDoCliente->setDisabled(false);

        //Novo paragrafo
        $p = new PHtml();

        //Label nome do cliente
        $labelNomeDoCliente = new LabelHtml();
        $labelNomeDoCliente->setTexto("Informe o nome do cliente:");
        //Adiciona ao array de objetos
        $p->adicionaObjeto($labelNomeDoCliente);

        //Input nome do cliente
        $inputNomeDoCliente = new InputHtml();
        $inputNomeDoCliente->setType("text");
        $inputNomeDoCliente->setName("clieNome");
        $inputNomeDoCliente->setValue($clienteModel->getClieNome());
        $inputNomeDoCliente->setDisabled(true);
        //Adiciona ao array de objetos
        $p->adicionaObjeto($inputNomeDoCliente);
        // Termina Paragrafo e Adiciona ao array de objetos
        $this->form->adicionaObjeto($p);

        $inputNomeDoCliente->setDisabled(false);
        
        //aqui
         $p = new PHtml();

        //Label cpf do cliente
        $labelCpfDoCliente = new LabelHtml();
        $labelCpfDoCliente->setTexto("Informe o CPF do cliente:");
        //Adiciona ao array de objetos
        $p->adicionaObjeto($labelCpfDoCliente);
        
         //Input cpf do cliente
        $inputCpfDoCliente = new InputHtml();
        $inputCpfDoCliente->setType("text");
        $inputCpfDoCliente->setName("clieCpf");
        $inputCpfDoCliente->setValue($clienteModel->getClieCpf());
        $inputCpfDoCliente->setDisabled(true);
        //Adiciona ao array de objetos
        $p->adicionaObjeto($inputCpfDoCliente);
        // Termina Paragrafo e Adiciona ao array de objetos
        $this->form->adicionaObjeto($p);

        $inputCpfDoCliente->setDisabled(false);
        
        //Novo paragrafo
        $p = new PHtml();

        //Label rg do cliente
        $labelRgDoCliente = new LabelHtml();
        $labelRgDoCliente->setTexto("Informe o RG do cliente:");
        //Adiciona ao array de objetos
        $p->adicionaObjeto($labelRgDoCliente);

        //Input rg do cliente
        $inputRgDoCliente = new InputHtml();
        $inputRgDoCliente->setType("text");
        $inputRgDoCliente->setName("clieRg");
        $inputRgDoCliente->setValue($clienteModel->getClieRg());
        $inputRgDoCliente->setDisabled(true);
        //Adiciona ao array de objetos
        $p->adicionaObjeto($inputRgDoCliente);
        // Termina Paragrafo e Adiciona ao array de objetos
        $this->form->adicionaObjeto($p);

        $inputRgDoCliente->setDisabled(false);
        
         //Novo paragrafo
        $p = new PHtml();

        //Label uf rg do cliente
        $labelUfRgDoCliente = new LabelHtml();
        $labelUfRgDoCliente->setTexto("Informe a UF presente no RG do cliente:");
        //Adiciona ao array de objetos
        $p->adicionaObjeto($labelUfRgDoCliente);

        //Input uf rg do cliente
        $inputUfRgDoCliente = new InputHtml();
        $inputUfRgDoCliente->setType("text");
        $inputUfRgDoCliente->setName("clieUfRg");
        $inputUfRgDoCliente->setValue($clienteModel->getClieUfRg());
        $inputUfRgDoCliente->setDisabled(true);
        //Adiciona ao array de objetos
        $p->adicionaObjeto($inputUfRgDoCliente);
        // Termina Paragrafo e Adiciona ao array de objetos
        $this->form->adicionaObjeto($p);

        $inputUfRgDoCliente->setDisabled(false);
        
         //Novo paragrafo
        $p = new PHtml();

        //Label rg dt expedicao do cliente
        $labelRgDtExpedicaoDoCliente = new LabelHtml();
        $labelRgDtExpedicaoDoCliente->setTexto("Informe a data de expedição presente no RG do cliente:");
        //Adiciona ao array de objetos
        $p->adicionaObjeto($labelRgDtExpedicaoDoCliente);

        //Input rg dt expedicao do cliente
        $inputRgDtExpedicaoDoCliente = new InputHtml();
        $inputRgDtExpedicaoDoCliente->setType("date");
        $inputRgDtExpedicaoDoCliente->setName("clieRgDtExpedicao");
        $inputRgDtExpedicaoDoCliente->setValue($clienteModel->getClieRgDtExpedicao());
        $inputRgDtExpedicaoDoCliente->setDisabled(true);
        //Adiciona ao array de objetos
        $p->adicionaObjeto($inputRgDtExpedicaoDoCliente);
        // Termina Paragrafo e Adiciona ao array de objetos
        $this->form->adicionaObjeto($p);

        $inputRgDtExpedicaoDoCliente->setDisabled(false);
        
        
        //Novo paragrafo
        $p = new PHtml();

        //Label fone do cliente
        $labelFoneCliente = new LabelHtml();
        $labelFoneCliente->setTexto("Informe o telefone  do cliente:");
        //Adiciona ao array de objetos
        $p->adicionaObjeto($labelFoneCliente);

        //Input fone do cliente
        $inputFoneCliente = new InputHtml();
        $inputFoneCliente->setType("text");
        $inputFoneCliente->setName("clieFone");
        $inputFoneCliente->setValue($clienteModel->getClieFone());
        $inputFoneCliente->setDisabled(true);
        //Adiciona ao array de objetos
        $p->adicionaObjeto($inputFoneCliente);
        // Termina Paragrafo e Adiciona ao array de objetos
        $this->form->adicionaObjeto($p);
        $inputFoneCliente->setDisabled(false);
        
        //Novo paragrafo
        $p = new PHtml();

        //Label email do cliente
        $labelEmailDoCliente = new LabelHtml();
        $labelEmailDoCliente->setTexto("Informe o email do cliente:");
        //Adiciona ao array de objetos
        $p->adicionaObjeto($labelEmailDoCliente);

        //Input email do cliente
        $inputEmailDoCliente = new InputHtml();
        $inputEmailDoCliente->setType("text");
        $inputEmailDoCliente->setName("clieEmail");
        $inputEmailDoCliente->setValue($clienteModel->getClieEmail());
        $inputEmailDoCliente->setDisabled(true);
        //Adiciona ao array de objetos
        $p->adicionaObjeto($inputEmailDoCliente);
        // Termina Paragrafo e Adiciona ao array de objetos
        $this->form->adicionaObjeto($p);

        $inputEmailDoCliente->setDisabled(false);
        
        
        //$this->form->adicionaObjeto($p);

        //Adiciona ao array de objetos uma quebra de linha
        $this->form->adicionaObjeto($br);
    }

}
