<?php

Class Response {

    static function post() {
        return (object) self::postAll();
    }

    static function get() {
        return (object) self::getAll();
    }

    static function postAll() {
        $sql_update = array();
        $sql_insert = array();
        foreach ($_POST as $key => $input) {
            $_POST[$key] = addslashes(trim($input));
            $sql_update[] = $key . " = '" . trim($_POST[$key]) . "'";
        }
        $sql_update = implode(', ', $sql_update);
        $sql_insert = "(`" . implode("`,`", array_keys($_POST)) . "`) VALUES "
                . "('" . implode("','", array_values($_POST)) . "')";
        return array(
            'fields' => array_keys($_POST),
            'values' => array_values($_POST),
            'sql_update' => $sql_update,
            'sql_insert' => $sql_insert,
            'sql_fields' => "(`" . implode("`,`", array_keys($_POST)) . "`)",
            'sql_values' => "('" . implode("','", array_values($_POST)) . "')",
            'obj' => (object) array_combine(array_keys($_POST), array_values($_POST)),
            'JSON' => json_encode( array_combine(array_keys($_POST), array_values($_POST)) ),
        );
    }

    static function getAll() {
        $sql_update = array();
        $sql_insert = array();
        foreach ($_GET as $key => $input) {
            $_GET[$key] = addslashes(trim($input));
            $sql_update[] = $key . " = '" . trim($_GET[$key]) . "'";
        }
        $sql_update = implode(', ', $sql_update);
        $sql_insert = "(`" . implode("`,`", array_keys($_GET)) . "`) VALUES "
                . "('" . implode("','", array_values($_GET)) . "')";
        return array(
            'fields' => array_keys($_GET),
            'values' => array_values($_GET),
            'sql_update' => $sql_update,
            'sql_insert' => $sql_insert,
            'sql_fields' => "(`" . implode("`,`", array_keys($_GET)) . "`)",
            'sql_values' => "('" . implode("','", array_values($_GET)) . "')",
            'obj' => (object) array_combine(array_keys($_GET), array_values($_GET)),
            'JSON' => json_encode( array_combine(array_keys($_GET), array_values($_GET)) ),
        );
    }

    static function query_build() {
        $obj = (object) Post::getAll();
        return $obj;
    }

    static function addIndex($key, $value) {
        $_POST[$key] = trim($value);
    }

    static function removeIndex($key) {
        if (isset($_POST[$key]))
            unset($_POST[$key]);
    }

    static function changeIndex($key, $value) {
        if (isset($_POST[$key]))
            $_POST[$key] = trim($value);
    }

    static function parseString($key) {
        if (isset($_POST[$key]))
            $_POST[$key] = addslashes(trim($_POST[$key]));
    }

    static function stripString($str) {
        return stripslashes(trim($str));
    }

    static function removeBlank($key) {
        foreach ($_POST as $key => $input) {
            $_POST[$key] = trim($input);
            if (empty($_POST[$key]))
                unset($_POST[$key]);
        }
    }

    static function getIndex($key) {
        if (isset($_POST[$key]))
            return ltrim(rtrim(trim($_POST[$key])));
    }

    static function file($key) {
        if (isset($_FILES["$key"]))
            return $_FILES["$key"];
    }

    static function parse($post) {
        parse_str($post, $arr);
        return $arr;
    }

    static function parse2Obj($post) {
        parse_str($post, $arr);
        return (object) $arr;
    }

    static function parse2post($post) {
        foreach ($post as $key => $input) {
            $_POST[$key] = @addslashes(trim($input));
        }
        return $_POST;
    }

    static function ajax2post($key) {
        Post::parse2post(Post::parse(Post::get("$key")));
        Post::removeIndex("$key");
        return $_POST;
    }

    static function getAndRemoveIndex($key) {
        $str = "";
        if (isset($_POST[$key])) {
            $str = ltrim(rtrim(trim($_POST[$key])));
            if (isset($_POST[$key])) {
                unset($_POST[$key]);
            }
        }
        return $str;
    }

}
