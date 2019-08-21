<?php

require_once 'adopdoabstract.class.php';

class AssociaFornecedorProdutoAdo extends AdoPdoAbstract {

    function __construct() {
        parent::__construct();
        parent::setNomeDaTabela("FornecedoresDeProdutos");
    }

    public function alteraObjeto(\ModelAbstract $fornecedorModel) {
        // $query = "UPDATE {$this->getNomeDaTabela()} SET fornNome = ?, fornFone1 = ?, fornFone2 = ?, fornEnd = ?, fornCep=?, fornCidade = ?, fornUf = ? WHERE {$this->getNomeDaTabela()}.fornCnpj = ? ";

        // $arrayDeValores = array($fornecedorModel->getFornNome(), $fornecedorModel->getFornFone1(), $fornecedorModel->getFornFone2(), $fornecedorModel->getFornEnd(), $fornecedorModel->getFornCep(), $fornecedorModel->getFornCidade(), $fornecedorModel->getFornUf(), $fornecedorModel->getFornCnpj());

        // return $this->executaPs($query, $arrayDeValores);
    }

    public function excluiObjeto(\ModelAbstract $associaFornecedorProdutoModel) {
        //$query = "DELETE FROM {$this->getNomeDaTabela()} WHERE {$this->getNomeDaTabela()}.fornCnpj = ? ";

        //$arrayDeValores = array($associaFornecedorProdutoModel->getFornCnpj());

        //return $this->executaPs($query, $arrayDeValores);
    }

    public function insereObjeto(\ModelAbstract $associaFornecedorProdutoModel) {
        $query = "insert into {$this->getNomeDaTabela()} (fproFornCnpj, fproProdId) values (?,?)";
        $arrayDeValores = array($associaFornecedorProdutoModel->getfProFornCnpj(), $associaFornecedorProdutoModel->getfProProdId());
        return $this->executaPs($query, $arrayDeValores);
    }
    
    public function montaInsereDoObjeto(ModelAbstract $associaFornecedorProdutoModel){
        
        $query = "insert into {$this->getNomeDaTabela()} (fproFornCnpj, fproProdId) values (?,?)";
        $arrayDeValores = array($associaFornecedorProdutoModel->getfProFornCnpj(), $associaFornecedorProdutoModel->getfProProdId());
        return array($query, $arrayDeValores);
    }

    public function buscaFornecedor($fornCnpj) {
       /* $query = "SELECT * FROM {$this->getNomeDaTabela()} where fornCnpj = ?";

        $executou = parent::executaPs($query, array($fornCnpj));
        if ($executou) {
            if (parent::qtdeLinhas() === 0) {
                return 0;
            }
        } else {
            return false;
        }
        
        $leu = $objetoBD = $this->leTabelaBD();
        if ($leu) {
            //continua...
        } else {
            return FALSE;
        }
        
        //Monta um novo fornecedor
        return new FornecedorModel($objetoBD['fornCnpj'],$objetoBD['fornNome'],$objetoBD['fornFone1'],$objetoBD['fornFone2'], $objetoBD['fornEnd'], $objetoBD['fornCep'], $objetoBD['fornCidade'], $objetoBD['fornUf']);
        
        */
    }
    
    public function buscaTodosOsFornecedores() {
        //return parent::buscaArrayObjetoComPs(array(), 1, "order by fornNome");
    }
 
   
}
