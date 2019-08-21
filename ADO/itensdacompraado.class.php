<?php

require_once 'adopdoabstract.class.php';

class ItensDaCompraAdo extends AdoPdoAbstract {
    
    function __construct() {
        parent::__construct();
        parent::setNomeDaTabela("ItensDaCompra");
    }
    
      public function montaInsereDoObjeto(ModelAbstract $itensDaCompraModel) {
        
        $query = "insert into {$this->getNomeDaTabela()} (itenCompId, itenProdId) values (?,?)";
        $arrayDeValores = array($itensDaCompraModel->getItenCompId(), $itensDaCompraModel->getItenProdId());
        return array($query, $arrayDeValores);
    }

    public function alteraObjeto(\ModelAbstract $objetoModel) {
        
    }

    public function excluiObjeto(\ModelAbstract $objetoModel) {
        
    }

    public function insereObjeto(\ModelAbstract $objetoModel) {
        
    }

}
