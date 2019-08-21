<?php

require_once 'adopdoabstract.class.php';

class ComprasAdo extends AdoPdoAbstract {
    
    function __construct() {
        parent::__construct();
        parent::setNomeDaTabela("Compras");
    }
    
    public function montaInsereDoObjeto(ModelAbstract $comprasModel) {
        
        $query = "insert into {$this->getNomeDaTabela()} (compId, compClieId) values (?,?)";
        $arrayDeValores = array($comprasModel->getCompId(), $comprasModel->getCompClieId());
        return array($query, $arrayDeValores);
    }

    public function alteraObjeto(\ModelAbstract $objetoModel) {
        
    }

    public function excluiObjeto(\ModelAbstract $objetoModel) {
        
    }

    public function insereObjeto(\ModelAbstract $objetoModel) {
        
    }

}
