<?php

require_once 'modelabstract.class.php';

class ProdutoModel extends ModelAbstract {

    private $prodId;
    private $prodNome;
    private $prodValor;
    private $prodQtde;

    function __construct($prodId = null, $prodNome = null, $prodValor = null, $prodQtde = null) {
        $this->prodId = $prodId;
        $this->prodNome = $prodNome;
        $this->prodValor = $prodValor;
        $this->prodQtde = $prodQtde;
    }

    public function checaAtributos() {
        $dadosCorretos = true;

        if (is_null($this->prodNome) || trim($this->prodNome) == "") {
            $this->adicionaMensagem("Obs: Por favor informe o nome do produto!");
            $dadosCorretos = false;
        } else {
            if (strlen($this->prodNome) > 70) {
                $this->adicionaMensagem("Obs: O nome do produto deve conter no máximo 70 caracteres!");
                $dadosCorretos = false;
            }
        }

        if (is_null($this->prodValor) || trim($this->prodValor) == "") {
            $this->adicionaMensagem("Obs: Por favor informe o valor do produto!");
            $dadosCorretos = false;
        } else {
            if (is_numeric($this->prodValor)) {
                //continua...
            } else {
                $this->adicionaMensagem("Obs: Por favor informe somente números para o valor do produto!");
                $dadosCorretos = false;
            }
        }

        if (is_null($this->prodQtde) || trim($this->prodQtde) == "") {
            $this->adicionaMensagem("Obs: Por favor informe a quantidade do produto!");
            $dadosCorretos = false;
        } else {
            if (is_numeric($this->prodQtde)) {
                //continua...
            } else {
                $this->adicionaMensagem("Obs: Por favor informe somente números para a quantidade do produto!");
                $dadosCorretos = false;
            }
        }

        return $dadosCorretos;
    }

    function getProdId() {
        return $this->prodId;
    }

    function getProdNome() {
        return $this->prodNome;
    }

    function getProdValor() {
        return $this->prodValor;
    }

    function getProdQtde() {
        return $this->prodQtde;
    }

    function setProdId($prodId) {
        $this->prodId = $prodId;
    }

    function setProdNome($prodNome) {
        $this->prodNome = $prodNome;
    }

    function setProdValor($prodValor) {
        $this->prodValor = $prodValor;
    }

    function setProdQtde($prodQtde) {
        $this->prodQtde = $prodQtde;
    }

}
