<?php

class Criterios
{
    public function __construct()
    {
        @session_start();
        if (!Session::check() || !Session::node('uid')) {
            Session::destroy();
            Http::redirect_to('/criterio/login');
        }

        if (Session::node('uperms') != 1) {
            Http::redirect_to('/criterio/?error');
        }
    }

    public function indexAction()
    {
        $config = (new Factory('config'))->find(1);
        $data = [
            'criterios' => (new Factory('criterio'))->get(),
            'config' => $config,            
            'mapper' => ['config']

        ];
        Tpl::view('admin.pages.criterios.index', $data, 1);
    }

    public function find() {
        $id = Req::post('id', 'int');
        echo json_encode((new Factory('criterio'))->find($id));        
    }

    public function gravar() {
        $dados = $_POST;

        if(empty($dados['criterio_nome']) || empty($dados['criterio_descricao'])) {
            Http::redirect_to('/criterios/?campos-obrigatorios');
        }

        unset($dados['files']);

        (new Factory('criterio'))->with($dados)->save();
        Http::redirect_to('/criterios/?success');
    }

    public function remove() {
        $id = Req::post("id", 'int');
        if(empty($id) || $id <= 0) {
            Http::redirect_to('/criterios/?campos-obrigatorios');
        }
        (new Factory('criterio'))->drop($id);
        Http::redirect_to('/criterios/?success');
    }
}
