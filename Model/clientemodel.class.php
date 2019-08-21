<?php

require_once 'modelabstract.class.php';
require_once '../Classes/validacoes.class.php';
require_once '../Classes/limpanumeros.class.php';
require_once '../Classes/estados.class.php';

use Classes\Validacoes;
use Classes\limpanumeros as LimpaNumeros;
use Classes\Estados;

class ClienteModel extends ModelAbstract {

    function __construct($clieId = null, $clieNome = null, $clieCpf = null, $clieRg = null, $clieUfRg = null, $clieRgDtExpedicao = null, $clieFone = null, $clieEmail = null) {
        $this->clieId = $clieId;
        $this->clieNome = $clieNome;
        $this->clieCpf = $clieCpf;
        $this->clieRg = $clieRg;
        $this->clieUfRg = $clieUfRg;
        $this->clieRgDtExpedicao = $clieRgDtExpedicao;
        $this->clieFone = $clieFone;
        $this->clieEmail = $clieEmail;
    }

    public function checaAtributos() {
        //is_null == verifica se uma determinada variavel é nulla(se o usuario não digitou nada)
        //trim == Retira espaço no ínicio e final de uma string (não retira espaços do meio da string)
        //is_numeric == Informa se a variável é um número ou uma string numérica 
        //strlen == Retorna o tamanho de uma String
        $dadosCorretos = true;

        //Valida Nome Cliente
        if (is_null($this->clieNome) || trim($this->clieNome) == "") {
            $this->adicionaMensagem("Por favor informe o nome do cliente!");
            $dadosCorretos = false;
        } else {
            if (strlen($this->clieNome) > 45) {
                $this->adicionaMensagem("O nome do cliente deve conter no máximo 40 caracteres!");
                $dadosCorretos = false;
            }
        }

        //valida CPF do cliente
        $validaCpf = new Validacoes();
        $this->clieCpf = LimpaNumeros::retiraNaoNumericos($this->clieCpf);

        if ($validaCpf->verificaOCpf($this->clieCpf)) {

            //continua
        } else {
            $this->adicionaMensagem("CPF Inválido!!");
            $dadosCorretos = false;
        }

        //valida RG do cliente
        if (is_null($this->clieRg) || trim($this->clieRg) == "") {
            $this->adicionaMensagem("Por favor, informe o RG do cliente!!");
            $dadosCorretos = false;
        } else {
            if (is_numeric($this->clieRg)) {
                //continua
            } else {
                $this->adicionaMensagem("Por favor, informe somente números para o RG do cliente!! ");
                $dadosCorretos = false;
            }
        }

        //valida UfRg do cliente
        if (is_null($this->clieUfRg) || trim($this->clieUfRg) == "") {
            $this->adicionaMensagem("Por favor, informe a UF presente no RG do cliente!!");
            $dadosCorretos = false;
        } else {
            $this->clieUfRg = strtoupper($this->clieUfRg);
            if (strlen($this->clieUfRg) == 2) {
                if (Estados::unidadeDaFederacaoValida($this->clieUfRg)) {
                    //continua
                } else {
                    $this->adicionaMensagem("UF Incorreto!!");
                    $dadosCorretos = false;
                }
            } else {
                $this->adicionaMensagem("UF Incorreto!!");
                $dadosCorretos = false;
            }
        }

        //valida clieRgDtExpedicao
        if (is_null($this->clieRgDtExpedicao) || trim($this->clieRgDtExpedicao) == "") {
            $this->adicionaMensagem("Por favor, informe a data de expedição presente no RG do cliente");
            $dadosCorretos = false;
        } else {

            $array = explode('-', $this->clieRgDtExpedicao);

            //garante que o array possui tres elementos (dia, mes e ano)
            if (count($array) == 3) {
                $dia = $array[2];
                $mes = $array[1];
                $ano = $array[0];

                //testa se a data é válida
                if (checkdate($mes, $dia, $ano)) {
                    
                    //continua
                } else {
                    $dadosCorretos = false;
                    $this->adicionaMensagem("A data informada é invalida");
                }
            } else {
                $dadosCorretos = false;
                $this->adicionaMensagem("A data informada é invalida");
            }
        }

        if (is_null($this->clieFone) || trim($this->clieFone) == "") {
            $this->adicionaMensagem("Obs: Por favor informe o telefone do cliente");
            $dadosCorretos = false;
        } else {
            $this->clieFone = LimpaNumeros::retiraNaoNumericos($this->clieFone);
            if (is_numeric($this->clieFone)) {

                if (strlen($this->clieFone) > 15) {
                    $this->adicionaMensagem("O telefone do cliente deverá conter até 15 digitos!!");
                    $dadosCorretos = false;
                } else {
                    //continua
                }
            } else {
                $this->adicionaMensagem("Obs: Por favor informe somente números para o telefone 1 do fornecedor!");
                $dadosCorretos = false;
            }
        }

        //valida email do cliente

        if (is_null($this->clieEmail) || trim($this->clieEmail) == "") {
            $this->adicionaMensagem("Obs: Por favor informe o Email do cliente!");
            $dadosCorretos = false;
        } else {
            if (strlen($this->clieEmail) > 60) {
                $this->adicionaMensagem("O email do cliente deverá conter até 60 caracteres!!");
                $dadosCorretos = false;
            } else {
                //continua
            }
        }

        return $dadosCorretos;
    }

    function getClieId() {
        return $this->clieId;
    }

    function getClieNome() {
        return $this->clieNome;
    }

    function setClieId($clieId) {
        $this->clieId = $clieId;
    }

    function setClieNome($clieNome) {
        $this->clieNome = $clieNome;
    }

    function getClieCpf() {
        return $this->clieCpf;
    }

    function getClieRg() {
        return $this->clieRg;
    }

    function getClieUfRg() {
        return $this->clieUfRg;
    }

    function getClieRgDtExpedicao() {
        return $this->clieRgDtExpedicao;
    }

    function getClieFone() {
        return $this->clieFone;
    }

    function getClieEmail() {
        return $this->clieEmail;
    }

    function setClieCpf($clieCpf) {
        $this->clieCpf = $clieCpf;
    }

    function setClieRg($clieRg) {
        $this->clieRg = $clieRg;
    }

    function setClieUfRg($clieUfRg) {
        $this->clieUfRg = $clieUfRg;
    }

    function setClieRgDtExpedicao($clieRgDtExpedicao) {
        $this->clieRgDtExpedicao = $clieRgDtExpedicao;
    }

    function setClieFone($clieFone) {
        $this->clieFone = $clieFone;
    }

    function setClieEmail($clieEmail) {
        $this->clieEmail = $clieEmail;
    }

}
