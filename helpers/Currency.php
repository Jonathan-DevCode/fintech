<?php

class Currency {

    static function moedaArr($key, $data) {
        foreach ($data as $k => $v) {
            $data[$k][$key] = number_format($data[$k][$key], 2, ',', '.');
        }
        return $data;
    }

    static function moeda($valor, $mostrar_zero = false) {
        return $valor ? number_format($valor, 2, ',', '.') : ($mostrar_zero ? '0,00' : '');
    }

    static function moedaUS($valor, $mostrar_zero = true) {
        return $valor ? number_format($valor, 2, '.', ',') : ($mostrar_zero ? '0' : '');
    }

    static function caracteresEsquerda($string, $num) {
        return substr($string, 0, $num);
    }

    static function caracteresDireita($string, $num) {
        return substr($string, strlen($string) - $num, $num);
    }

}
