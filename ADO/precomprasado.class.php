<?php

require_once 'adopdoabstract.class.php';

class PreComprasAdo extends AdoPdoAbstract {
    
    function __construct() {
        parent::__construct();
        parent::setNomeDaTabela("PreCompras");
    }
    
    public function montaInsereDoObjeto(ModelAbstract $preComprasModel) {
        
        $query = "insert into {$this->getNomeDaTabela()} (precProdId, precCestId) values (?,?)";
        $arrayDeValores = array($preComprasModel->getPrecProdId(), $preComprasModel->getPrecCestId());
        return array($query, $arrayDeValores);
    }
    
    public function montaDeleteDoObjeto(ModelAbstract $preComprasModel) {
        
        $query = "DELETE FROM {$this->getNomeDaTabela()} WHERE {$this->getNomeDaTabela()}.precProdId = ? and {$this->getNomeDaTabela()}.precCestId = ?";

        $arrayDeValores = array($preComprasModel->getPrecProdId(), $preComprasModel->getPrecCestId());

        return array($query, $arrayDeValores);
        
    }

    public function alteraObjeto(ModelAbstract $preComprasModel) {
        
    }

    public function excluiObjeto(ModelAbstract $preComprasModel) {
        
    }

    public function insereObjeto(ModelAbstract $preComprasModel) {
        
    }

}
