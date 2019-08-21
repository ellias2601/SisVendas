<?php


require_once 'adopdoabstract.class.php';

class FornecedorAdo extends AdoPdoAbstract {

    function __construct() {
        parent::__construct();
        parent::setNomeDaTabela("Fornecedores");
    }

    public function alteraObjeto(\ModelAbstract $fornecedorModel) {
        $query = "UPDATE {$this->getNomeDaTabela()} SET fornNome = ?, fornFone1 = ?, fornFone2 = ?, fornEnd = ?, fornCep=?, fornCidade = ?, fornUf = ? WHERE {$this->getNomeDaTabela()}.fornCnpj = ? ";

        $arrayDeValores = array($fornecedorModel->getFornNome(), $fornecedorModel->getFornFone1(), $fornecedorModel->getFornFone2(), $fornecedorModel->getFornEnd(), $fornecedorModel->getFornCep(), $fornecedorModel->getFornCidade(), $fornecedorModel->getFornUf(), $fornecedorModel->getFornCnpj());

        return $this->executaPs($query, $arrayDeValores);
    }

    public function excluiObjeto(\ModelAbstract $fornecedorModel) {
        $query = "DELETE FROM {$this->getNomeDaTabela()} WHERE {$this->getNomeDaTabela()}.fornCnpj = ? ";

        $arrayDeValores = array($fornecedorModel->getFornCnpj());

        return $this->executaPs($query, $arrayDeValores);
    }

    public function insereObjeto(\ModelAbstract $fornecedorModel) {
        $query = "insert into {$this->getNomeDaTabela()} (fornCnpj, fornNome, fornFone1, fornFone2, fornEnd, fornCep, fornCidade, fornUf) values (?,?,?,?,?,?,?,?)";
        $arrayDeValores = array($fornecedorModel->getFornCnpj(), $fornecedorModel->getFornNome(), $fornecedorModel->getFornFone1(), $fornecedorModel->getFornFone2(), $fornecedorModel->getFornEnd(), $fornecedorModel->getFornCep(), $fornecedorModel->getFornCidade(), $fornecedorModel->getFornUf());
        return $this->executaPs($query, $arrayDeValores);
    }

    public function buscaFornecedor($fornCnpj) {
        $query = "SELECT * FROM {$this->getNomeDaTabela()} where fornCnpj = ?";

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
    }
    
    public function buscaTodosOsFornecedores() {
        return parent::buscaArrayObjetoComPs(array(), 1, "order by fornNome");
    }
    
    /* Este método tem como objetivo verificar se existe algum produto cadastrado referente ao fornecedor
     * informado através do cnpj no banco de dados. Se existe, retorna TRUE, se não, retorna FALSE.
     */
    public function checaFornecedorLigadoAProduto($fornCnpj) {

        $query = "SELECT fornNome FROM {$this->getNomeDaTabela()} INNER JOIN FornecedoresDeProdutos ON fornCnpj = fProFornCnpj WHERE fornCnpj = ?";
       

        $executou = parent::executaPs($query, array($fornCnpj));

        if ($executou) {
            if (parent::qtdeLinhas() === 0) {
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            return false;
        }
    }

}

