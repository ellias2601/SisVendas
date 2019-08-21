<?php

require_once '../Classes/inputhtml.class.php';
require_once '../Classes/labelhtml.class.php';
require_once '../Classes/buttonhtml.class.php';
require_once '../Classes/texteareahtml.class.php';
require_once '../Classes/brhtml.class.php';
require_once '../Classes/phtml.class.php';
require_once '../Classes/formhtml.class.php';
require_once '../Classes/optionhtml.class.php';
require_once '../Classes/selecthtml.class.php';
require_once '../Classes/legendhtml.class.php';
require_once '../Classes/fieldsethtml.class.php';

use Classes\inputhtml as InputHtml;
use Classes\labelhtml as LabelHtml;
use Classes\buttonhtml as ButtonHtml;
use Classes\texteareahtml as TexteAreaHtml;
use Classes\brhtml as BrHtml;
use Classes\phtml as PHtml;
use Classes\formhtml as FormHtml;

abstract class InterfaceHtml {

    protected $inputHtml;
    protected $labelHtml;
    protected $buttonHtml;
    protected $texteAreaHtml;
    protected $break;
    protected $p;
    protected $formhtml;
    protected $optionhtml;
    protected $selecthtml;
    protected $html1 = null;
    protected $html2 = null;
    protected $forms = null;
    protected $mensagens;
    protected $bt = null;

    public function __construct($titulo) {
        $this->inputHtml = new InputHtml();
        $this->labelHtml = new LabelHtml();
        $this->buttonHtml = new ButtonHtml();
        $this->texteAreaHtml = new TexteAreaHtml();
        $this->break = new BrHtml();
        $this->p = new PHtml();
        $this->formhtml = new FormHtml();
        $this->optionhtml = new OptionHtml();
        $this->selecthtml = new SelectHtml();

        $this->mensagens = array();

        $this->montaHtml1($titulo);
        $this->montaHtml2();
    }

    public function montaHtml1($titulo) {
        $this->html1 = "<html><head><title>" . $titulo . "</title></head><body>";
    }

    abstract protected function montaForms($dados, $acao);

    public function montaHtml2() {
        $this->html2 = "</body></html>";
    }

    public function displayInterface($dados, $acao) {
        $this->forms = $this->montaForms($dados, $acao);

        $mensagens = $this->montaMensagens();

        echo $this->html1 . $mensagens . $this->forms . $this->html2;
    }

    function getBt() {
        if (isset($_POST['bt'])) {
            return $this->bt = $_POST['bt'];
        } else {
            return $this->bt = false;
        }
    }

    function setBt($bt) {
        $this->bt = $bt;
    }

    public abstract function recebeDadosDoFormulario();

    public function getMensagens() {
        return $this->mensagens;
    }

    public function adicionaMensagem($mensagem) {
        $this->mensagens [] = $mensagem;
    }

    public function adicionaMensagens(Array $mensagens) {
        foreach ($mensagens as $mensagem) {
            $this->adicionaMensagem($mensagem);
        }
    }

    protected function montaMensagens() {
        $textoMensagens = null;
        $mensagens = $this->getMensagens();

        foreach ($mensagens as $mensagem) {
            $textoMensagens .= "<p>" . $mensagem . "</p>";
        }
        return $textoMensagens;
    }

