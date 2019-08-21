<?php
namespace Classes;

abstract class InputsHtml {

    protected $disabled;

    function __construct($disabled = false) {
        $this->disabled = null;
    }

    function getDisabled() {
        return $this->disabled;
    }

    function setDisabled($disabled = false) {
        if ($disabled) {
            $this->disabled = "disabled = 'disabled'";
        } else {
            $this->disabled = null;
        }
    }
}