<?php

class Path
{

    static public function base()
    {
        $dir = dirname(__FILE__);
        $dir = explode(DIRECTORY_SEPARATOR . "helpers" . DIRECTORY_SEPARATOR, $dir);
        $dir = explode(DIRECTORY_SEPARATOR . "helpers", $dir[0]);
        return $dir[0];
    }

    static public function create_dir($file)
    {

    }


    static public function open_file($file)
    {

    }

}
