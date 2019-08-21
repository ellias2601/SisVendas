<?php


require_once '../View/interfacehtml.class.php';
require_once '../ADO/fornecedorado.class.php';
require_once '../Model/fornecedormodel.class.php';
use Classes\labelhtml as LabelHtml;
use Classes\buttonhtml as ButtonHtml;
use Classes\brhtml as BrHtml;
use Classes\phtml as PHtml;
use Classes\inputhtml as InputHtml;
use Classes\legendhtml as LegendHtml;
use Classes\fieldsethtml as FieldsetHtml;
use Classes\formhtml as FormHtml;

class FornecedorView extends InterfaceHtml {

    private $form = null;

    protected function montaForms($fornecedoresModel, $acao) {
        $codigoHtml = null;
        
        $this->form = new FormHtml();
        $this->form->setAction("cadastrafornecedor.php");
        $this->form->setMethod("post");

        
       $codigoHtml .= "<h1>Cadastro de Fornecedores</h1>";

        $codigoHtml .= $this->montaFormConsulta();

        $legend = new LegendHtml("Fornecedor");
        $fieldset = new FieldsetHtml ();
        $fieldset->setLegend($legend);
        

        $this->montaInputs($fornecedoresModel);

        //monta os botoes.
        $botoes = $this->montaBotoesDeAcordoComAAcao($acao);
        foreach ($botoes as $buttonHtml) {
            $this->form->adicionaObjeto($buttonHtml);
        }

        foreach ($fornecedoresModel as $fornecedor) {
            if (is_null($fornecedor->getFornCnpj())) {
                //continua
            } else {
                $this->montaInputs($fornecedor);
            }
        }
        
        $fieldset->adicionaObjeto($this->form);
        $codigoHtml .= $fieldset->geraHtml();

        return $codigoHtml;
    }

    public function recebeDadosDoFormulario() {
        $fornCnpj= null;
        if (isset($_POST['fornCnpj'])) {
            $fornCnpj = $_POST['fornCnpj'];
        }
        
        $fornNome = null;
        if (isset($_POST['fornNome'])) {
            $fornNome = $_POST['fornNome'];
        }
        
        $fornFone1 = null;
        if(isset($_POST['fornFone1'])){
            $fornFone1 = $_POST['fornFone1'];
        }
        
        $fornFone2 = null;
        if(isset($_POST['fornFone2'])){
            $fornFone2 = $_POST['fornFone2'];
        }
        
        $fornEnd = null;
        if(isset($_POST['fornEnd'])){
            $fornEnd = $_POST['fornEnd'];
        }
        
        $fornCep = null;
        if(isset($_POST['fornCep'])){
            $fornCep = $_POST['fornCep'];
        }
        
        $fornCidade = null;
        if(isset($_POST['fornCidade'])){
            $fornCidade = $_POST['fornCidade'];
        }
        
        $fornUf = null;
        if(isset($_POST['fornUf'])){
            $fornUf = $_POST['fornUf'];
        }

        return new FornecedorModel($fornCnpj, $fornNome, $fornFone1, $fornFone2, $fornEnd, $fornCep, $fornCidade, $fornUf);
    }

    private function montaFormConsulta() {
        
        $legend = new LegendHtml("Consulta");
        $fieldset = new FieldsetHtml ();
        $fieldset->setLegend($legend);
        
        $formHtml = new FormHtml();
        $formHtml->setAction("cadastrafornecedor.php");
        
        $labelHtml = new LabelHtml("Fornecedores");
        $labelHtml->setTexto("Fornecedores");
        
        $selectHtml = new SelectHtml();
        $selectHtml->setName("fornCnpj");

        $fornecedorAdo = new FornecedorAdo();
        
         try {
            $buscou = $fornecedoresModel = $fornecedorAdo->buscaTodosOsFornecedores();
        } catch (Exception $e) {
            parent::adicionaMensagem("Ocorreu um erro ao buscar os produtos da base de dados! Contate o responsável pelo sistema.");
        }
        if ($buscou) {
            //continua
        } else {
            if ($buscou === 0) {
                parent::adicionaMensagem("Não existe nenhum fornecedor cadastrado no banco de dados.");
            } else {
                parent::adicionaMensagem("Ocorreu um erro ao buscar os produtos da base de dados! Contate o responsável pelo sistema.");
            }
            $fornecedoresModel = array();
        }
        
        $optionHtml = new OptionHtml(null, FALSE, "Selecione um fornecedor...");
        $selectHtml->adicionaOption($optionHtml);
        
        foreach ($fornecedoresModel as $fornecedorModel) {
            $optionHtml = new OptionHtml($fornecedorModel->getFornCnpj(), FALSE, $fornecedorModel->getFornNome());
            $selectHtml->adicionaOption($optionHtml);
        }

        $buttonHtml = new ButtonHtml();
        
        $buttonHtml->setType("input");
        $buttonHtml->setName("bt");
        $buttonHtml->setValue("con");
        $buttonHtml->setTexto("CONSULTAR");

        $formHtml->adicionaObjeto($labelHtml);
        $formHtml->adicionaObjeto($selectHtml);
        $formHtml->adicionaObjeto(new BrHtml());
        $formHtml->adicionaObjeto($buttonHtml);

        $fieldset->adicionaObjeto($formHtml);

        return $fieldset->geraHtml();
    }

