<?php

class Avaliacao
{

    public function __construct()
    {
        @session_start();
    }

    public function avaliar()
    {
        Auth::validator();

        $fintech_id = Http::get_in_params("avaliar", 'int');

        if (!isset($fintech_id->value) || empty($fintech_id->value)) {
            Http::redirect_to('/painel-jurado/?error');
        }

        $jurado_id = ClientSession::node('uid');
        $fintech_id = $fintech_id->value;
        $config = (new Factory('config'))->find(1);
        $etapa = $config->config_etapa_atual;

        if ($etapa == 1) {
            $fintech = (new Factory('fintech'))                
                ->where("fintech_id = {$fintech_id} AND fintech_id NOT IN (SELECT voto_fintech_id FROM voto WHERE voto_jurado_id = {$jurado_id} AND voto_etapa = {$etapa})")
                ->get();
        } else {
            $fintech = (new Factory('fintech_finalista'))
                ->select("fintech_finalista_id AS fintech_id, fintech_finalista_nome AS fintech_nome, fintech_finalista_descricao AS fintech_descricao, 1 AS fintech_status")
                ->where("fintech_finalista_id = {$fintech_id} AND fintech_finalista_id NOT IN (SELECT voto_fintech_id FROM voto WHERE voto_jurado_id = {$jurado_id} AND voto_etapa = {$etapa})")                
                ->get();
        }

        if(!isset($fintech[0])) {
            Http::redirect_to('/painel-jurado/?error');
        }

        $fintech = $fintech[0];

        if(!isset($fintech->fintech_status) || $fintech->fintech_status == 0) {
            Http::redirect_to('/painel-jurado/?error');
        }

        $fintech_anterior_id = 0;
        $fintech_proxima_id = 0;

        // busca o id da fintech anterior
        if ($etapa == 1) {
            $fintech_anterior = (new Factory('fintech'))                
                ->where("fintech_id < {$fintech_id} AND fintech_id NOT IN (SELECT voto_fintech_id FROM voto WHERE voto_jurado_id = {$jurado_id} AND voto_etapa = {$etapa})")
                ->order("fintech_ordem DESC")
                ->get();
        } else {
            $fintech_anterior = (new Factory('fintech_finalista'))
                ->select("fintech_finalista_id AS fintech_id, fintech_finalista_nome AS fintech_nome, fintech_finalista_descricao AS fintech_descricao")
                ->where("fintech_finalista_id < {$fintech_id} AND fintech_finalista_id NOT IN (SELECT voto_fintech_id FROM voto WHERE voto_jurado_id = {$jurado_id} AND voto_etapa = {$etapa})")                                
                ->get();
        }

        if(isset($fintech_anterior[0])) {
            $fintech_anterior_id = $fintech_anterior[0]->fintech_id;
        }

        // busca o id da próxima fintech
        if ($etapa == 1) {
            $fintech_proxima = (new Factory('fintech'))                
                ->where("fintech_id > {$fintech_id} AND fintech_id NOT IN (SELECT voto_fintech_id FROM voto WHERE voto_jurado_id = {$jurado_id} AND voto_etapa = {$etapa})")
                ->order("fintech_ordem ASC")
                ->get();
        } else {
            $fintech_proxima = (new Factory('fintech_finalista'))
                ->select("fintech_finalista_id AS fintech_id, fintech_finalista_nome AS fintech_nome, fintech_finalista_descricao AS fintech_descricao")
                ->where("fintech_finalista_id > {$fintech_id} AND fintech_finalista_id NOT IN (SELECT voto_fintech_id FROM voto WHERE voto_jurado_id = {$jurado_id} AND voto_etapa = {$etapa})")                              
                ->get();
        }

        if(isset($fintech_proxima[0])) {
            $fintech_proxima_id = $fintech_proxima[0]->fintech_id;
        }

        $data = [
            'config' => $config,
            'fintech' => $fintech,
            'fintech_prev' => $fintech_anterior_id,
            'fintech_next' => $fintech_proxima_id,
            'mapper' => [],
        ];

        Tpl::view("tema.optionmaker.painel.avaliacao", $data);
    }

