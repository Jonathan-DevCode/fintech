<?php

class Math {

    static function divide($a = 0, $b = 0) {
        if ($b != 0) {
            return ($a / $b);
        } else {
            return 0;
        }
    }

    static function reverse_decimal($num, $arr = null) {
        if ($arr == null) {
            if (preg_match('/,/', $num)) {
                return preg_replace(array('/\,/i'), array('.'), $num);
            } else {
                return preg_replace(array('/\./i'), array(','), $num);
            }
        } else {
            foreach ($num as $k => $v) {
                $n = $v;
                if (preg_match('/,/', $n)) {
                    $num[$k] = preg_replace(array('/\,/'), array('.'), $n);
                } else {
                    $num[$k] = preg_replace(array('/\./'), array(','), $n);
                }
            }
            return $num;
        }
    }

    static function format_decimal($valor, $casas = 2, $glue = ',') {
        $a = explode("$glue", $valor);
        if (isset($a[0]) && isset($a[1])) {
            $f = substr($a[1], 0, $casas);
            if (strlen($f) <= 1) {
                $f = $f . "0";
            }
            $b = $a[0] . $glue . $f;
        } else {
            $b = $valor;
        }
        return $b;
    }

    static function percentual($valor, $percentual, $op = "+")
    {
        if ($op == '+') {
            return $valor + (($valor / 100) * $percentual);
        } else {
            return $valor - (($valor / 100) * $percentual);
        }
    }

    static function percentual_de($v1, $v2) {
        if($v2 > 0)
        return  (($v1 / $v2) * 100 );
        else
            return 0;
    }

    // calcula dif de porcentagem entre 2 valores
    static function percentual_entre($v1, $v2){
        if($v2 > 0) {
            $inteiro = (int)($v1 / $v2);
            $resto = ($v1 / $v2) - $inteiro;
            return  number_format( ($resto * 100) , 0, ',' , '.');
        }else
            return 0;
    }

    static function only_number($str)
    {
        return preg_replace('/[^0-9]/', '', $str);
    }

    static function zeroEsquerda($str, $tam) {
        return (strlen($str) < $tam) ? "0$str" : $str;
    }

    static function moeda($valor, $zero = '0,00', $vazio = null) {
        //return ($valor > 0 ) ? number_format($valor, 2, ',', '.') : ($vazio ? '' : $zero);
        return number_format($valor, 2, ',', '.');
    }

    static function moedaUS($valor, $zero = '0.00', $vazio = null) {
        //return ($valor > 0 ) ? number_format($valor, 2, '.', ',') : ($vazio ? '' : $zero);
        return number_format($valor, 2, '.', ',');
    }

    static function money2Decimal($valor) {
        $pat = array('/\./', '/\,/');
        $rep = array('', '.');
        return preg_replace($pat, $rep, $valor);
    }

    static function maskJs2Money($valor) {
        $pat = array('/\./', '/\,/');
        $rep = array('', '.');
        return preg_replace($pat, $rep, $valor);
    }

    static function nonDecimal($valor) {
        $pat = array('/\.00/', '/\,00/');
        $rep = array('', '');
        return preg_replace($pat, $rep, $valor);
    }

    static function decimal($valor) {
        $pat = array('/\,/', '/\,/');
        $rep = array('.', '.');
        return preg_replace($pat, $rep, $valor);
    }

}