    private function montaInputs($fornecedorModel) {
        
        $p = new PHtml();
        $br = new BrHtml();

        // Inicia Paragrafo e Adiciona ao array de objetos
        $this->form->adicionaObjeto($br);

        //Label cnpj fornecedor
        $labelCnpjFornecedor = new LabelHtml();
        $labelCnpjFornecedor->setTexto("Informe o CNPJ do fornecedor:");
        //Adiciona ao array de objetos
        $p->adicionaObjeto($labelCnpjFornecedor);

        //Input cnpj fornecedor
        $inputCnpjFornecedor = new InputHtml();
        $inputCnpjFornecedor->setType("text");
        $inputCnpjFornecedor->setName("fornCnpj");
        $inputCnpjFornecedor->setValue($fornecedorModel->getFornCnpj());
        $inputCnpjFornecedor->setDisabled(true);

        $p->adicionaObjeto($inputCnpjFornecedor);

        $this->form->adicionaObjeto($p);

        $inputCnpjFornecedor->setDisabled(false);

        //Novo paragrafo
        $p = new PHtml();

        //Label nome do fornecedor
        $labelNomeFornecedor = new LabelHtml();
        $labelNomeFornecedor->setTexto("Informe o Nome do fornecedor:");
        //Adiciona ao array de objetos
        $p->adicionaObjeto($labelNomeFornecedor);

        //Input nome do fornecedor
        $inputNomeFornecedor = new InputHtml();
        $inputNomeFornecedor->setType("text");
        $inputNomeFornecedor->setName("fornNome");
        $inputNomeFornecedor->setValue($fornecedorModel->getFornNome());
        $inputNomeFornecedor->setDisabled(true);
        //Adiciona ao array de objetos
        $p->adicionaObjeto($inputNomeFornecedor);
        // Termina Paragrafo e Adiciona ao array de objetos
        $this->form->adicionaObjeto($p);

        $inputNomeFornecedor->setDisabled(false);
        
         //Novo paragrafo
        $p = new PHtml();

        //Label fone1 do fornecedor
        $labelFone1Fornecedor = new LabelHtml();
        $labelFone1Fornecedor->setTexto("Informe o Telefone 1 do fornecedor:");
        //Adiciona ao array de objetos
        $p->adicionaObjeto($labelFone1Fornecedor);

        //Input fone1 do fornecedor
        $inputFone1Fornecedor = new InputHtml();
        $inputFone1Fornecedor->setType("text");
        $inputFone1Fornecedor->setName("fornFone1");
        $inputFone1Fornecedor->setValue($fornecedorModel->getFornFone1());
        $inputFone1Fornecedor->setDisabled(true);
        //Adiciona ao array de objetos
        $p->adicionaObjeto($inputFone1Fornecedor);
        // Termina Paragrafo e Adiciona ao array de objetos
        $this->form->adicionaObjeto($p);
        $inputFone1Fornecedor->setDisabled(false);
        
        //Novo paragrafo
        $p = new PHtml();

        //Label fone2 do fornecedor
        $labelFone2Fornecedor = new LabelHtml();
        $labelFone2Fornecedor->setTexto("Informe o Telefone 2 do fornecedor:");
        //Adiciona ao array de objetos
        $p->adicionaObjeto($labelFone2Fornecedor);

        //Input fone2 do fornecedor
        $inputFone2Fornecedor = new InputHtml();
        $inputFone2Fornecedor->setType("text");
        $inputFone2Fornecedor->setName("fornFone2");
        $inputFone2Fornecedor->setValue($fornecedorModel->getFornFone2());
        $inputFone2Fornecedor->setDisabled(true);
        //Adiciona ao array de objetos
        $p->adicionaObjeto($inputFone2Fornecedor);
        // Termina Paragrafo e Adiciona ao array de objetos
        $this->form->adicionaObjeto($p);

        $inputFone2Fornecedor->setDisabled(false);
        
        
         //Novo paragrafo
        $p = new PHtml();

        //Label endereco do fornecedor
        $labelEnderecoFornecedor = new LabelHtml();
        $labelEnderecoFornecedor->setTexto("Informe o Endereco do fornecedor:");
        //Adiciona ao array de objetos
        $p->adicionaObjeto($labelEnderecoFornecedor);

        //Input endereco do fornecedor
        $inputEnderecoFornecedor = new InputHtml();
        $inputEnderecoFornecedor->setType("text");
        $inputEnderecoFornecedor->setName("fornEnd");
        $inputEnderecoFornecedor->setValue($fornecedorModel->getFornEnd());
        $inputEnderecoFornecedor->setDisabled(true);
        //Adiciona ao array de objetos
        $p->adicionaObjeto($inputEnderecoFornecedor);
        // Termina Paragrafo e Adiciona ao array de objetos
        $this->form->adicionaObjeto($p);

        $inputEnderecoFornecedor->setDisabled(false);
        
        //Novo paragrafo
        $p = new PHtml();

        //Label cep do fornecedor
        $labelCepFornecedor = new LabelHtml();
        $labelCepFornecedor->setTexto("Informe o CEP do fornecedor:");
        //Adiciona ao array de objetos
        $p->adicionaObjeto($labelCepFornecedor);

        //Input fone2 do fornecedor
        $inputCepFornecedor = new InputHtml();
        $inputCepFornecedor->setType("text");
        $inputCepFornecedor->setName("fornCep");
        $inputCepFornecedor->setValue($fornecedorModel->getFornCep());
        $inputCepFornecedor->setDisabled(true);
        //Adiciona ao array de objetos
        $p->adicionaObjeto($inputCepFornecedor);
        // Termina Paragrafo e Adiciona ao array de objetos
        $this->form->adicionaObjeto($p);

        $inputCepFornecedor->setDisabled(false);
        
        
        //Novo paragrafo
        $p = new PHtml();

        //Label cidade do fornecedor
        $labelCidadeFornecedor = new LabelHtml();
        $labelCidadeFornecedor->setTexto("Informe a Cidade do fornecedor:");
        //Adiciona ao array de objetos
        $p->adicionaObjeto($labelCidadeFornecedor);

        //Input fone2 do fornecedor
        $inputCidadeFornecedor = new InputHtml();
        $inputCidadeFornecedor->setType("text");
        $inputCidadeFornecedor->setName("fornCidade");
        $inputCidadeFornecedor->setValue($fornecedorModel->getFornCidade());
        $inputCidadeFornecedor->setDisabled(true);
        //Adiciona ao array de objetos
        $p->adicionaObjeto($inputCidadeFornecedor);
        // Termina Paragrafo e Adiciona ao array de objetos
        $this->form->adicionaObjeto($p);

        $inputCidadeFornecedor->setDisabled(false);
        
        
        //Novo paragrafo
        $p = new PHtml();

        //Label uf do fornecedor
        $labelUfFornecedor = new LabelHtml();
        $labelUfFornecedor->setTexto("Informe a UF do fornecedor:");
        //Adiciona ao array de objetos
        $p->adicionaObjeto($labelUfFornecedor);

        //Input fone2 do fornecedor
        $inputUfFornecedor = new InputHtml();
        $inputUfFornecedor->setType("text");
        $inputUfFornecedor->setName("fornUf");
        $inputUfFornecedor->setValue($fornecedorModel->getFornUf());
        $inputUfFornecedor->setDisabled(true);
        //Adiciona ao array de objetos
        $p->adicionaObjeto($inputUfFornecedor);
        // Termina Paragrafo e Adiciona ao array de objetos
        $this->form->adicionaObjeto($p);

        $inputUfFornecedor->setDisabled(false);

        //Adiciona ao array de objetos uma quebra de linha
        $this->form->adicionaObjeto($br);
    }


}

