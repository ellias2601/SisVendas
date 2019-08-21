<?php

require_once '../ADO/adopdoabstract.class.php';

class LoginAdo extends AdoPdoAbstract {

    public function buscaIdCliente($clieCpf) {
       // $query = "SELECT clieId FROM {$this->getNomeDaTabela()} WHERE loginCpf = ?";
        $query = "SELECT clieId, clieCpf FROM Clientes WHERE clieCpf = ?";
        //SELECT `clieId` FROM `Clientes` WHERE `clieCpf` = 5509803185
        
        $executou = parent::executaPs($query, array($clieCpf));
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
        //montar loginModel
        
        session_start();
        $_SESSION['clieId'] = $objetoBD['clieId'];
        $_SESSION['clieCpf'] = $objetoBD['clieCpf'];
        
        return new LoginModel($objetoBD['clieId'], $objetoBD['clieCpf']);
       
        
    }

    public function alteraObjeto(ModelAbstract $objetoModel) {
        
    }

    public function excluiObjeto(ModelAbstract $objetoModel) {
        
    }

    public function insereObjeto(ModelAbstract $objetoModel) {
        
    }

}
