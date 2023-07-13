<?php

Class Page
{

    public function __construct()
    {

    }

    public function indexAction()
    {
    }

    public function _erro($Msg = '')
    {
        Tpl::view("erro.padrao", $Msg);
        return $this;
    }

    public function _required($key)
    {
        $Msg = "Campo [$key] requerido!";
        self::_erro($Msg);
    }

    public function _404()
    {
        Tpl::view("erro.404");
        return $this;
    }

    public function _action($Action)
    {
        Tpl::view("erro.action", "$Action");
        return $this;
    }

    public function _deny($Action)
    {
        Tpl::view("erro.deny", "$Action");
        return $this;
    }

    public function _perm($msg)
    {
        $data = [
            'msg' => ['msg' => $msg],
            'mapper' => ['msg']
        ];
        Tpl::view("erro.perm", $data);
        return $this;
    }

    public function _and_stop()
    {
        exit;
    }

}
