<?php


/**

 * Descrição de BancoDeDadosPdo:
 * Esta classe cuida da camada de persistência do banco de dados e será extendida 
 * diretamente pela classe AdoPdoAbstract02 ou instanciada pela AdoPdoAbstract. 
 * 
 * Todos os métodos a serem execudados diretamente pela classe PDO devem ser 
 * implementados nesta.
 * 
 * Esta classe extende a classe PDO.
 * 
 */
class BancoDeDadosPdo extends PDO {

    private $host = NULL;
    private $usuario = NULL;
    private $senha = NULL;
    private $bdNome = NULL;
    private $mensagem = NULL;
    private $confUTF8 = "charset=utf8";
    
    private $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
    //    private $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    //                             PDO::ATTR_PERSISTENT => true);
    private $statusDoConstrutor = TRUE;
    private $pdoStatment = NULL;
    //a conexão e os atributosBd serão utilizados nas transações envolvento mais de um objeto.
    private $conexaoParaTransacoesMultiobjetos = NULL;
    private $atributosBd = null;

    /**
     * Este é o método construtor da classe BancoDeDadosPdo. Nele é feita a conexão com o 
     * banco de dados usando os dados da classe AtributosBd que deve ser recebida via parâmetro.
     * @param type $atributosBd Classe com os dados para conexão e seleção do banco de dados.
     * @return type
     */
    function __construct() {
        //atributosBd será utilizado quando se precisar de uma transação 
        //envolvendo mais de um objeto.
        $this->usuario = "comercial";
        $this->senha = 'comercial';
        try {
            parent::__construct("mysql:host=localhost;dbname=Comercial;{$this->confUTF8}", $this->usuario, $this->senha, $this->options);
            $this->configuraUTF8();
        } catch (PDOException $e) {
            $this->geraLogDeErro("Conexão com o Banco de Dados. mysql:host={$this->host};dbname={$this->bdNome};{$this->confUTF8}", $e->getMessage());
            die("N&atildeo foi poss&iacute;vel conectar ao SGBD. Contate o analista respons&aacute;vel");
        }
    }

    /**
     * Este é o método que vai destruir o construtor, vai encerrar a conexão.
     */
    function __destruct() {
        $this->conexaoParaTransacoesMultiobjetos = NULL;
    }

    function setMensagem($mensagem) {
        $this->mensagem = $mensagem;
    }

    function getMensagem() {
        return $this->mensagem;
    }

    /**
     * Este método retornará erros do SGBD.
     * 
     * @param inteiro $tipo Identifica se o erro foi de uma execução num objeto 
     *                      do tipo statment (0) ou diretamente no BD (1).
     * @return String Mensagem do erro
     */
    function getBdError($tipo = 0) {
        $erro = null;
        if ($tipo === 0) {
            $erro = $this->pdoStatment->errorInfo();
        } else {
            $erro = parent::errorInfo();
        }

        return $erro[2];
    }

    /**
     * Este método mostrará os status do construtor.
     * @return String
     */
    public function getStatusDoConstrutor() {
        return $this->statusDoConstrutor;
    }

    /**
     * Este método será montado o status do construtor
     * @param type $statusDoConstrutor
     */
    public function setStatusDoConstrutor($statusDoConstrutor) {
        $this->statusDoConstrutor = $statusDoConstrutor;
    }

