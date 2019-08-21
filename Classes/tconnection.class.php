<?php

/**

 * 
 * Gerencia as conexões com o BD por meio do arquivo de confiração.
 * 
 * Foi implementada para ser usada nas transações que envolvem diversos objetos 
 * ADO. Mas, pode ser utilizada sempre que necessário uma conexão à parte da 
 * ADO.
* 
 * Esta clase foi baseada no exemplo do livro PHP: Programando com Orientação a 
 * Objetos do Pablo Dall'Oglio (p. 202-203).
  * 
 
 */
final class TConnection {

    /**
     * Não devem existir instâncias de TConnection, por isso o construtor foi
     * marcado como private p/ previnir que algum desavisado tente instanciá-la.
     */
    private function __construct() {
        //vazio
    }

    public static function open(AtributosBdAbstract $atributosBD, $nomeDoArquivoIni = "/FabricaDeSoftware/fsw/Default/bd_mysql.ini") {
        //inicia variáveis locais da conexão
        $options  = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
        $confUTF8 = "charset=utf8";
        
        //recupera valores do BD
        $bdNome = $atributosBD->getBdNome();

        if (file_exists($nomeDoArquivoIni)) {
            $bd = parse_ini_file($nomeDoArquivoIni);
        } else {
            throw new Exception("O arquivo '{$nomeDoArquivoIni}' n&atilde;o foi encontrado!");
        }

        //recupera as informações do arquivo
        $host    = isset($bd['host']) ? $bd['host'] : null;
        $usuario = isset($bd['usuario']) ? $bd['usuario'] : null;
        $senha   = isset($bd['senha']) ? $bd['senha'] : null;
        $tipo    = isset($bd['tipo']) ? $bd['tipo'] : null;

        switch ($tipo) {
            case "mysql":
                $conexao = new PDO("mysql:host={$host};dbname={$bdNome};{$confUTF8}", $usuario, $senha, $options);

                break;

            default:
                throw new Exception("O tipo do banco n&atilde;o foi identificado!");

                break;
        }
        
        //determina lançamento de exceções na ocorrência de erros
        $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        return $conexao;
    }

}
