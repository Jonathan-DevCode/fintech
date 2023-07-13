<?php

class Auth
{

    public function __construct()
    {
    }

    public function indexAction()
    {
    }

    public function jurado()
    {
        $login = Req::post('login', 'string');
        $senha = Req::post('senha', 'string');

        if (empty($login) || empty($senha)) {
            echo json_encode(['status' => 401]);
            exit;
        }

        $senha = md5($senha);

        $jurado = (new Factory('jurado'))->where("jurado_login = '{$login}' AND jurado_senha = '{$senha}'")->get();
        if(!isset($jurado[0])) {
            echo json_encode(['status' => 404]);
            exit;
        }
        $jurado = $jurado[0];

        ClientSession::init();
        ClientSession::node('uid', $jurado->jurado_id);
        ClientSession::node('unome', $jurado->jurado_nome);
        ClientSession::node('uip', (new Browser)->get_ip());

        Browser::cookie('token_login', base64_encode("pirilim:" . ClientSession::node('uid')));

        echo json_encode(['status' => 200]);
        exit;
    }

    public function admin()
    {
        $login = Req::post('login', 'string');
        $senha = Req::post('senha', 'string');

        if (empty($login) || empty($senha)) {
            Http::redirect_to('/admin/login/?campos-obrigatorios');
        }

        $senha = md5($senha);
        $user = (new Factory('admin'))->where("admin_login = '$login' AND admin_senha = '$senha'")->get();

        if (!isset($user[0])) {
            Http::redirect_to('/admin/login/?login-inexistente');
        }
        $u = $user[0];
        Session::init();
        Session::node('uid', $u->admin_id);
        Session::node('unome', $u->admin_nome);
        Session::node('uperms', $u->admin_permissao);
        Session::node('uip', (new Browser)->get_ip());
        Http::redirect_to('/admin');
    }

    public function logout_admin()
    {
        Session::destroy();
        Http::redirect_to('/admin');
    }

    public function logout()
    {
        Session::destroy();
        Browser::cookie('token_login', 'drop');
        Http::redirect_to('/');
    }

    static function validator()
    {
        if(!Session::check()) {
            Http::redirect_to('/');
        }

    }
}
