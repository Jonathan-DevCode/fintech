<?php

class Usuarios
{
    public function __construct()
    {
        @session_start();
        if (!Session::check() || !Session::node('uid')) {
            Session::destroy();
            Http::redirect_to('/admin/login');
        }

        if (Session::node('uperms') != 1) {
            Http::redirect_to('/admin/?error');
        }
    }

    public function indexAction()
    {
        $config = (new Factory('config'))->find(1);
        $data = [
            'usuarios' => (new Factory('admin'))->select("admin_nome, admin_id, admin_permissao, admin_login")->get(),
            'config' => $config,
            'mapper' => ['config']
        ];
        Tpl::view('admin.pages.usuarios.index', $data, 1);
    }

    public function gravar() {
        $dados = Filter::parse_full($_POST);
        if(isset($dados['admin_senha']) && !empty($dados['admin_senha'])) {
            $dados['admin_senha'] = md5($dados['admin_senha']);
        } else {
            unset($dados['admin_senha']);
        }
        if(empty($dados['admin_nome']) || empty($dados['admin_login']) || empty($dados['admin_permissao'])) {
            Http::redirect_to('/usuarios/?campos-obrigatorios');
        }

        (new Factory('admin'))->with($dados)->save();
        Http::redirect_to('/usuarios/?success');
    }

    public function remove() {
        $id = Req::post("id", 'int');
        if(empty($id) || $id <= 0) {
            Http::redirect_to('/usuarios/?campos-obrigatorios');
        }
        (new Factory('admin'))->drop($id);
        Http::redirect_to('/usuarios/?success');
    }
}
