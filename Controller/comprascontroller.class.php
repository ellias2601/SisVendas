<?php

require_once '../Controller/controllerabstract.class.php';
require_once '../Model/produtomodel.class.php';
require_once '../View/produtoview.class.php';
require_once '../ADO/produtoado.class.php';
require_once '../ADO/associafornecedorprodutoado.class.php';
require_once '../Model/associafornecedorprodutomodel.class.php';
//require_once '../View/precomprasview.class.php';
require_once '../Model/cestasdecomprasmodel.class.php';
require_once '../ADO/cestasdecomprasado.class.php';
//require_once '../ADO/precomprasado.class.php';
//require_once '../Model/precomprasmodel.class.php';
require_once '../View/comprasview.class.php';
require_once '../Model/comprasmodel.class.php';
require_once '../ADO/comprasado.class.php';
require_once '../Model/itensdacompramodel.class.php';
require_once '../ADO/itensdacompraado.class.php';

class ComprasController extends ControllerAbstract {

    public function __construct() {

        $this->scProdutoModelFornecedor = new stdClass();

        $this->produtoAdo = new ProdutoAdo();
        $this->comprasAdo = new ComprasAdo();
        $this->cestasDeComprasAdo = new CestasDeComprasAdo();
        //$this->preComprasAdo = new PreComprasAdo();
        $this->itensDaCompraAdo = new ItensDaCompraAdo();

        $this->associaFornecedorProdutoAdo = new AssociaFornecedorProdutoAdo();
        $this->AssociaFornecedorProdutoModel = new AssociaFornecedorProdutoModel();

        $this->produtoModel = new ProdutoModel();
        //$this->preComprasModel = new PreComprasModel();
        $this->comprasModel = new ComprasModel();
        $this->cestasDeComprasModel = new CestasdeComprasModel();
        $this->itensDaCompraModel = new ItensDaCompraModel();


        $this->produtoView = new ProdutoView("Produtos");

        $this->comprasView = new ComprasView("Confirmar Compra");

        $this->acao = $this->comprasView->getBt();

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

            case "ret":
                $this->retornaPreCompra();
                break;
            
            case "fin":
                $this->finalizarCompra();
                break;

            default:
                break;
        }
        
        $this->comprasView->displayInterface($this->produtoModel, $this->acao);
       
    }

    protected function inclui() {

         session_start();
         
        $this->produtoAdo->beginTransaction();

        $clieId = $_SESSION['clieId'];
        $comprasModel = new ComprasModel(null, $clieId);


        $arrayDaQueryEDosValores = $this->comprasAdo->montaInsereDoObjeto($comprasModel);

        $inseriu = $this->produtoAdo->executaPs($arrayDaQueryEDosValores[0], $arrayDaQueryEDosValores[1]);

        if ($inseriu) {
            $this->comprasView->adicionaMensagem("Estrutura de compra criada com sucesso!!");
        } else {
            $this->comprasView->adicionaMensagem("Houve um problema ao processar sua compra!!");
            $this->produtoAdo->rollBack();
            return;
        }



        $idCompra = $this->produtoAdo->recuperaId("Compras");
        $itenCompId = $_SESSION['compId'] = $idCompra;
        // $itenProdId = 2; //??? Como inserir todos os ids de uma so vez??????????????????
       
        

        $idsProdutoNaCesta = $this->produtoAdo->buscaIdProdutosNaCesta($_SESSION['cestId']);

        foreach ($idsProdutoNaCesta as $idProdutos) {

            $itensDaCompraModel = new ItensDaCompraModel($itenCompId, $idProdutos);


            $arrayDaQueryEDosValores = $this->itensDaCompraAdo->montaInsereDoObjeto($itensDaCompraModel);

            $inseriu = $this->produtoAdo->executaPs($arrayDaQueryEDosValores[0], $arrayDaQueryEDosValores[1]);
            if ($inseriu) {
                $this->comprasView->adicionaMensagem("Produto adquirido com sucesso!!");
                $this->acao = "fin";
            } else {
                $this->comprasView->adicionaMensagem("Houve um erro ao adcionar produtos a compra!!");
                $this->produtoAdo->rollBack();
                $this->acao = "inc";
                return;
            }
        }



        $this->produtoAdo->commit();
    }
    
    protected function retornaPreCompra() {

        header('Location: http://localhost/ModuloFornecedor/Modulos/cadastraprecompras.php');
    }
    
    protected function finalizarCompra() {
       
        session_start();
        session_destroy();
        header('Location: http://localhost/ModuloFornecedor/Modulos/login.php');
        
    }

    protected function exclui() {
        
    }

    protected function consulta() {
        
    }

    protected function altera() {
        
    }

    

}
