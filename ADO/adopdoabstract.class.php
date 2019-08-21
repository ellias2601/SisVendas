<?php

/**

 * Descrição de ADO:
 * Esta classe cuida dos métodos para persistência no banco de dados e será extendida 
 * diretamente pelas classes da camada ADO. Métodos genéricos são implementados nesta
 * e os que são mais específicos de cada objeto são implementados nas classes filhas
 * 
 * Esta classe extende a classe BancoDeDadosPdo.

 */
require_once '../ADO/bancodedadospdo.class.php';

abstract class AdoPdoAbstract extends BancoDeDadosPdo {

    private $nomeDaTabela = null;

    /**
     * Monta um objeto do tipo model com os dados informados via parâmetro. O
     * parâmetro deve ser do tipo array e deve conter uma tupla da tabela lida.
     * 
     * @param Arrah $objetoBD Tupla de uma tabela recuperados do banco de dados. 
     * Precisa conter todos os dados recuperados.
     * @return ModelAbstract Objeto do tipo model.
     */
    public function montaObjeto($objetoBD) {
        // A função get_called_class() busca o nome da classe que extende esta. 
        // Ou seja, pega o nome do ObjetoAdo (Ex.: RelatorioDeAtividadeAdo).
        $classeAdo = get_called_class();

        // Agora substitui a palavra ADO por Model no nome da classe. Esse nome será 
        // utilizado para estanciar a classe Model do objeto desejado. 
        // (Ex.: RelatorioDeAtividadeAdo vira RelatorioDeAtividadeModel).
        $classeModel = str_replace("Ado", "Model", $classeAdo);

        // Adiciona uma classe que precisa ser extendida. Método da ExtensionBridgeAbstract.
        $objetoModel = new $classeModel();

        foreach ($objetoBD as $nomeDaColuna => $valorDaColuna) {
            $setAtributo = "set" . strtoupper(substr($nomeDaColuna, 0, 1)) . substr($nomeDaColuna, 1);
            $objetoModel->$setAtributo($valorDaColuna);
        }

        return $objetoModel;
    }

    /**
     * Recupera os dados de um objeto do tipo model e monta em um array com as 
     * posições identificadas com os nomes dos atributos.
     * 
     * @param ModelAbstract $objetoModel Objeto do tipo model.
     * @return Array Array com os dados recuperados a partir do objeto do tipo model.
     */
    protected function montaArrayDeDadosDaTabela(ModelAbstract $objetoModel) {
        $vetor = array();

        $metodosDaClasse = get_class_methods($objetoModel);
        foreach ($metodosDaClasse as $value) {
            /**
             * @todo A função eregi abaixo deve ser substituída pq na versão 7 não existe mais.
             */
            if (substr_compare($value, 'set', 0, 3, $case_insensitive = false) === 0) {
                if (substr_compare($value, 'Mensagem', -8) == 0) {
                    //setMensagem deve ser ignorada
                } else {
                    $nomeDoAtributo = str_replace("set", "", $value);

                    //lcfirst() troca a primeira letra para lower case.
                    //Transforma o nome do atributo para o nome da coluna no BD
                    $nomeDaColunaDoBd = Strings::trocaAtributoParaNomeColuna(lcfirst($nomeDoAtributo));

                    //Transforma o nome da coluna no bd para o nome do metodo get para o atributo.
                    $getAtributo = Strings::trocaNomeColunaParaGetAtributo($nomeDaColunaDoBd);

                    //alimenta o array
                    $vetor[$nomeDaColunaDoBd] = $objetoModel->$getAtributo();
                }
            }
        }
        return $vetor;
    }

    /**
     * Este métedo lê uma linha de um  determinado objeto.
     * @param type $resultado
     * @return boolean
     */
    function leObjeto() {
        $objetoBD = $this->leTabelaBD();
        if ($objetoBD) {
            return $this->montaObjeto($objetoBD);
        } else {
            return FALSE;
        }
    }

