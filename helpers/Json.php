<?php

class Json
{

    public static function open($file, $decode = false)
    {
        $handle = @fopen($file, "r");
        $bufferOut = "";
        if ($handle) {
            while (($buffer = fgets($handle, 4096)) !== false) {
                $bufferOut .= $buffer;
            }
            fclose($handle);
            if ($decode === true) {
                return json_decode($bufferOut);
            } else {
                return $bufferOut;
            }
        }

        exit;
        if (file_exists($file)) {
            $file = file_get_contents($file);
            if ($decode === true) {
                return json_decode($file);
            } else {
                return $file;
            }
        }
    }

    public static function save(&$file, $data)
    {
        if (file_exists($file)) {
            unlink($file);
        }
        file_put_contents($file, json_encode($data), LOCK_EX);
    }

    public static function find(&$obj, $field, $value)
    {
        $return = null;
        $obj = json_decode($obj);
        foreach ($obj as $k => &$v) {
            if (isset($obj[$k]->$field)) {
                if ($obj[$k]->$field == "$value") {
                    $return[] = $obj[$k];
                }
            }
        }
        return json_encode($return);
    }

}
