<?php

require_once 'adopdoabstract.class.php';

class CestasDeComprasAdo extends AdoPdoAbstract {
    
     function __construct() {
        parent::__construct();
        parent::setNomeDaTabela("CestasDeCompras");
    }
    
  
    public function montaInsereDoObjeto(ModelAbstract $cestasDeComprasModel){
        
        $query = "insert into {$this->getNomeDaTabela()} (cestId, cestClieId) values (?,?)";
        $arrayDeValores = array($cestasDeComprasModel->getCestId(), $cestasDeComprasModel->getCestClieId());
        return array($query, $arrayDeValores);
    }
    
    
    public function alteraObjeto(ModelAbstract $cestasDeComprasModel) {
        
    }

    public function excluiObjeto(ModelAbstract $cestasDeComprasModel) {
        
    }

    public function insereObjeto(ModelAbstract $cestasDeComprasModel) {
        
    }

}
