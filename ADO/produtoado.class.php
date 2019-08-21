<?php

require_once 'adopdoabstract.class.php';

class ProdutoAdo extends AdoPdoAbstract {

    function __construct() {
        parent::__construct();
        parent::setNomeDaTabela("Produtos");
    }

    public function alteraObjeto(\ModelAbstract $produtoModel) {
        $query = "UPDATE {$this->getNomeDaTabela()} SET prodNome = ?, prodValor = ?, prodQtde = ? WHERE {$this->getNomeDaTabela()}.prodId = ? ";

        $arrayDeValores = array($produtoModel->getProdNome(), $produtoModel->getProdValor(), $produtoModel->getProdQtde(), $produtoModel->getProdId());

        return $this->executaPs($query, $arrayDeValores);
    }

    public function excluiObjeto(\ModelAbstract $produtoModel) {
        $query = "DELETE FROM {$this->getNomeDaTabela()} WHERE {$this->getNomeDaTabela()}.prodId = ? ";

        $arrayDeValores = array($produtoModel->getProdId());

        return $this->executaPs($query, $arrayDeValores);
    }

    public function insereObjeto(\ModelAbstract $produtoModel) {
        $query = "insert into {$this->getNomeDaTabela()} (prodId, prodNome,prodValor, prodQtde) values (?,?,?,?)";
        $arrayDeValores = array(null, $produtoModel->getProdNome(), $produtoModel->getProdValor(), $produtoModel->getProdQtde());
        return $this->executaPs($query, $arrayDeValores);
    }

    public function buscaProduto($prodId) {
        $query = "SELECT * FROM {$this->getNomeDaTabela()} where prodId = ?";

        $executou = parent::executaPs($query, array($prodId));
        if ($executou) {
            if (parent::qtdeLinhas() === 0) {
                return 0;
            }
        } else {
            return false;
        }

        $leu = $objetoBD = $this->leTabelaBD();
        //var_dump($leu);
        if ($leu) {
            //continua...
        } else {
            return FALSE;
        }

        //Monta um produto novo
        return new ProdutoModel($objetoBD['prodId'], $objetoBD['prodNome'], $objetoBD['prodValor'], $objetoBD['prodQtde']);
    }
    
    public function buscaIdProdutosNaCesta($precCestId) {
        $query = "SELECT precProdId FROM PreCompras where precCestId = ?";

        $executou = parent::executaPs($query, array($precCestId));
        if ($executou) {
            if (parent::qtdeLinhas() === 0) {
                return 0;
            }
        } else {
            return false;
        }
        
         $arrayProdutosDaCesta = array();
        while ($objetoBD = $this->leTabelaBD()){
            //Monta um produto novo
            $arrayProdutosDaCesta [] = $objetoBD['precProdId'];
        }
        
        return $arrayProdutosDaCesta;

        
    }

    public function buscaTodosOsProdutos() {
        return parent::buscaArrayObjetoComPs(array(), 1, "order by prodNome");
    }
    
    public function buscaTodosOsProdutosDaCesta($cestId) {
        
        //devo passar o id da cesta tambem???
        $query = "SELECT * FROM {$this->getNomeDaTabela()} INNER JOIN PreCompras ON prodId = precProdId AND precCestID = ?";

        $executou = parent::executaPs($query, array($cestId));
        if ($executou) {
            if (parent::qtdeLinhas() === 0) {
                return 0;
            }
        } else {
            return false;
        }

        $arrayProdutosDaCesta = array();
        while ($objetoBD = $this->leTabelaBD()){
            //Monta um produto novo
            $arrayProdutosDaCesta [] = new ProdutoModel($objetoBD['prodId'], $objetoBD['prodNome'], $objetoBD['prodValor'], $objetoBD['prodQtde']);
        }
        
        return $arrayProdutosDaCesta;
    }

    public function checaProdutoRelacionadoFornecedor($prodNome = null) {

        $query = "SELECT prodNome, fornNome FROM {$this->getNomeDaTabela()} INNER JOIN FornecedoresDeProdutos ON prodId = fProProdId INNER JOIN Fornecedores ON fProFornCnpj = fornCnpj WHERE prodNome= ?";


        $executou = parent::executaPs($query, array($prodNome));

        if ($executou) {
            if (parent::qtdeLinhas() === 0) {
                return 0;
            }
        }
        while ($objetoBD = $this->leTabelaBD(2)) {
            $leu = $objetoBD;
            if ($leu) {
                //continua
            } else {
                return FALSE;
            }
            
            return $objetoBD;
        }

        //Monta um produto novo
        // return new ProdutoModel($objetoBD['prodNome'], $objetoBD['fornNome'], $objetoBD['prodValor'], $objetoBD['prodQtde']);
    }

}
