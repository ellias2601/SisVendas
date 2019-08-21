<?php

require_once 'adopdoabstract.class.php';

class ClienteAdo extends AdoPdoAbstract {

    public function __construct() {
        parent::__construct();
        parent::setNomeDaTabela("Clientes");
    }

    public function insereObjeto(\ModelAbstract $clienteModel) {
        $query = "insert into {$this->getNomeDaTabela()} (clieId, clieNome, clieCpf, clieRg, clieUfRg, clieRgDtExpedicao, clieFone, clieEmail) values (?, ?, ?, ?, ?, ?, ?, ?) ";
        $arrayDeValores = array(null, $clienteModel->getClieNome(), $clienteModel->getClieCpf(), $clienteModel->getClieRg(), $clienteModel->getClieUfRg(), $clienteModel->getClieRgDtExpedicao(), $clienteModel->getClieFone(), $clienteModel->getClieEmail());
        return $this->executaPs($query, $arrayDeValores);
    }

    public function alteraObjeto(\ModelAbstract $clienteModel) {
        $query = "UPDATE {$this->getNomeDaTabela()} SET clieNome = ?, clieCpf = ?, clieRg = ?, clieUfRg = ?, clieRgDtExpedicao = ?, clieFone = ?, clieEmail = ? WHERE {$this->getNomeDaTabela()}.clieId = ? ";

        $arrayDeValores = array($clienteModel->getClieNome(), $clienteModel->getClieCpf(), $clienteModel->getClieRg(), $clienteModel->getClieUfRg(), $clienteModel->getClieRgDtExpedicao(), $clienteModel->getClieFone(), $clienteModel->getClieEmail(), $clienteModel->getClieId());

        return $this->executaPs($query, $arrayDeValores);
    }

    public function excluiObjeto(\ModelAbstract $clienteModel) {
        $query = "DELETE FROM {$this->getNomeDaTabela()} WHERE {$this->getNomeDaTabela()}.clieId = ? ";

        $arrayDeValores = array($clienteModel->getClieId());

        return $this->executaPs($query, $arrayDeValores);
    }

    public function buscaCliente($clieId) {
        
        $query = "SELECT *, DATE_FORMAT(clieRgDtExpedicao,'%d/%m/%Y') as 'dataFormatada' FROM {$this->getNomeDaTabela()} where clieId = ? ORDER BY clieRgDtExpedicao DESC";

        $executou = parent::executaPs($query, array($clieId));
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
        //montar clienteModel
        return new ClienteModel($objetoBD['clieId'], $objetoBD['clieNome'], $objetoBD['clieCpf'], $objetoBD['clieRg'], $objetoBD['clieUfRg'], $objetoBD['clieRgDtExpedicao'], $objetoBD['clieFone'], $objetoBD['clieEmail']);
    }
    
    public function buscaTodosOsClientes() {
        return parent::buscaArrayObjetoComPs(array(), 1, "order by clieNome");
    }

}
