<?php

require_once 'modelabstract.class.php';

class CestasdeComprasModel extends ModelAbstract {
    
    private $cestId;
    private $cestClieId;
    
    function __construct($cestId = null, $cestClieId=null) {
        
        $this->cestId = $cestId;
        $this->cestClieId = $cestClieId;
    }
    
    public function checaAtributos() {
        
        $dadosCorretos=true;
        
         //valida se o id da cesta foi gerado corretamente
         if (is_null($this->cestId) || trim($this->cestId) == "") {
            $this->adicionaMensagem("Houve um problema ao criar sua cesta de compras!!");
            $dadosCorretos = false;
        } else {
            if (is_numeric($this->cestId)) {
                //continua
            } else {
                $this->adicionaMensagem("Houve um problema ao criar sua cesta de compras!!");
                $dadosCorretos = false;
            }
        }
        
          if (is_null($this->cestClieId) || trim($this->cestClieId) == "") {
            $this->adicionaMensagem("Houve um erro relacionado a idenfificação do cliente!!! Contacte o suporte!!");
            $dadosCorretos = false;
        } else {
            if (is_numeric($this->cestClieId)) {
                //continua
            } else {
                $this->adicionaMensagem("Houve um erro relacionado a idenfificação do cliente!!! Contacte o suporte!!");
                $dadosCorretos = false;
            }
        }

        return $dadosCorretos;
    }
    
    function getCestId() {
        return $this->cestId;
    }

    function getCestClieId() {
        return $this->cestClieId;
    }

    function setCestId($cestId) {
        $this->cestId = $cestId;
    }

    function setCestClieId($cestClieId) {
        $this->cestClieId = $cestClieId;
    }


    
}
