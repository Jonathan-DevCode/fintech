<?php

class Jurados
{
    public function __construct()
    {
        @session_start();
        if (!Session::check() || !Session::node('uid')) {
            Session::destroy();
            Http::redirect_to('/jurado/login');
        }

        if (Session::node('uperms') != 1) {
            Http::redirect_to('/jurado/?error');
        }
    }

    public function indexAction()
    {
        $config = (new Factory('config'))->find(1);
        $jurados = (new Factory('jurado'))->get();
        if(isset($jurados[0])) {

            if($config->config_etapa_atual == 1) {
                $fintechs = (new Factory('fintech'))->where("fintech_status = 1")->get();
            } else {
                $fintechs = (new Factory('fintech_finalista'))->select("fintech_finalista_id AS fintech_id")->get();
            }

            $qtd_fintechs_elegiveis = isset($fintechs[0]) ? sizeof($fintechs) : 0;

            foreach($jurados as $k => $v) {
                $qtd_votos = (new Factory('voto'))->where("voto_jurado_id = {$v->jurado_id} AND voto_etapa = {$config->config_etapa_atual}")->group_by("voto_fintech_id")->get();
                if(isset($qtd_votos[0])) {
                    $qtd_votos = sizeof($qtd_votos);
                } else {
                    $qtd_votos = 0;
                }

                // verifica se ele já votou em todas
                $jurados[$k]->jurado_votou_em_todas = true;
                if($qtd_votos < $qtd_fintechs_elegiveis) {
                    $jurados[$k]->jurado_votou_em_todas = false;
                }

                $jurados[$k]->jurado_string_votos = "{$qtd_votos}/{$qtd_fintechs_elegiveis}";
            }
        }
        $data = [
            'jurados' => $jurados,
            'config' => $config,
            'mapper' => ['config']
        ];
        Tpl::view('admin.pages.jurados.index', $data, 1);
    }

    public function gravar() {
        $dados = Filter::parse_full($_POST);
        if(isset($dados['jurado_senha']) && !empty($dados['jurado_senha'])) {
            $dados['jurado_senha'] = md5($dados['jurado_senha']);
        } else {
            unset($dados['jurado_senha']);
        }
        if(empty($dados['jurado_nome']) || empty($dados['jurado_login'])) {
            Http::redirect_to('/jurados/?campos-obrigatorios');
        }

        (new Factory('jurado'))->with($dados)->save();
        Http::redirect_to('/jurados/?success');
    }

    public function remove() {
        $id = Req::post("id", 'int');
        if(empty($id) || $id <= 0) {
            Http::redirect_to('/jurados/?campos-obrigatorios');
        }
        (new Factory('jurado'))->drop($id);
        (new Factory('voto'))->where("voto_jurado_id = {$id}")->drop();
        Http::redirect_to('/jurados/?success');
    }
}
