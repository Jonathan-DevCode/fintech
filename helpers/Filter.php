<?php

class Filter {

    static public function parse_string($str) {
        return addslashes(strip_tags($str));
    }

    static public function trim_str($str) {
        return preg_replace('/\s+/', ' ', $str);
    }

    static public function parse_int($str) {
        return intVal($str);
    }

    // Adicionar escape (para campos que não precisa dessa validação)
    static public function parse_full($arr) {
        foreach($arr as $k => $v) {
            if(isset($arr[$k]) && !empty($arr[$k])) {                
                $arr[$k] = addslashes(strip_tags($arr[$k]));
            }          
        }
        return $arr;
    }

    static public function addslashes($str) {
        return addslashes($str);
    }
    static function parse_numeric($str) {
        return preg_replace("([[:punct:]]|[[:alpha:]]| )", '', $str);
    }

    static public function parse_email($str) {
        return preg_match("/^[a-z0-9_\.\-]+@[a-z0-9_\.\-]*[a-z0-9_\-]+\.[a-z]{2,4}$/", $str);
    }

    static public function parse_link($url) {
        $regex = array('/http|https\:\/\//', '/www./', '/\:\/\//');
        $link = preg_replace($regex, array('', '', ''), $url);
        return ($link != "") ? "http://" . $link : $link;
    }

    static public function parse_cpf($str) {
        return preg_match("preg_match('/^[0-9]{3}\.[0-9]{3}\.[0-9]{3}-[0-9]{2}$/'", $str);
    }

    static public function parse_cnpj($str) {
        return preg_match("#^[0-9]{2}\.[0-9]{3}\.[0-9]{3}/[0-9]{4}-[0-9]{2}$#", $str);
    }

    static public function antiSQL($str, $strip = null) {
        if ($strip != null) {
            return strip_tags(addslashes($str));
        } else {
            return addslashes($str);
        }
    }

    static public function parse_to_date($str,$f = 'd/m/Y H:i') {
        $str = date($f, strtotime($str));
        return $str;
    }

    static public function parse_date($str) {
        $str = preg_replace('/\//', '-', $str);
        $str = date('Y-m-d', strtotime($str));
        return $str;
    }

    static public function decode_utf8($key, $data) {
        foreach ($data as $k => $v) {
            $data[$k][$key] = utf8_decode($data[$k][$key]);
        }
        return $data;
    }

    static public function pre($data, $exit = 0) {
        echo "<pre>", @print_r($data, true), "</pre>";
        if($exit){
            exit;
        }
    }

    static public function moedaArr($key, $data) {
        foreach ($data as $k => $v) {
            $data[$k][$key] = number_format($data[$k][$key], 2, ',', '.');
        }
        return $data;
    }

    static public function moeda($valor, $moeda = 'brl', $mostrar_zero = false) {
        if ($moeda == 'brl') {
            return floatval($valor) ? number_format(floatval($valor), 2, ',', '.') : ($mostrar_zero ? '0,00' : '');
        } else {
            return floatval($valor) ? number_format(floatval($valor), 2, '.', ',') : ($mostrar_zero ? '0,00' : '');
        }
    }

    static public function caracteresEsquerda($string, $num) {
        return substr($string, 0, $num);
    }

    static public function caracteresDireita($string, $num) {
        return substr($string, strlen($string) - $num, $num);
    }

    static public function memoryHuman($size) {
        $unit = array('b', 'kb', 'mb', 'gb', 'tb', 'pb');
        return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
    }

    static public function slug($string)
    {
        $string = self::troca_acentos($string);
        $group_a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç',
            'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð',
            'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú',
            'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä',
            'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í',
            'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø',
            'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'A', 'a', 'A',
            'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c',
            'C', 'c', 'D', 'd', 'Ð', 'd', 'E', 'e', 'E',
            'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g',
            'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H',
            'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i',
            'I', 'i', '?', '?', 'J', 'j', 'K', 'k', 'L',
            'l', 'L', 'l', 'L', 'l', '?', '?', 'L', 'l',
            'N', 'n', 'N', 'n', 'N', 'n', '?', 'O', 'o',
            'O', 'o', 'O', 'o', '?', '?', 'R', 'r', 'R',
            'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's',
            '?', '?', 'T', 't', 'T', 't', 'T', 't', 'U',
            'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u',
            'U', 'u', 'W', 'w', 'Y', 'y', '?', 'Z', 'z',
            'Z', 'z', '?', '?', '?', '?', 'O', 'o', 'U',
            'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u',
            'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', '?',
            '?', '?', '?', '?', '?', 'ç');

        $group_b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C',
            'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D',
            'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U',
            'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a',
            'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i',
            'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o',
            'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A',
            'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c',
            'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E',
            'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g',
            'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H',
            'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i',
            'I', 'i', '', '', 'J', 'j', 'K', 'k', 'L',
            'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l',
            'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o',
            'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R',
            'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's',
            'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U',
            'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u',
            'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z',
            'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U',
            'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u',
            'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A',
            'a', 'AE', 'ae', 'O', 'o', 'c');
        $string = strtolower(str_replace($group_a, $group_b, utf8_decode($string)));
        $pattern = array('/[^a-zA-Z0-9 -]/', '/[ -]+/', '/^-|-$/');
        $replace = array(' ', '-', '');
        return preg_replace($pattern, $replace, $string);
    }

    static public function troca_acentos($str = ''){
        return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/","/(ç)/","/( Ç)/"),
            explode(" ","a A e E i I o O u U n N c C"),$str);
    }

    static public function cut($str, $chars, $info) {
        try {
            $str = strip_tags($str);
            if (strlen($str) >= $chars) {
                $str = preg_replace('/\s\s+/', ' ', $str);
                $str = preg_replace('/\s\s+/', ' ', $str);
                $str = substr($str, 0, $chars);
                $str = preg_replace('/\s\s+/', ' ', $str);
                $arr = explode(' ', $str);
                array_pop($arr);
                $final = implode(' ', $arr) . $info;
            } else {
                $final = $str;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
        return $final;
    }

    static function hash($width = 8) {
        $chars = 'abcdefghijlmnopqrstuvxwzABCDEFGHIJLMNOPQRSTUVXYWZ0123456789-.^*%#@!';
        $max = strlen($chars) - 1;
        $pass = "";
        for ($i = 0; $i < $width; $i++) {
            $pass .= $chars[mt_rand(0, $max)];
        }
        return $pass;
    }

    static public function getLatLon($address) {
        $address = urlencode(utf8_encode($address));
        $url = "http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&region=Brazil";
        //$json = file_get_contents($url);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $json = curl_exec($ch);
        $json = json_decode($json);
        if (isset($json->status) && $json->status == "OK") {
            $lat = $json->results[0]->geometry->location->lat;
            $lon = $json->results[0]->geometry->location->lng;
            return (object) array('lat' => $lat, 'lon' => $lon);
        } else {
            return (object) array('lat' => '', 'lon' => '');
        }
    }
    static function zeroEsquerda($str, $tam) {
        return (strlen($str) < $tam) ? "0$str" : $str;
    }
 public static function phone2Number($fone = ''){
        $fone = str_replace('(', '', $fone);
        $fone = str_replace(')', '', $fone);
        $fone = str_replace('-', '', $fone);
        $fone = str_replace(' ', '', $fone);
        return $fone;
    }

}