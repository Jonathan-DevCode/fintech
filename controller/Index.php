<?php

class Index
{

    public function __construct()
    {
        @session_start();
    }

    public function indexAction()
    {
        $config = (new Factory('config'))->find(1);
        $data = [
            'config' => $config,
            'mapper' => [],
        ];

        Tpl::view("tema.optionmaker.home.login", $data);
    }

    public function painel_jurado()
    {
        Auth::validator();

        $config = (new Factory('config'))->find(1);
        $jurado_id = ClientSession::node('uid');
        $etapa = $config->config_etapa_atual;

         // busca o id da primeira fintech que precisa ser votada
         if ($etapa == 1) {
            $fintech = (new Factory('fintech'))
                ->where("fintech_id NOT IN (SELECT voto_fintech_id FROM voto WHERE voto_jurado_id = {$jurado_id} AND voto_etapa = {$etapa})")
                ->order("fintech_ordem")
                ->get();
        } else {
            $fintech = (new Factory('fintech_finalista'))
                ->select("fintech_finalista_id AS fintech_id, fintech_finalista_nome AS fintech_nome, fintech_finalista_descricao AS fintech_descricao, 1 AS fintech_status")
                ->where("fintech_finalista_id NOT IN (SELECT voto_fintech_id FROM voto WHERE voto_jurado_id = {$jurado_id} AND voto_etapa = {$etapa})")
                ->order("fintech_finalista_nome DESC")
                ->get();
        }

        $hasNextFintech = false;
        if(isset($fintech[0])) {
            $hasNextFintech = true;
        }

        $data = [
            'config' => $config,
            'hasNextFintech' => $hasNextFintech,
            'fintechs' => self::get_fintech($config->config_etapa_atual),
            'mapper' => [],
        ];

        Tpl::view("tema.optionmaker.painel.lista-fintechs", $data);
    }

    public static function get_fintech($etapa) {
        $fintechs = null;
        $jurado_id = ClientSession::node('uid');
        if($etapa == 1) {
            $fintechs = (new Factory('fintech'))
                ->select("*, (SELECT COUNT(*) FROM voto WHERE voto_jurado_id = {$jurado_id} AND voto_fintech_id = fintech_id AND voto_etapa = {$etapa}) AS qtd_voto")
                ->order("fintech_nome ASC")
                ->get();
        } else {
            $fintechs = (new Factory('fintech_finalista'))
                ->select("fintech_finalista_id AS fintech_id, fintech_finalista_nome AS fintech_nome, fintech_finalista_descricao AS fintech_descricao, 1 AS fintech_status,
                    (SELECT COUNT(*) FROM voto WHERE voto_jurado_id = {$jurado_id} AND voto_fintech_id = fintech_id AND voto_etapa = {$etapa}) AS qtd_voto")
                ->order("fintech_finalista_nome ASC")
                ->get();
        }
        return $fintechs;
    }

    // mostra as escolhas dos jurados
    public function escolhas_jurado() {
        Auth::validator();

        $etapa = Http::get_in_params("escolhas-jurado", 'int');
        $jurado_id = ClientSession::node('uid');

        if ($etapa->value == 1) {
            $fintechs = (new Factory('fintech'))
                ->where("fintech_id IN (SELECT voto_fintech_id FROM voto WHERE voto_jurado_id = {$jurado_id} AND voto_etapa = {$etapa->value})")
                ->order("fintech_ordem ASC")
                ->get();
        } else {
            $fintechs = (new Factory('fintech_finalista'))
                ->select("fintech_finalista_id AS fintech_id, fintech_finalista_nome AS fintech_nome, fintech_finalista_descricao AS fintech_descricao, 1 AS fintech_status")
                ->where("fintech_finalista_id IN (SELECT voto_fintech_id FROM voto WHERE voto_jurado_id = {$jurado_id} AND voto_etapa = {$etapa->value})")
                ->order("fintech_finalista_nome ASC")
                ->get();
        }

        if(isset($fintechs[0])) {
            $qtd_criterios = count((new Factory("criterio"))->get());
            foreach($fintechs as $k => $v) {
                $fintechs[$k]->fintech_media = 0;
                $fintechs[$k]->votos = (new Factory('voto'))
                    ->join("criterio", "criterio_id = voto_criterio_id")
                    ->where("voto_fintech_id = {$v->fintech_id} AND voto_jurado_id = {$jurado_id}")
                    ->get();

                // calcula a média das fintechs
                if(isset($fintechs[$k]->votos[0])) {
                    $soma = 0;
                    foreach($fintechs[$k]->votos as $voto) {
                        $soma += $voto->voto_nota;
                    }

                    $fintechs[$k]->fintech_media = $soma / $qtd_criterios;
                }
            }
        }


        $config = (new Factory('config'))->find(1);
        $data = [
            'config' => $config,
            'fintechs' => $fintechs,
            'etapa' => $etapa->value,
            'mapper' => [],
        ];

        Tpl::view("tema.optionmaker.painel.escolhas", $data);

    }

}
