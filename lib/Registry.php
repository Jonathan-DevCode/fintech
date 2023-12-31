<?php

class Registry
{

    private $registry = array();
    private static $instance = null;

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Registry();
        }
        return self::$instance;
    }

    public function set($key, $value)
    {
        if (isset($this->registry[$key])) {
            throw new Exception("There is already an entry for key " . $key);
        }
        $this->registry[$key] = $value;
    }

    public function get($key)
    {
        if (!isset($this->registry[$key])) {
            return false;
            //throw new Exception("There is no entry for key " . $key);
        }
        return $this->registry[$key];
    }
}