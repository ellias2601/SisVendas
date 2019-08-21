<?php


abstract class ControllerAbstract {

    protected $acao = null;

    abstract protected function consulta();

    abstract protected function inclui();

    abstract protected function altera();

    abstract protected function exclui();

}
