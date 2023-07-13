<?php

if( !function_exists('curl_version') ){
    echo utf8_decode("O Curl está desativdo em seu PHP!");
    exit;
}
if (version_compare(phpversion(), '5.6', '<')) {
    echo utf8_decode("A versão de seu PHP é:  " . phpversion() . ", o sistema requer PHP 5.6.x ou superior!");
    exit;
}