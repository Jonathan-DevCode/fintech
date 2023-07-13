<?php

class Fintechs
{
    public function __construct()
    {
        @session_start();
        if (!Session::check() || !Session::node('uid')) {
            Session::destroy();
            Http::redirect_to('/login');
        }

        if (Session::node('uperms') != 1) {
            Http::redirect_to('/fintechs/?error');
        }
    }

    public function indexAction()
    {
        $config = (new Factory('config'))->find(1);
        
        $data = [
            'fintechs' => (new Factory('fintech'))->order("fintech_ordem")->get(),
            'config' => $config,
            'etapa' => 1,
            'mapper' => ['config']
        ];
        Tpl::view('admin.pages.fintechs.index', $data, 1);
    }

    public function find() {
        $id = Req::post('id', 'int');
        $etapa = Req::post('etapa', 'int');
        if($etapa == 1) {
            $fintech = (new Factory('fintech'))->where("fintech_id = {$id}")->get();          
            if(isset($fintech[0]))   {
                $fintech = $fintech[0];
            }
            echo json_encode($fintech);
        } else {
            $fintech = (new Factory('fintech_finalista'))->select("fintech_finalista_id AS fintech_id, fintech_finalista_nome AS fintech_nome, fintech_finalista_descricao AS fintech_descricao")->where("fintech_finalista_id = {$id}")->get();
            if(isset($fintech[0]))   {
                $fintech = $fintech[0];
            }
            echo json_encode($fintech);
        }
    }

    public function gravar() {
        $dados = $_POST;

        if(empty($dados['fintech_nome']) || empty($dados['fintech_descricao'])) {
            Http::redirect_to('/fintechs/?campos-obrigatorios');
        }

        $etapa = $dados['etapa'];
        unset($dados['etapa']);
        unset($dados['files']);
        if($etapa == 1) {
            (new Factory('fintech'))->with($dados)->save();
            Http::redirect_to('/fintechs/?success');
        } else {
            $with = [
                "nome" => $dados['fintech_nome'],
                "descricao" => $dados['fintech_descricao'],
                "ordem" => intval($dados['fintech_ordem']),
            ];
            if(!empty($dados['fintech_id'])) {
                $with["id"] = $dados['fintech_id'];
            }
            (new Factory('fintech_finalista'))->with($with)->save();
            Http::redirect_to('/fintechs/finalistas/?success');
        }
    }

    public function remove() {
        $id = Req::post("id", 'int');
        if(empty($id) || $id <= 0) {
            Http::redirect_to('/fintechs/?campos-obrigatorios');
        }

        $etapa = $_POST['etapa'];

        if($etapa == 1) {
            (new Factory('fintech'))->drop($id);
            Http::redirect_to('/fintechs/?success');
        } else {
            (new Factory('fintech_finalista'))->drop($id);
            Http::redirect_to('/fintechs/finalistas/?success');
        }
    }

    // finalistas
    public function finalistas()
    {
        $config = (new Factory('config'))->find(1);
        
        $data = [
            'fintechs' => (new Factory('fintech_finalista'))->select("fintech_finalista_id AS fintech_id, fintech_finalista_nome AS fintech_nome, fintech_finalista_descricao AS fintech_descricao")->get(),
            'config' => $config,
            'etapa' => 2,
            'is_finalista' => 1,
            'mapper' => ['config']
        ];
        Tpl::view('admin.pages.fintechs.index', $data, 1);
    }

}
