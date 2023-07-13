<?php

class Router
{

    public $uri = array();
    public $controller;
    public $action;
    public $baseuri;
    public $idxbase = 0;
    public $registry;
    public $db;

    public function __construct()
    {
        $this->uri = filter_input(INPUT_GET, 'rota', FILTER_SANITIZE_SPECIAL_CHARS);
        $this->uri = explode("/", $this->uri);
    }

    public function controller()
    {
        $this->controller = (!isset($this->uri[$this->idxbase]) || $this->uri[$this->idxbase] == NULL) ? 'Index' : $this->uri[$this->idxbase];
        return (is_string($this->controller)) ? $this->controller : 'Index';
    }

    public function action()
    {
        $this->action = (
            isset($this->uri[$this->idxbase + 1]) && strlen($this->uri[$this->idxbase + 1]) != 0 && is_string($this->uri[$this->idxbase + 1])
        ) ? $this->uri[$this->idxbase + 1] : 'indexAction';
        return $this->action;
    }

}