    // inicia a avaliação a partir da primeira avaliação pendente
    public static function iniciar()
    {
        Auth::validator();

        $jurado_id = ClientSession::node('uid');
        $config = (new Factory('config'))->find(1);
        $etapa = $config->config_etapa_atual;
        
        // busca o id da primeira fintech que precisa ser votada
        if ($etapa == 1) {
            $fintech = (new Factory('fintech'))                
                ->where("fintech_id NOT IN (SELECT voto_fintech_id FROM voto WHERE voto_jurado_id = {$jurado_id} AND voto_etapa = {$etapa})")
                ->order("fintech_ordem ASC")
                ->get();
        } else {
            $fintech = (new Factory('fintech_finalista'))
                ->select("fintech_finalista_id AS fintech_id, fintech_finalista_nome AS fintech_nome, fintech_finalista_descricao AS fintech_descricao")
                ->where("fintech_finalista_id NOT IN (SELECT voto_fintech_id FROM voto WHERE voto_jurado_id = {$jurado_id} AND voto_etapa = {$etapa})")                                
                ->get();
        }

        if(!isset($fintech[0])) {
            Http::redirect_to("/painel-jurado/?error");
        }

        Http::redirect_to("/avaliacao/avaliar/{$fintech[0]->fintech_id}");

    }

    public function get_criterios() {

        $criterios = (new Factory("criterio"))->get();

        echo json_encode(['status' => 200, 'criterios' => $criterios]);
        exit;
    }

    public function setar_nota() {

        $jurado_id = ClientSession::node('uid');
        $fintech_id = Req::post('fintech_id', 'int');
        $notas = Req::post('notas');
        $comentario = Req::post('comentario', 'string');
        
        if(intval($jurado_id) <= 0) {
            echo json_encode(['status' => 401, 'msg' => 'Jurado ID inválido']);
            exit;
        }

        if(intval($fintech_id) <= 0) {
            echo json_encode(['status' => 401, 'msg' => 'Fintech ID inválido']);
            exit;
        }

        if(!is_array($notas) || sizeof($notas) <= 0) {
            echo json_encode(['status' => 401, 'msg' => 'Nenhuma nota foi enviada']);
            exit;
        }
        $config = (new Factory('config'))->find(1);
        $etapa = $config->config_etapa_atual;

        // verifica se ele já votou nessa fintech
        $verify = (new Factory('voto'))->where("voto_jurado_id = {$jurado_id} AND voto_etapa = {$etapa} AND voto_fintech_id = {$fintech_id}")->get();
        if(isset($verify[0])) {
            echo json_encode(['status' => 401, 'msg' => 'Seu voto nessa fintech já foi contabilizado!', 'verify' => $verify]);
            exit;
        }

        // salva as notas
        foreach($notas as $nota) {
            $nota = (object) $nota;
            $with = [
                "jurado_id" => $jurado_id,
                "fintech_id" => $fintech_id,
                "criterio_id" => $nota->criterio_id,
                "nota" => $nota->nota,
                "etapa" => $etapa,
                "comentario" => $comentario,
            ];
            (new Factory('voto'))->with($with)->save();
        }

        // verifica qual a próxima fintech
        // $ordem_atual = (new Factory("fintech"))->find($fintech_id);
        // $ordem_atual = intval($ordem_atual->fintech_ordem);
        // $proxima_fintech_id = 0;
        // if($etapa == 1) {
        //     $proxima_fintech = (new Factory("fintech"))->select("fintech_id")->where("fintech_ordem > {$ordem_atual}")->order("fintech_ordem")->get();
        // } else {
        //     $proxima_fintech = (new Factory("fintech_finalista"))->select("fintech_finalista_id AS fintech_id")->where("fintech_finalista_id > {$fintech_id}")->get();
        // }
        // if(isset($proxima_fintech[0])) {
        //     $proxima_fintech_id = $proxima_fintech[0]->fintech_id;
        // }

        echo json_encode(['status' => 200]);
        exit;


    }
}
