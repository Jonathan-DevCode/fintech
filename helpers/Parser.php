<?php

class Parser
{

    static public function check($val, $type)
    {
        if ($type == 'string') {
            return addslashes($val);
        }
        if ($type == 'int') {
            return intval($val);
        }
        if ($type == 'upper') {
            return strtoupper($val);
        }
        if ($type == 'lower') {
            return strtolower($val);
        }
        if ($type == 'md5') {
            return md5($val);
        }
        if ($type == 'strip') {
            return stripslashes(trim($val));
        }
        if ($type == 'moeda') {
            return preg_replace(array('/,/', '/./'), array('', ''), $val);
        }
    }

    static function crypt($str)
    {
        return md5($str);
    }

    static function string($str)
    {
        return addslashes(strip_tags($str));
    }

    static function replace($p, $b, $str)
    {
        return preg_replace($p, $b, $str);
    }

    static function trim($str)
    {
        return preg_replace('/\s+/', ' ', $str);
    }

    static function int($str)
    {
        return intVal($str);
    }

    static function numeric($str)
    {
        return preg_replace("([[:punct:]]|[[:alpha:]]| )", '', $str);
    }

    static function email($str)
    {
        return preg_match("/^[a-z0-9_\.\-]+@[a-z0-9_\.\-]*[a-z0-9_\-]+\.[a-z]{2,4}$/", $str);
    }

    static function link($url)
    {
        $regex = array('/http|https\:\/\//', '/www./', '/\:\/\//');
        $link = preg_replace($regex, array('', '', ''), $url);
        return ($link != "") ? "http://" . $link : $link;
    }

    static function cpf($str)
    {
        return preg_match("preg_match('/^[0-9]{3}\.[0-9]{3}\.[0-9]{3}-[0-9]{2}$/'", $str);
    }

    static function cnpj($str)
    {
        return preg_match("#^[0-9]{2}\.[0-9]{3}\.[0-9]{3}/[0-9]{4}-[0-9]{2}$#", $str);
    }

    static function date($str, $patt = 'Y-m-d')
    {
        $str = preg_replace('/\//', '-', $str);
        $str = date($patt, strtotime($str));
        return $str;
    }

    static function antiSQL($str, $strip = null)
    {
        if ($strip != null) {
            return strip_tags(addslashes($str));
        } else {
            return addslashes($str);
        }
    }

    static function valid($data, $key)
    {
        if (isset($data[$key]) && !empty($data[$key])) {
            return true;
        }
        return false;
    }

    static function reverse_parse_date($str)
    {
        $str = preg_replace('/\-/', '/', $str);
        $str = date('d/m/Y', strtotime($str));
        return $str;
    }


    static function memoryHuman($size)
    {
        $unit = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
        return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
    }

    static function check_agent($type = NULL)
    {
        $user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        if ($type == 'bot') {
            // matches popular bots
            if (preg_match("/googlebot|adsbot|yahooseeker|yahoobot|msnbot|watchmouse|pingdom\.com|feedfetcher-google/", $user_agent)) {
                return true;
                // watchmouse|pingdom\.com are "uptime services"
            }
        } else if ($type == 'browser') {
            // matches core browser types
            if (preg_match("/mozilla\/|opera\//", $user_agent)) {
                return true;
            }
        } else if ($type == 'mobile') {
            // matches popular mobile devices that have small screens and/or touch inputs
            // mobile devices have regional trends; some of these will have varying popularity in Europe, Asia, and America
            // detailed demographics are unknown, and South America, the Pacific Islands, and Africa trends might not be represented, here
            if (preg_match("/phone|iphone|itouch|ipod|symbian|android|htc_|htc-|palmos|blackberry|opera mini|iemobile|windows ce|nokia|fennec|hiptop|kindle|mot |mot-|webos\/|samsung|sonyericsson|^sie-|nintendo/", $user_agent)) {
                // these are the most common
                return true;
            } else if (preg_match("/mobile|pda;|avantgo|eudoraweb|minimo|netfront|brew|teleca|lg;|lge |wap;| wap /", $user_agent)) {
                // these are less common, and might not be worth checking
                return true;
            }
        }
        return false;
    }

    static function parse_data_br($data)
    {
        $date = new DateTime($data);
        $day = $date->format("l");
        $daynum = $date->format("j");
        $month = $date->format("F");
        $year = $date->format("Y");

        switch ($day) {
            case "Monday":
                $day = "Segunda-Feira";
                break;
            case "Tuesday":
                $day = "Terça-Feira";
                break;
            case "Wednesday":
                $day = "Quarta-Feira";
                break;
            case "Thursday":
                $day = "Quinta-Feira";
                break;
            case "Friday":
                $day = "Sexta-Feira";
                break;
            case "Saturday":
                $day = "Sábado";
                break;
            case "Sunday":
                $day = "Domingo";
                break;
            default:
                $day = "Unknown";
                break;
        }

        switch ($month) {
            case "January":
                $month = "Janeiro";
                break;
            case "February":
                $month = "Fevereiro";
                break;
            case "March":
                $month = "Março";
                break;
            case "April":
                $month = "Abril";
                break;
            case "May":
                $month = "Maio";
                break;
            case "June":
                $month = "Junho";
                break;
            case "July":
                $month = "Julho";
                break;
            case "August":
                $month = "Agosto";
                break;
            case "September":
                $month = "Setembro";
                break;
            case "October":
                $month = "Outubro";
                break;
            case "November":
                $month = "Novembro";
                break;
            case "December":
                $month = "Dezembro";
                break;
            default:
                $month = "Unknown";
                break;
        }
        //echo $daynum . " de " . $month . " de " . $year;exit;
        $ndata = new stdClass;
        $ndata->dia = $day;
        $ndata->dian = $daynum;
        $ndata->mes = $month;
        $ndata->ano = $year;
        $ndata->dataext = "$daynum de  $month de $year";
        return $ndata;
    }

    // ORLANDO 04/07/2019
    public static function unserialize_form($str, $blank = false) {
        $returndata = array();
        $strArray = explode("&", $str);
        $i = 0;
        foreach ($strArray as $item) {
            $array = explode("=", $item);
            if($blank){
                $valor = urldecode($array[1]);
                empty($valor) ? : $returndata[$array[0]] = urldecode($array[1]);
            }else{
                $returndata[$array[0]] = urldecode($array[1]);
            }
        }
        return $returndata;
    }

}