    /**
     * Implementa a consulta a tabelas como o método consultaObjeto() usando a 
     * parametrização dos valores para maior segurança, por isso exige um array 
     * com os valores para substituição no Prepare. Necessita do nome da tabela 
     * no atributo de classe $nomeDaTabela (use o método setNomeDaTabela).
     * 
     * @param type $arrayDeValoresParaPs Array com os valores a serem 
     *             substituídos pelo Prepare (PS). Têm que estar na ordem 
     *             identificada pelo ? na clásula where.
     * @param type $where String com a expressão lógica para ser montada após a
     *             cláusula where do select com ? no lugar dos valores. Não obrigatória.
     * @param type $orderBy Instrução order by completa. Não obrigatória.
     * @return int|boolean Retorna true para execução ok, false para 
     *         erro/exceção e 0 para consulta vazia.
     * @throws ExcecaoNaClasseAdo Lança essa exceção quando o nome da tabela não 
     *         está identificado no atributo da classe.
     */
    public function consultaObjetoComPs($arrayDeValoresParaPs, $where = 1, $orderBy = NULL) {
        if (is_null($this->nomeDaTabela)) {
            throw new ExcecaoNaClasseAdo("Voc&ecirc; deve identificar o nome da tabela para usar esta classe. Utilize o setNomeDaTabela.");
        }

        $query = "select * from {$this->nomeDaTabela} where {$where} {$orderBy} ";

        $executou = parent::executaPs($query, $arrayDeValoresParaPs);
        if ($executou) {
            if (parent::qtdeLinhas() === 0) {
                return 0;
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * Aquele que implementar esta classe deve implementar os métodos para inserir,
     * alterar e excluir no banco de dados.
     */
    //abstract function consultaObjeto($where = '1', $orderBy = NULL);

    abstract function insereObjeto(ModelAbstract $objetoModel);

    abstract function alteraObjeto(ModelAbstract $objetoModel);

    abstract function excluiObjeto(ModelAbstract $objetoModel);

    /**
     * Implementa a consulta a tabelas como o método consultaObjetoPs() usando a 
     * parametrização dos valores para maior segurança, por isso exige um array 
     * com os valores para substituição no Prepare. Necessita do nome da tabela 
     * no atributo de classe $nomeDaTabela (use o método setNomeDaTabela).
     * 
     * Pode lançar a exceção ExcecaoNaClasseAdo porque chama o método 
     * consultaObjetoComPs() que lança essa exceção quando o nome da tabela não 
     * está identificado no atributo da classe.
     * 
     * Retorna o a tupla recuperada em forma de objeto do tipo Model.
     * 
     * @param type $arrayDeValoresParaPs Array com os valores a serem 
     *             substituídos pelo Prepare (PS). Têm que estar na ordem 
     *             identificada pelo ? na clásula where.
     * @param type $where String com a expressão lógica para ser montada após a
     *             cláusula where do select com ? no lugar dos valores. Não 
     *             obrigatória.
     * @param type $orderBy Instrução order by completa. Não obrigatória.
     * @return int|boolean|Objeto Retorna true para execução ok, false para 
     *         erro/exceção, 0 para consulta vazia ou objeto do tipo model da 
     *         tupla encontrada.
     */
    public function buscaObjetoComPs($arrayDeValoresParaPs, $where, $orderBy = NULL) {
        $consultou = $this->consultaObjetoComPs($arrayDeValoresParaPs, $where, $orderBy);

        if ($consultou) {
            return $this->leObjeto($consultou);
        } else {
            return $consultou;
        }
    }

    /**
     * Implementa a consulta a tabelas como o método consultaObjetoPs() usando a 
     * parametrização dos valores para maior segurança.
     * 
     * Pode lançar a exceção ExcecaoNaClasseAdo porque chama o método 
     * consultaObjetoComPs() que lança essa exceção quando o nome da tabela não 
     * está identificado no atributo da classe.
     * 
     * Retorna um array com os cada tupla recuperada em forma de objeto do tipo
     * Model.
     * 
     * @param type $arrayDeValoresParaPs Array com os valores a serem 
     *             substituídos pelo Prepare (PS). Têm que estar na ordem 
     *             identificada pelo ? na clásula where.
     * @param type $where String com a expressão lógica para ser montada após a
     *             cláusula where do select com ? no lugar dos valores. Não 
     *             obrigatória.
     * @param type $orderBy Instrução order by completa. Não obrigatória.
     * @return int|boolean|Objeto Retorna true para execução ok, false para 
     *         erro/exceção, 0 para consulta vazia ou array com cada tupola 
     *         recuperada em forma de objetos do tipo Model.
     * @throws ExcecaoNaClasseAdo Lança essa exceção quando a cláusula where foi
     *         definida e o array de valores está nulo.
     */
    function buscaArrayObjetoComPs($arrayDeValoresParaPs = null, $where = '1', $orderBy = NULL) {
        //Quando se monta o where deve-se montar obrigatoriamente o array de valores também.
        if (is_null($arrayDeValoresParaPs)) {
            //qando o $arrayDeValoresParaPs vier nulo, apenas troca p/ um array 
            //vazio para parametrizar a consultaObjetoComPs() corretamente que exige um array.
            $arrayDeValoresParaPs = array();
            if ($where == '1') {
                //se o array veio nulo e o where tá == 1, ok.
            } else {
                //tá errado se o array não veio nulo e o where não tá == 1.
                throw new Exception("Para sele&ccedil;&otilde;es de linhas com cl&aacute;usula where informe o array com os valores para substitui&ccedil;&atilde;o.");
            }
        }
        $arrayObjeto = array(); //variável array a ser alimentada;

        $resultado = $this->consultaObjetoComPs($arrayDeValoresParaPs, $where, $orderBy);

        if ($resultado) {
            //continua
        } else if ($resultado === 0) {
            return 0;
        } else {
            return FALSE;
        }

        while (($objeto = $this->leObjeto()) !== FALSE) {
            /*
             * É necessário clonar o objeto, pois o objeto é uma referência a este mesmo.
             * Ao se clonar, cria-se uma cópia. Sem clonar o array conteria em cada uma
             * das suas posições a mesma referência, o que geraria múltiplas ocorrência
             * de um único objeto. No entanto o que se pretende com este método é que 
             * se tenha em cada ocorrência um objeto que represente cada uma das tuplas
             * selecinadas na tabela representada.
             */

            $arrayObjeto [] = clone ($objeto);
        }
        return $arrayObjeto;
    }

    /**
     * Método para montar Insert para a execução com Prepared Statement
     * onde os valores serão referenciados por ?
     * @param String $tabela
     * @param array $colunasValores
     * @return String $query
     */
    function montaInsertDoObjetoPS($tabela, array $colunasValores) {
        $primeiraColuna = true;
        $colunas = " (";
        $valores = " values (";
        $param = "?";

        foreach ($colunasValores as $nomeDaColuna => $valorDaColuna) {
            if ($primeiraColuna) {
                $primeiraColuna = false;
            } else {
                $colunas .= ", ";
                $valores .= ", ";
            }

            $colunas .= "`{$nomeDaColuna}`";
            $valores .= "({$param})";
        }
        $colunas .= ") ";
        $valores .= ") ";

        $query = "insert into {$tabela} " . $colunas . $valores;

        return $query;
    }

    /**
     * Este método montará uma query com os dados do Objeto model para inserção.
     * @param ModelAbstract $objetoModel
     * @return String Uma <br>query<br> para inserção
     */
    protected function montaQueryInsersao(ModelAbstract $objetoModel) {
        $arrayColunasValores = $this->montaArrayDeDadosDaTabela($objetoModel);
        return $this->montaInsertDoObjetoPS($this->getNomeDaTabela(), $arrayColunasValores);
    }

    /**
     * Monta um array com a query parametrizada de insersão e com os dados a 
     * serem substituídos. O array retornado pode ser executado diretamente no 
     * método BandoDeDados::executaArrayDePsComTransacaoParaMultiobjetos (Array $arrayPsEParametros).
     * @param ModelAbstract $objetoModel
     * @return Array Array com a query na primeira posição e outro array com as colunas e valores na segunda posição.
     */
    public function montaInsersaoParametrizada(ModelAbstract $objetoModel) {
        $arrayColunasValores = $this->montaArrayDeDadosDaTabela($objetoModel);
        $query = $this->montaQueryInsersao($objetoModel);
        return Array($query, $arrayColunasValores);
    }

    /**
     *  Monta a string da query Update com o nome da tabela, as colunas e os 
     * parametros em ? para serem substituidos dentro do executePS
     * 
     * @param type $tabela Nome da Tabela
     * @param array $arrayDeColunasEValores 
     *                     Array no formato ("nome_da_coluna" => "valor_da_coluna").
     *                     Se alguma coluna for nula use NULL sem aspas
     *                     para o seu valor.
     * @param type $where  Critério para fazer a atualização.
     * @return string      Query de update.
     */
    function montaUpdateDoObjetoPS($tabela, array $arrayDeColunasEValores, $where) {
        $colunasEValores = NULL;
        $contadorIteracoes = 0;
        $numeroDeColunas = count($arrayDeColunasEValores);
        $param = '?';

        foreach ($arrayDeColunasEValores as $nomeDaColuna => $valorDaColuna) {
            $colunasEValores .= "`{$nomeDaColuna}` = ";

            $colunasEValores .= " ({$param})";

            $contadorIteracoes++;

            if ($contadorIteracoes < $numeroDeColunas) {
                $colunasEValores .= ", ";
            }
        }

        $query = "update {$tabela} set " . $colunasEValores . " where $where";

        return $query;
    }

    function montaDeleteUsandoAndDoObjetoPS($tabela, array $arrayDeColunasEValores) {
        $where = NULL;
        $contadorIteracoes = 0;
        $numeroDeColunas = count($arrayDeColunasEValores);
        $param = '?';

        foreach ($arrayDeColunasEValores as $nomeDaColuna => $valorDaColuna) {
            $where .= "`{$nomeDaColuna}` = ";
            $where .= " {$param}";

            $contadorIteracoes++;

            if ($contadorIteracoes < $numeroDeColunas) {
                $where .= " AND ";
            }
        }

        $query = "DELETE FROM {$tabela} WHERE $where";

        return $query;
    }

    function setNomeDaTabela($nomeDaTabela) {
        $this->nomeDaTabela = $nomeDaTabela;
    }

    function getNomeDaTabela() {
        return $this->nomeDaTabela;
    }

}
