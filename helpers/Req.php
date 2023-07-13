<?php

class Req
{
    
    static public function get_type()
    {
        //return POST / PUT / DELETE / GET
    }
    
    static public function query_builder()
    {
        $sql_update = array();
        foreach ($_POST as $key => $input) {
            $_POST[$key] = addslashes(trim($input));
            $sql_update[] = $key . " = '" . $_POST[$key] . "'";
        }
        $sql_update = implode(', ', $sql_update);
        $sql_insert = "(`" . implode("`,`", array_keys($_POST)) . "`) VALUES "
        . "('" . implode("','", array_values($_POST)) . "')";
        $all = array(
            'fields' => array_keys($_POST),
            'values' => array_values($_POST),
            'sql_update' => $sql_update,
            'sql_insert' => $sql_insert,
            'sql_fields' => "(`" . implode("`,`", array_keys($_POST)) . "`)",
            'sql_values' => "('" . implode("','", array_values($_POST)) . "')",
            'obj' => (object)array_combine(array_keys($_POST), array_values($_POST)),
            'array' => array_combine(array_keys($_POST), array_values($_POST)),
            'JSON' => json_encode(array_combine(array_keys($_POST), array_values($_POST))),
        );
        return (object)$all;
    }
    
    static public function now($key = 'created')
    {
        $_POST["$key"] = date('Y-m-d H:i:s');
    }
    
    static public function cookie()
    {
        
    }
    
    static public function filter($data)
    {
        return $data;
    }
    
    static public function post($key = null, $parse = null, $value = null)
    {
        if ($key == null && $value == null) {
            if (isset($_POST) && !empty($_POST)) {
                return $_POST;
            } else {
                return false;
            }
        } else {
            if ($value == null) {
                if (isset($_POST[$key])) {
                    if ($parse != null) {
                        $_POST[$key] = self::parse($_POST[$key], $parse);
                    }
                    return $_POST[$key];
                } else {
                    return false;
                }
            } else {
                if ($value == 'drop') {
                    if(isset($_POST[$key])){
                        $drop = $_POST[$key];
                        unset($_POST[$key]);
                        return $drop;
                    }
                } else {
                    $_POST[$key] = $value;
                    if ($parse != null) {
                        $_POST[$key] = self::parse($_POST[$key], $parse);
                    }
                    return $_POST[$key];
                }
            }
        }
    }
    
    static public function get($key = null, $value = null, $parse = null)
    {
        if ($key == null && $value == null) {
            if (isset($_GET) && !empty($_GET)) {
                return $_GET;
            } else {
                return false;
            }
        } else {
            if ($value == null) {
                if (isset($_GET[$key])) {
                    if ($parse != null) {
                        $_GET[$key] = self::parse($_GET[$key], $parse);
                    }
                    return $_GET[$key];
                }
            } else {
                
                if ($value == 'drop') {
                    unset($_GET[$key]);
                } else {
                    $_GET[$key] = $value;
                    if ($parse != null) {
                        $_GET[$key] = self::parse($_GET[$key], $parse);
                    }
                    return $_GET[$key];
                }
            }
        }
    }
    
    static public function parse($val, $type)
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
    
    static public function changeWith($key, $helper = 'Math', $fn = 'money2Decimal')
    {
        if (isset($_POST[$key])) {
            $_POST[$key] = $helper::$fn($_POST[$key]);
            return $_POST[$key];
        }
    }
    
    static function crypt($key)
    {
        if (isset($_POST["$key"])) {
            $_POST["$key"] = md5($_POST["$key"]);
        }
    }
    
    
    static public function drop($key)
    {
        if (isset($_POST[$key])) {
            unset($_POST[$key]);
        }
    }
    
    static public function drop_blank($except = [])
    {
        foreach ($_POST as $key => $input) {
            $_POST[$key] = ltrim(rtrim(trim($input)));
            if (empty($_POST[$key])) {
                if (!in_array($key, $except)) {
                    unset($_POST[$key]);
                }
            }
        }
        foreach ($_GET as $key => $input) {
            $_GET[$key] = ltrim(rtrim(trim($input)));
            if (empty($_GET[$key])) {
                unset($_GET[$key]);
            }
        }
    }
    
    static public function is_empty($key)
    {
        if (isset($_POST[$key]) && empty($_POST[$key]) || !isset($_POST[$key])) {
            return true;
        } else {
            return false;
        }
    }
    
    static public function file($key)
    {
        if (isset($_FILES["$key"])) {
            return $_FILES["$key"];
        }
    }
    
    static public function parse_str($post)
    {
        parse_str($post, $arr);
        return $arr;
    }
    
    static public function parse2int()
    {
        $post = $_POST;
        foreach ($post as $key => $input) {
            $_POST[$key] = intval($input);
        }
        return $_POST;
    }
    
    static public function parse2Obj($post)
    {
        parse_str($post, $arr);
        return (object)$arr;
    }
    
    static public function parse_get2post($post)
    { //utilizado para enviar serializado do ajax para query_build
        $_POST = Req::parse_str(Req::get('dados'));
    }
    
    static public function ajax2post($key)
    {
        Req::parse2post(Req::parse(Req::get("$key")));
        Req::removeIndex("$key");
        return $_POST;
    }
    
    static public function get_and_drop($key)
    {
        $str = "";
        if (isset($_POST[$key])) {
            $str = ltrim(rtrim(trim($_POST[$key])));
            if (isset($_POST[$key])) {
                unset($_POST[$key]);
            }
        }
        return $str;
    }
    
    static public function open_url($param = array())
    {
        try {
            if (empty($param)) {
                throw new Exception('openUrl: Array de parâmetros vazio!');
            } else {
                if (isset($param['method'])) {
                    $method = strtoupper($param['method']);
                } else {
                    throw new Exception('openUrl: Parâmetro method deve ser informado no array de parâmetros!');
                }
                if ($method == 'C') {
                    $url = $param['url'];
                    $buffer = "";
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    $buffer = trim(curl_exec($ch));
                    if (curl_errno($ch)) {
                        throw new Exception('Curl error: ' . curl_error($ch));
                    } else {
                        return $buffer;
                    }
                    curl_close($ch);
                } elseif ($method == 'F') {
                    $url = $param['url'];
                    $line = "";
                    $buffer = "";
                    $handle = @fopen("$url", "r");
                    if ($handle) {
                        while (!feof($handle)) {
                            $line = trim(@fgets($handle, 4096));
                            if (isset($param['return']) && $param['return'] == 'array') {
                                $buffer[] = explode(",", $line);
                            } else {
                                $buffer .= $line . "\n";
                            }
                        }
                        fclose($handle);
                        return $buffer;
                    }
                } elseif ($method == 'FC') {
                    $url = $param['url'];
                    $buffer = trim(@file_get_contents($url, 0, null));
                    return $buffer;
                }
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }
}
