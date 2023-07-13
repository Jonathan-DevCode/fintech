<?php

class Server
{

    static public function indexAction($out = 1)
    {
        if ($out == 1)
            return $_SERVER['SERVER_SOFTWARE'];
        else
            echo $_SERVER['SERVER_SOFTWARE'];
    }

    static public function info()
    {
        phpinfo();
    }


    static public function php()
    {
        echo 'PHP ', phpversion();
    }

    static public function vars()
    {
        echo '<pre>';
        print_r($_SERVER);
    }

    static function memory_start()
    {
        $size = memory_get_usage();
        $unit = array('b', 'kb', 'mb', 'gb', 'tb', 'pb');
        $size = @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
        $str = "Uso de memoria no inicio:" . $size . " \n\n";
        return $str;
    }

    static function memory_end()
    {
        $size = memory_get_usage();
        $size_pico = memory_get_peak_usage();
        $unit = array('b', 'kb', 'mb', 'gb', 'tb', 'pb');
        $size = @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
        $size_pico = @round($size_pico / pow(1024, ($i = floor(log($size_pico, 1024)))), 2) . ' ' . $unit[$i];
        $str = "Uso de memoria no final:" . $size . " \n";
        //$str .= "O Pico de memoria:" . $size_pico . "\n\n";
        return $str;
    }

}
