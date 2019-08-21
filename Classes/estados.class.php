<?php
namespace Classes;

class Estados {

    public static function unidadeDaFederacaoValida($uf) {
        $ufs = array(
            "AC",
            "AL",
            "AM",
            "AP",
            "BA",
            "CE",
            "DF",
            "ES",
            "GO",
            "MA",
            "MG",
            "MS",
            "MT",
            "PA",
            "PB",
            "PE",
            "PI",
            "PR",
            "RJ",
            "RN",
            "RO",
            "RR",
            "RS",
            "SC",
            "SE",
            "SP",
            "TO"
        );
        return in_array($uf, $ufs);
    }

}
