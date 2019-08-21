<?php

require_once 'modelabstract.class.php';

class ComprasModel extends ModelAbstract {
    
    private $compId;
    private $compClieId;
    
    function __construct($compId = null, $compClieId=null) {
        
        $this->compId = $compId;
        $this->compClieId = $compClieId;
    }
    
    
    function getCompId() {
        return $this->compId;
    }

    function getCompClieId() {
        return $this->compClieId;
    }

    function setCompId($compId) {
        $this->compId = $compId;
    }

    function setCompClieId($compClieId) {
        $this->compClieId = $compClieId;
    }
    
      public function checaAtributos() {
        
    }


}