    protected function montaBotoesDeAcordoComAAcao($acao) {
        $botoes = array();

        switch ($acao) {
            case false:
            case "inc" :
                $button = new ButtonHtml();
                $button->setType("submit");
                $button->setName("bt");
                $button->setValue("inc");
                $button->setTexto("INCLUIR");
                //Adiciona ao array de objetos
                $botoes [] = $button;

                break;

            //Caso de alteração em que não pode ocorrer exclusão.
            case "altNoExc" :
                //Button Alterar
                $button = new ButtonHtml();
                $button->setType("submit");
                $button->setName("bt");
                $button->setValue("alt");
                $button->setTexto("ALTERAR");
                $botoes [] = $button;
                break;

            case "con" :
            case "alt" :
            case "exc" :
                //Button Alterar
                $button = new ButtonHtml();
                $button->setType("submit");
                $button->setName("bt");
                $button->setValue("alt");
                $button->setTexto("ALTERAR");
                $botoes [] = $button;

                //Button Excluir
                $button = new ButtonHtml();
                $button->setType("submit");
                $button->setName("bt");
                $button->setValue("exc");
                $button->setTexto("EXCLUIR");
                $botoes [] = $button;
                break;

            case "addCesta" :
            case "removerCesta" :
                $button = new ButtonHtml();
                $button->setType("submit");
                $button->setName("bt");
                $button->setValue("inc");
                $button->setTexto("ADCIONAR A CESTA DE COMPRAS");
                //Adiciona ao array de objetos
                $botoes [] = $button;


                $button = new ButtonHtml();
                $button->setType("submit");
                $button->setName("bt");
                $button->setValue("exc");
                $button->setTexto("EXCLUIR DA CESTA DE COMPRAS");
                //Adiciona ao array de objetos
                $botoes [] = $button;

                $button = new ButtonHtml();
                $button->setType("submit");
                $button->setName("bt");
                $button->setValue("alt");
                $button->setTexto("FAZER CHECKOUT");
                //Adiciona ao array de objetos
                $botoes [] = $button;

                break;

            default:
                break;
        }

        return $botoes;
    }

    protected function montaBotoesDeAcordoComAAcao2($acao) {
        $botoes = array();

        switch ($acao) {
            case false:
            case "inc" :
            case "exc":
            case "con":
            case "addCesta":
            case "removerCesta":

                $button = new ButtonHtml();
                $button->setType("submit");
                $button->setName("bt");
                $button->setValue("inc");
                $button->setTexto("ADCIONAR A CESTA DE COMPRAS");
                //Adiciona ao array de objetos
                $botoes [] = $button;

                $button = new ButtonHtml();
                $button->setType("submit");
                $button->setName("bt");
                $button->setValue("av");
                $button->setTexto("FINALIZAR COMPRA");
                //Adiciona ao array de objetos
                $botoes [] = $button;

                $button = new ButtonHtml();
                $button->setType("submit");
                $button->setName("bt");
                $button->setValue("alt");
                $button->setTexto("FAZER LOGOFF");
                //Adiciona ao array de objetos
                $botoes [] = $button;

                break;

            default:
                break;
        }

        return $botoes;
    }

    protected function montaBotoesDeAcordoComAAcao3($acao) {
        $botoes = array();

        switch ($acao) {
            case false:
            case "inc" :
           
              $button = new ButtonHtml();
              $button->setType("submit");
              $button->setName("bt");
              $button->setValue("inc");
              $button->setTexto("FINALIZAR COMPRA");
              $botoes [] = $button;

              $button = new ButtonHtml();
              $button->setType("submit");
              $button->setName("bt");
              $button->setValue("ret");
              $button->setTexto("RETORNAR AO CARRINHO");
              $botoes [] = $button;
              break;

           
            case "fin":
              $button = new ButtonHtml();
              $button->setType("submit");
              $button->setName("bt");
              $button->setValue("fin");
              $button->setTexto("FINALIZAR SESSÃO / REALIZAR NOVA COMPRA");
              $botoes [] = $button;
              break;
          
             default:
                break;
                
        }

        return $botoes;
    }

    protected function montaBotoesDeAcordoComAAcaoLogin($acao) {
        $botoes = array();
        switch ($acao) {
            case false:
            case "logar":
                //Button logar
                $button = new ButtonHtml();
                $button->setType("submit");
                $button->setName("bt");
                $button->setValue("logar");
                $button->setTexto("LOGAR");
                $botoes [] = $button;

                break;

            case "deslogar":
                //Button deslogar
                $button = new ButtonHtml();
                $button->setType("submit");
                $button->setName("bt");
                $button->setValue("deslogar");
                $button->setTexto("DESLOGAR");
                $botoes [] = $button;
                break;

            default:
                break;
        }
        return $botoes;
    }

    public function getValorOuNull($nomeDoInput) {
        if (isset($_POST[$nomeDoInput])) {
            return $_POST[$nomeDoInput];
        } else {
            return null;
        }
    }

}

?>