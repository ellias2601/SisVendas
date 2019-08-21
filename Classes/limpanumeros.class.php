<?php
namespace Classes;

class LimpaNumeros {

    public static function retiraNaoNumericos($valor) {
        $valor = trim($valor);
        $valor = str_split($valor);

        $numero = array();
        $i = 0;
        foreach ($valor as $caratere) {
            if (strlen($caratere) > 0) {
                $ehNumero = is_numeric($caratere);
                if ($ehNumero) {
                    $numero[$i] = $caratere;
                    $i++;
                }
            }
        }

        return implode('', $numero);
    }

}
