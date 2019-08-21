<?php
require_once 'modelabstract.class.php';
require_once '../Classes/validacoes.class.php';
require_once '../Classes/limpanumeros.class.php';
require_once '../Classes/estados.class.php';
use Classes\Validacoes;
use Classes\limpanumeros as LimpaNumeros;
use Classes\Estados;

class FornecedorModel extends ModelAbstract {

    private $fornCnpj;
    private $fornNome;
    private $fornFone1;
    private $fornFone2;
    private $fornEnd;
    private $fornCep;
    private $fornCidade;
    private $fornUf;

    function __construct($fornCnpj = null, $fornNome = null, $fornFone1 = null, $fornFone2 = null, $fornEnd = null, $fornCep = null, $fornCidade = null, $fornUf = null) {
        $this->fornCnpj = $fornCnpj;
        $this->fornNome = $fornNome;
        $this->fornFone1 = $fornFone1;
        $this->fornFone2 = $fornFone2;
        $this->fornEnd = $fornEnd;
        $this->fornCep = $fornCep;
        $this->fornCidade = $fornCidade;
        $this->fornUf = $fornUf;
    }

    public function checaAtributos() {

        $validaCnpj = new Validacoes();
        $dadosCorretos = true;
        $this->fornCnpj = LimpaNumeros::retiraNaoNumericos($this->fornCnpj);

        if ($validaCnpj->verificaOCnpj($this->fornCnpj)) {
            //continua
        } else {
            $this->adicionaMensagem("CNPJ Invalido!!!");
            $dadosCorretos = false;
        }

        if (is_null($this->fornNome) || trim($this->fornNome) == "") {
            $this->adicionaMensagem("Obs: Por favor informe o nome do fornecedor!");
            $dadosCorretos = false;
        } else {
            if (strlen($this->fornNome) > 45) {
                $this->adicionaMensagem("Obs: O nome do fornecedor deverá conter até 45 caracteres!!");
                $dadosCorretos = false;
            } else {
                //continua
            }
        }

        if (is_null($this->fornFone1) || trim($this->fornFone1) == "") {
            $this->adicionaMensagem("Obs: Por favor informe o telefone 1 do fornecedor!!");
            $dadosCorretos = false;
        } else {
            $this->fornFone1 = LimpaNumeros::retiraNaoNumericos($this->fornFone1);
            if (is_numeric($this->fornFone1)) {
                //continua...
            } else {
                $this->adicionaMensagem("Obs: Por favor informe somente números para o telefone 1 do fornecedor!");
                $dadosCorretos = false;
            }
        }

        if (is_null($this->fornFone2) || trim($this->fornFone2) == "") {
            //continua.. O telefone 2 podera ou nao ser informado!!
        } else {
            $this->fornFone2 = LimpaNumeros::retiraNaoNumericos($this->fornFone2);
            if (is_numeric($this->fornFone2)) {
                //continua...
            } else {
                $this->adicionaMensagem("Obs: Por favor informe somente números para o telefone 2 do fornecedor!");
                $dadosCorretos = false;
            }
        }

        if (is_null($this->fornEnd) || trim($this->fornEnd) == "") {
            $this->adicionaMensagem("Obs: Por favor, informe o endereço do fornecedor!!");
            $dadosCorretos = false;
        } else {
            if (strlen($this->fornEnd) > 100) {
                $this->adicionaMensagem("Obs: O endereço do fornecedor deverá ter até 100 caracteres!!");
                $dadosCorretos = false;
            } else {
                //continua
            }
        }

        if (is_null($this->fornCep) || trim($this->fornCep) == "") {
            $this->adicionaMensagem("Obs: Por favor, informe o CEP do fornecedor!!");
            $dadosCorretos = false;
        } else {
            if (strlen($this->fornCep) > 10) {
                $this->adicionaMensagem("Obs: O CEP do fornecedor deverá ter até 10 caracteres!!");
                $dadosCorretos = false;
            } else {
                $this->fornCep = LimpaNumeros::retiraNaoNumericos($this->fornCep);
                if (is_numeric($this->fornCep)) {
                    //continua
                } else {
                    $this->adicionaMensagem("Obs: O CEP do fornecedor deverá conter somente números");
                    $dadosCorretos = false;
                }
            }
        }

        if (is_null($this->fornCidade) || trim($this->fornCidade) == "") {
            $this->adicionaMensagem("Obs: Por favor, informe a cidade do fornecedor!!");
            $dadosCorretos = false;
        } else {
            if (strlen($this->fornCidade) > 30) {
                $this->adicionaMensagem("Obs: O nome da cidade do fornecedor deverá ter até 30 caracteres!!");
                $dadosCorretos = false;
            } else {
                //continua
            }
        }

        if (is_null($this->fornUf) || trim($this->fornUf) == "") {
            $this->adicionaMensagem("Obs: Por favor informe a UF do Fornecedor!");
            $dadosCorretos = false;
        } else {
            $this->fornUf = strtoupper($this->fornUf);
            if (strlen($this->fornUf) == 2) {
                if (Estados::unidadeDaFederacaoValida($this->fornUf)) {
                    //continua
                } else {
                    $this->adicionaMensagem("Uf incorreto!");
                    $dadosCorretos = false;
                }
            } else {
                $this->adicionaMensagem("Uf incorreto!");
                $dadosCorretos = false;
            }
        }

        return $dadosCorretos;
    }

    function getFornCnpj() {
        return $this->fornCnpj;
    }

    function getFornNome() {
        return $this->fornNome;
    }

    function getFornFone1() {
        return $this->fornFone1;
    }

    function getFornFone2() {
        return $this->fornFone2;
    }

    function getFornEnd() {
        return $this->fornEnd;
    }

    function getFornCep() {
        return $this->fornCep;
    }

    function getFornCidade() {
        return $this->fornCidade;
    }

    function getFornUf() {
        return $this->fornUf;
    }

    function setFornCnpj($fornCnpj) {
        $this->fornCnpj = $fornCnpj;
    }

    function setFornNome($fornNome) {
        $this->fornNome = $fornNome;
    }

    function setFornFone1($fornFone1) {
        $this->fornFone1 = $fornFone1;
    }

    function setFornFone2($fornFone2) {
        $this->fornFone2 = $fornFone2;
    }

    function setFornEnd($fornEnd) {
        $this->fornEnd = $fornEnd;
    }

    function setFornCep($fornCep) {
        $this->fornCep = $fornCep;
    }

    function setFornCidade($fornCidade) {
        $this->fornCidade = $fornCidade;
    }

    function setFornUf($fornUf) {
        $this->fornUf = $fornUf;
    }

}
