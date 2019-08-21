<?php

require_once '../View/interfacehtml.class.php';
require_once '../ADO/associafornecedorprodutoado.class.php';
require_once '../Model/associafornecedorprodutomodel.class.php';
//require_once '../ADO/fornecedorado.class.php';
//require_once '../Model/fornecedormodel.class.php';


class AssociaFornecedorProdutoView extends InterfaceHtml {

    private $form = null;

    protected function montaForms($associaFornecedorProdutoModel, $acao) {
        $codigoHtml = null;
        
        $this->form = new FormHtml();
        $this->form->setAction("associafornecedorproduto.php");
        $this->form->setMethod("post");

        
       $codigoHtml .= "<h1>Associa Fornecedor a Produto</h1>";
       
       //$codigoHtml .= $this->montaFormConsulta();


        $legend = new LegendHtml("Associação");
        $fieldset = new FieldsetHtml ();
        $fieldset->setLegend($legend);
        

        $this->montaInputs($associaFornecedorProdutoModel);

        //monta os botoes.
        $botoes = $this->montaBotoesDeAcordoComAAcao($acao);
        foreach ($botoes as $buttonHtml) {
            $this->form->adicionaObjeto($buttonHtml);
        }

        foreach ($associaFornecedorProdutoModel as $fornecedor) {
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
        
        $prodId = null;
        if (isset($_POST['prodId'])) {
            $prodId = $_POST['prodId'];
        }
        
       

        return new AssociaFornecedorProdutoModel($fornCnpj, $prodId);
    }

   

    private function montaInputs($associaFornecedorProdutoModel) {
        
        $p = new PHtml();
        $br = new BrHtml();

        // Inicia Paragrafo e Adiciona ao array de objetos
        $this->form->adicionaObjeto($br);

        //Label cnpj fornecedor
        $labelFornecedor = new LabelHtml();
        $labelFornecedor->setTexto("Informe o CNPJ do fornecedor:");
        //Adiciona ao array de objetos
        $p->adicionaObjeto($labelFornecedor);

        //Input cnpj fornecedor
        $inputFornecedor = new InputHtml();
        $inputFornecedor->setType("text");
        $inputFornecedor->setName("fproFornCnpj");
        $inputFornecedor->setValue($associaFornecedorProdutoModel->getFornCnpj());
        $inputFornecedor->setDisabled(true);

        $p->adicionaObjeto($inputFornecedor);

        $this->form->adicionaObjeto($p);

        $inputFornecedor->setDisabled(false);

        //Novo paragrafo
        $p = new PHtml();

        
        $labelProduto = new LabelHtml();
        $labelProduto->setTexto("Informe o ID do produto:");
        //Adiciona ao array de objetos
        $p->adicionaObjeto($labelProduto);

        //Input nome do fornecedor
        $inputProduto = new InputHtml();
        $inputProduto->setType("text");
        $inputProduto->setName("fproFornId");
        $inputProduto->setValue($associaFornecedorProdutoModel->getProdId());
        $inputProduto->setDisabled(true);
        //Adiciona ao array de objetos
        $p->adicionaObjeto($inputProduto);
        // Termina Paragrafo e Adiciona ao array de objetos
        $this->form->adicionaObjeto($p);

        $inputProduto->setDisabled(false);
        


        //Adiciona ao array de objetos uma quebra de linha
        $this->form->adicionaObjeto($br);
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

    


}


