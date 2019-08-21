<?php

require_once '../Controller/controllerabstract.class.php';
require_once '../Model/produtomodel.class.php';
require_once '../View/produtoview.class.php';
require_once '../ADO/produtoado.class.php';
require_once '../ADO/associafornecedorprodutoado.class.php';
require_once '../Model/associafornecedorprodutomodel.class.php';
require_once '../View/precomprasview.class.php';
require_once '../Model/cestasdecomprasmodel.class.php';
require_once '../ADO/cestasdecomprasado.class.php';
require_once '../ADO/precomprasado.class.php';
require_once '../Model/precomprasmodel.class.php';

class PreComprasController extends ControllerAbstract {

    private $produtoView = null;
    private $preComprasView = null;
    private $produtoModel = null;
    private $produtoAdo = null;
    private $cestasDeComprasAdo = null;
    private $scProdutoModelFornecedor = null;
    private $associaFornecedorProdutoAdo = null;
    private $AssociaFornecedorProdutoModel = null;
    private $preComprasAdo = null;

    public function __construct() {

        $this->scProdutoModelFornecedor = new stdClass();

        $this->produtoAdo = new ProdutoAdo();
        $this->cestasDeComprasAdo = new CestasDeComprasAdo();
        $this->preComprasAdo = new PreComprasAdo();

        $this->associaFornecedorProdutoAdo = new AssociaFornecedorProdutoAdo();
        $this->AssociaFornecedorProdutoModel = new AssociaFornecedorProdutoModel();

        $this->produtoModel = new ProdutoModel();
        $this->preComprasModel = new PreComprasModel();
        $this->cestasDeComprasModel = new CestasdeComprasModel();

        $this->produtoView = new ProdutoView("Produtos");

        $this->preComprasView = new PreComprasView("Pré compras");

        $this->acao = $this->preComprasView->getBt();

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
            
            case "av":
                $this->avanca();
                break;


            default:
                break;
        }

        $this->preComprasView->displayInterface($this->produtoModel, $this->acao);
    }

    protected function consulta() {
        //corrigido de acordo com novo padrao
        $this->scProdutoModelFornecedor = $this->preComprasView->recebeDadosDoFormulario();
        $buscou = $produtoModel = $this->produtoAdo->buscaProduto($this->scProdutoModelFornecedor->produtoModel->getProdId());

        if ($buscou) {
            $this->produtoModel = $produtoModel;
            $this->acao = "addCesta";
        } else {
            $this->preComprasView->adicionaMensagem("Não foi possivel buscar os dados! Contate o responsável pelo sistema.");
            $this->acao = "addCesta";
        }
    }

    protected function altera() {

        session_start();
        session_destroy();
        header ("location:/ModuloFornecedor/Modulos/login.php");
    }

    protected function inclui() {

        $this->scProdutoModelFornecedor = $this->preComprasView->recebeCestaComProduto();
        //var_dump($this->scProdutoModelFornecedor);
        //$cestId = $this->scProdutoModelFornecedor->cestId;

        session_start();
       
        if (isset($_SESSION['cestId'])) {
            //se a cesta ja existir, somente adcionamos o produto a pre compra

            $precProdId = $this->scProdutoModelFornecedor->prodId;  //Este e o ID do produto selecionado
            $precCestId = $_SESSION['cestId'];
            $this->preComprasView->setCestId($precCestId);
            //Como obter o id da cesta criada anteriormente?? Será desta forma??

            $preComprasModel = new PreComprasModel($precProdId, $precCestId);


            $arrayDaQueryEDosValores = $this->preComprasAdo->montaInsereDoObjeto($preComprasModel);

            $inseriu = $this->preComprasAdo->executaPs($arrayDaQueryEDosValores[0], $arrayDaQueryEDosValores[1]);

            if ($inseriu) {
                $this->preComprasView->adicionaMensagem("Produto adcionado a cesta de compras com sucesso!!");
            } else {
                $this->preComprasView->adicionaMensagem("Houve um erro ao adcionar o produto a cesta de compras!!");
                return;
            }
        } else {
            
            $cestId = $_SESSION['cestId'] = null;

            if ($cestId == null) {
                $this->produtoAdo->beginTransaction();


                $cestClieId = $_SESSION['clieId']; // Como recuperar o id do cliente???
                $cestasDeComprasModel = new CestasdeComprasModel(null, $cestClieId);


                $arrayDaQueryEDosValores = $this->cestasDeComprasAdo->montaInsereDoObjeto($cestasDeComprasModel);

                $inseriu = $this->produtoAdo->executaPs($arrayDaQueryEDosValores[0], $arrayDaQueryEDosValores[1]);

                if ($inseriu) {
                    $this->preComprasView->adicionaMensagem("Cesta de compras criada com sucesso!!");
                } else {
                    $this->preComprasView->adicionaMensagem("Houve um problema na criação da cesta de compras!!");
                    $this->produtoAdo->rollBack();
                    return;
                }

                //agora sim, adcionamos o produto a pre compra

                $precProdId = $this->scProdutoModelFornecedor->prodId;
                $precCestId = $this->produtoAdo->recuperaId("CestasDeCompras");
                $this->preComprasView->setCestId($precCestId);

                // echo " <br>";
                //var_dump($precCestId);            

                $preComprasModel = new PreComprasModel($precProdId, $precCestId);

                $arrayDaQueryEDosValores = $this->preComprasAdo->montaInsereDoObjeto($preComprasModel);

                $inseriu = $this->produtoAdo->executaPs($arrayDaQueryEDosValores[0], $arrayDaQueryEDosValores[1]);
                if ($inseriu) {
                    $this->preComprasView->adicionaMensagem("Produto adicionado a cesta de compras com sucesso!!");
                } else {
                    $this->preComprasView->adicionaMensagem("Houve um erro ao adcionar o produto a cesta de compras!!");
                    $this->produtoAdo->rollBack();
                    return;
                }

                $this->produtoAdo->commit();
            }
        }

        $this->consulta();
    }

    protected function recuperaId() {
        $fproProdId = $this->produtoAdo->recuperaId("Produtos");
        return $fproProdId;
    }

    protected function exclui() {

        $this->scProdutoModelFornecedor = $this->preComprasView->recebeCestaComProduto();

        $precProdId = $this->scProdutoModelFornecedor->prodId;  //Este e o ID do produto selecionado
        //var_dump($precProdId);
        session_start();
        //$precProdId = $_SESSION['prodId'];
        $precCestId = $_SESSION['cestId'];

        $this->preComprasView->setCestId($precCestId);
        //Como obter o id da cesta criada anteriormente?? Será desta forma??

        $preComprasModel = new PreComprasModel($precProdId, $precCestId);

        $arrayDaQueryEDosValores = $this->preComprasAdo->montaDeleteDoObjeto($preComprasModel);

        $excluiu = $this->produtoAdo->executaPs($arrayDaQueryEDosValores[0], $arrayDaQueryEDosValores[1]);

        if ($excluiu) {
            $this->preComprasView->adicionaMensagem("Produto excluido da cesta de compras com sucesso!!");
        } else {
            $this->preComprasView->adicionaMensagem("Houve um erro ao excluir o produto da cesta de compras!!");
            return;
        }
    }
    
    protected function avanca() {
        
        header('Location: http://localhost/ModuloFornecedor/Modulos/realizacompras.php'); 
    }

}