    /**
     * Método para execução da query via PDO Prepared Statement
     * passando os valores por parametros em array, separados da query
     * @param String $query Instrução SQL parametrizada com ?.
     * @param array $arrayDeValores Valores a serem substituídos nos ? da instrução.
     * @return boolean true ou false dependendo do resultado de execute()
     */
    function executaPs($query, $arrayDeValores) {
        //Preparação
        try {
            $preparou = parent::prepare($query);
            if ($preparou) {
                $this->pdoStatment = $preparou;
            } else {
                $this->geraLogDeErro($query, "PREPARE : " . parent::errorInfo());
                return false;
            }
        } catch (Exception $e) {
            $this->geraLogDeErro($query, $e->getMessage());
            return false;
        }

        try {
            $executou = $this->pdoStatment->execute(array_values($arrayDeValores));
            if ($executou) {
                $this->geraLogDeExecucao($query, 'executaPs');
                return true;
            } else {
                $this->geraLogDeErro($query, $this->getBdError());
                return false;
            }
        } catch (Exception $e) {
            $this->geraLogDeErro($query, "EXECUTE : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Este método será retornado o número de linhas afetadas em uma consulta sql.
     * OBS: Segundo o php.net o comportamento do rowCount de retornar o número de
     *      linhas, não será garantido para todos bancos de dados.
     * @param type $resultado
     * @return rowCount
     */
    function qtdeLinhas() {
        return $this->pdoStatment->rowCount();
    }

    /**
     * Este método irá retorna a quantidade de linhas afetadas por Updates, Deletes...
     * @return rowCount
     */
    function linhasAfetadas() {
        return $this->pdoStatment->rowCount();
    }

    public function getAtributoArquivo($linha, $nomeDoAtributo) {
        return $linha[$nomeDoAtributo];
    }

    /**
     * Este método lê o resultado de um select. Retorna uma tupla no formato de
     * array indexado pelo nome da coluna ou um objeto stdClas, de acoro com o 
     * parâmetro de entrada (2 ou 5 respectivamente).
     * @param type $estilo 2 == FETCH_ASSOC, 5 == FETCH_OBJ;
     * @return type
     */
    function leTabelaBD($estilo = 2) {
        return $this->pdoStatment->fetch($estilo);
    }

    /**
     * Recebe um array com a tupla lida de uma tabela usando o fetch e monta uma
     * instância da stdClass() com os atributo sendo os nomes das posições do 
     * array, sem os sublinhados, e os valores o conteúdos das mesmas.
     * 
     * @param array $arrayDoFetch Array de retorno de um fetch numa tabela.
     * @return \stdClass Objeto do tipo stdClass.
     */
    public function montaStdClassDoArrayDoFetch(Array $arrayDoFetch) {
        $objetoStd = new stdClass();

        foreach ($arrayDoFetch as $nomeDaColuna => $valorDaColuna) {
            $nomeDaColunaStd = Strings::trocaNomeColunaParaAtributo($nomeDaColuna);

            $objetoStd->$nomeDaColunaStd = $valorDaColuna;
        }

        return $objetoStd;
    }

    /**
     * Este método é responsável por gerar arquivo de log quando houver erro 
     * de SQL ao executar uma query no banco de dados
     
     */
    function geraLogDeErro($query, $mensagemDeErro) {
        global $diretorios;

        $conteudo_file = '===================================================================================' . PHP_EOL;
        $conteudo_file .= 'Hora: ' . date("H:i:s") . ' | Script: ' . $_SERVER['SCRIPT_NAME'] . PHP_EOL;
        $conteudo_file .= "Query executada: " . $query . ": " . $mensagemDeErro . PHP_EOL . PHP_EOL;

        $diretoriosDeLogs = $_SERVER['DOCUMENT_ROOT'] . "/ModuloFornecedor/Logs";
        

        // Se o diretório não existir, cria-o
        if (!is_dir($diretoriosDeLogs)) {
            mkdir($diretoriosDeLogs);
        }

        $fopen = fopen($diretoriosDeLogs . "/erros_" . date("Ymd") . ".log", "a");
        fwrite($fopen, $conteudo_file);
        fclose($fopen);
    }

    /**
     * Este método é responsável por gerar arquivo de log quando houver insert, delete ou update 
     * de SQL ao executar uma query no banco de dados
     */
    function geraLogDeExecucao($query, $metodoExecutado) {
        global $diretorios;
        $tipo_sql = explode(" ", trim($query));
        if (strtolower($tipo_sql[0]) != 'select') {
            $conteudo_file = '===================================================================================' . PHP_EOL;
            $conteudo_file .= 'Hora: ' . date("H:i:s") . ' | Script: ' . $_SERVER['SCRIPT_NAME'] . PHP_EOL;
            $conteudo_file .= "Query executada: " . $query . " | Método: " . $metodoExecutado . PHP_EOL . PHP_EOL;

            $diretoriosDeLogs = $_SERVER['DOCUMENT_ROOT'] . "/ModuloFornecedor/Logs";
            
            // Se o diretório não existir, cria-o
            if (!is_dir($diretoriosDeLogs)) {
                mkdir($diretoriosDeLogs);
            }

            $fopen = fopen($diretoriosDeLogs . "/execucao_" . date("Ymd") . ".log", "a");
            fwrite($fopen, $conteudo_file);
            fclose($fopen);
        }
    }

    /**
     * Este método configura o tipo dos caracteres para UTF-8
     */
    function configuraUTF8() {
        parent::exec("SET NAMES utf8");
        // Comentei a linha abaixo porque no momento da conexão o character_set já é setado
        //parent::exec("SET character_set='utf8'");
        parent::exec("SET collation_connection='utf8_general_ci'");
        parent::exec("SET character_set_connection=utf8");
        parent::exec("SET character_set_client=utf8");
        parent::exec("SET character_set_results=utf8");
    }

    /**
     * Recupera o último id inserido numa tabela. Cuidado! Não utilize este 
     * mátodo em transações. Utilize o método 
     * recuperaIdEmTransacoesMultiobjetos($tabela = null) no lugar.
     * 
     * @return boolean/int retorna o id da última tupla inserida ou false se 
     * ocorrer erro.
     */
    function recuperaId($tabela = null) {
        if (is_null($tabela)) {
            return parent::lastInsertId();
        } else {
            return parent::lastInsertId($tabela);
        }
    }

    /**
     * Este métedo é para iniciar a transação com o banco de dados. Entra no 
     * lugar do iniciaTransacaoComApenasUmObjeto().
     * Ele usa a classe TTransaction que permite abrir transações envolvendo 
     * mais de um objeto.
     */
    public function iniciaTransacao() {
        try {
            if (TTransaction::open($this->atributosBd, $_SERVER['DOCUMENT_ROOT'] . "/FabricaDeSoftware/fsw/Default/bd_mysql.ini")) {
                $this->conexaoParaTransacoesMultiobjetos = TTransaction::getConexao();
            } else {
                return false;
            }

            return true;
        } catch (Exception $e) {
            $this->setMensagem("Erro ao tentar iniciar a transa&ccedil;&atilde;o. Contate o analista respons&aacute;vel.");
            $this->setMensagem($e->getMessage());
            return false;
        }
    }

    /**
     * Aplica todas as operações realizadas na transação e fecha a conexão com o
     * BD.
     * @return boolean
     */
    public function validaTransacao() {
        
        return TTransaction::commit();
    }

    /**
     * Descarta todas as operações realizadas na transação.
     * @return boolean
     */
    public function descartaTransacao() {
        
        return TTransaction::rollback();
    }

    function getConexaoParaTransacoesMultiobjetos() {
        return $this->conexaoParaTransacoesMultiobjetos;
    }

    function setConexaoParaTransacoesMultiobjetos($conexaoParaTransacoesMultiobjetos) {
        return $this->conexaoParaTransacoesMultiobjetos = $conexaoParaTransacoesMultiobjetos;
    }

    function executaPsEmTransacoesMultiobjetos($query, array $arrayDeValores) {
        try {
            $preparou = $this->conexaoParaTransacoesMultiobjetos->prepare($query);
            if ($preparou) {
                $this->pdoStatment = $preparou;
            } else {
                $this->geraLogDeErro($query, "PREPARE : " . parent::errorInfo());
                return false;
            }
        } catch (Exception $e) {
            $this->geraLogDeErro($query, $e->getMessage());
            return false;
        }
        try {
            $executou = $this->pdoStatment->execute(array_values($arrayDeValores));
            if ($executou) {
                $this->geraLogDeExecucao($query, 'executaPsEmTransaoesMultiobjetos');
                return true;
            } else {
                $this->geraLogDeErro($query, $this->getBdError());
                return false;
            }
        } catch (Exception $e) {
            $this->geraLogDeErro($query, "EXECUTE : " . $e->getMessage());
            return false;
        }
    }

    function recuperaIdEmTransacoesMultiobjetos($tabela = null) {
        if (is_null($tabela)) {
            return $this->conexaoParaTransacoesMultiobjetos->lastInsertId();
        } else {
            return $this->conexaoParaTransacoesMultiobjetos->lastInsertId($tabela);
        }
    }

    /**
     * Similar ao método executaArrayDeQuerysComTransacao porém executa o método
     * executaPs desta classe que precisa receber as querys parametrizadas (?)
     * e os parâmetros. Além disso trabalha com multiplus objetos e, portanto,
     * trabalha com os métodos para transações multilobjetos.
     * 
     * @param type $arrayPsEParametros Vetor onde cada posição deve conter um 
     * array com uma query na primeira posição e um array com os parâmetros na 
     * segunda posição.
     * Exemplo: Array (String $query, Array $arrayDeParametros)
     * @return boolean True se executou todos as querys e False se ocorreu algum 
     *                 erro ou não executou alguma tupla.
     */
    function executaArrayDePsComTransacaoParaMultiobjetos(Array $arrayPsEParametros) {
        $this->iniciaTransacao();

        foreach ($arrayPsEParametros as $psEParametros) {
            $executouQuery = $this->executaPsEmTransacoesMultiobjetos($psEParametros[0], $psEParametros[1]);
            if ($executouQuery) {
                //continua
            } else {
                $this->descartaTransacao();
                return false;
            }
        }

        $this->validaTransacao();

        return true;
    }

    function getPdoStatment() {
        return $this->pdoStatment;
    }

    function setPdoStatment($pdoStatment) {
        $this->pdoStatment = $pdoStatment;
    }

}

?>
    