<?php

class Admin
{
    public function __construct()
    {
        @session_start();
    }

    public function check()
    {
        if (!Session::check() || !Session::node('uid')) {
            Session::destroy();
            Http::redirect_to('/admin/login');
        }
    }

    public function login()
    {
        $data = [
            'mapper' => []
        ];
        Tpl::view('admin.pages.login.index', $data, 1);
    }

    public function __addUser()
    {
        Http::redirect_to('/');
        $with = [
            'login' => 'admin',
            'senha' => md5('admin')
        ];
        (new Factory('admin'))->with($with)->save();
        echo 'Usuário salvo';
    }

    public function indexAction()
    {
        $this->check();
        $config = (new Factory('config'))->find(1);

        $fintechs_1 = $this->getMelhoresFintechsFull();
        $fintechs_2 = $this->getMelhoresFintechsFull2();
        $data = [
            'config' => $config,
            'fintechs_1' => $fintechs_1,
            'fintechs_2' => $fintechs_2,
            'mapper' => ['config']
        ];
        Tpl::view('admin.pages.dashboard.index', $data, 1);
    }

    public function set_config()
    {
        $this->check();
        $dados = Filter::parse_full($_POST);
        $dados['config_id'] = 1;
        (new Factory('config'))->with($dados)->save();
        Http::redirect_to('/admin/?success');
    }

    public function clear()
    {
        $this->check();

        $key = Req::post('key', 'int');
        $pass = Req::post('pass', 'string');
        if ($key < 1 || empty($pass)) {
            Http::redirect_to('/admin/?error');
        }
        $where = "admin_id = " . Session::node('uid') . ' AND admin_senha = "' . md5($pass) . '"';
        $verify = (new Factory('admin'))->where($where)->get();
        if (!isset($verify[0])) {
            Http::redirect_to('/admin/?error');
        }

        $this->limpa_bd(1);
        Http::redirect_to('/admin/?success');
    }

    public function limpa_bd($is_callable = false)
    {
        if ($is_callable) {
            (new Install(1))->reset();
        }
    }

    public function etapa_2()
    {
        $this->check();

        $key = Req::post('key', 'int');
        $pass = Req::post('pass', 'string');
        if ($key < 1 || empty($pass)) {
            Http::redirect_to('/admin/?error');
        }
        $where = "admin_id = " . Session::node('uid') . ' AND admin_senha = "' . md5($pass) . '"';
        $verify = (new Factory('admin'))->where($where)->get();
        if (!isset($verify[0])) {
            Http::redirect_to('/admin/?error');
        }

        $with = [
            "id" => 1,
            "etapa_atual" => 2
        ];
        (new Factory('config'))
            ->with($with)
            ->save();

        $this->goToEtapa2();
        Http::redirect_to("/admin/?success");
    }

    public function etapa_1()
    {
        $this->check();

        $key = Req::post('key', 'int');
        $pass = Req::post('pass', 'string');
        if ($key < 1 || empty($pass)) {
            Http::redirect_to('/admin/?error');
        }
        $where = "admin_id = " . Session::node('uid') . ' AND admin_senha = "' . md5($pass) . '"';
        $verify = (new Factory('admin'))->where($where)->get();
        if (!isset($verify[0])) {
            Http::redirect_to('/admin/?error');
        }

        $with = [
            "id" => 1,
            "etapa_atual" => 1
        ];
        (new Factory('config'))
            ->with($with)
            ->save();

        // limpa a etapa 2
        (new Install(1))->fintech_finalista_create();
        (new Factory("voto"))->where("voto_etapa = 2")->drop();

        Http::redirect_to("/admin/?success");
    }

    public function goToEtapa2()
    {
        $this->check();

        $melhores_fintechs = $this->getMelhoresFintechs();

        // insere na tabela das finalistas
        if (isset($melhores_fintechs[0])) {
            foreach ($melhores_fintechs as $fintech) {
                $with = [
                    "id_anterior" => $fintech->fintech_id,
                    "nome" => $fintech->fintech_nome,
                    "descricao" => $fintech->fintech_descricao,
                    "ordem" => $fintech->fintech_ordem,
                ];
                (new Factory("fintech_finalista"))->with($with)->save();
            }
        }
    }

    public function getMelhoresFintechs($etapa = 1)
    {
        $this->check();

        $config = (new Factory('config'))->find(1);
        $qtd_fintechs_vencedoras = $config->config_qtd_fintechs_segunda_etapa;

        $fintechs = (new Factory('fintech'))->get();

        // ordenação
        $medias = [];

        if (isset($fintechs[0])) {
            foreach ($fintechs as $k => $v) {
                $qtd_votos_total = (new Factory('voto'))->where("voto_etapa = {$etapa} AND voto_fintech_id = {$v->fintech_id}")->get();
                if (is_array($qtd_votos_total)) {
                    $qtd_votos_total = sizeof($qtd_votos_total);
                } else {
                    $qtd_votos_total = 0;
                }
                $media_total = (new Factory('voto'))->select("SUM(voto_nota) / {$qtd_votos_total} AS media_total")->where("voto_etapa = {$etapa} AND voto_fintech_id = {$v->fintech_id}")->get();
                if (isset($media_total[0])) {
                    $media_total = $media_total[0]->media_total;
                } else {
                    $media_total = 0;
                }
                $fintechs[$k]->media_total = $media_total;

                // ordenação
                $medias[$k] = $media_total;
            }

            // realiza de fato a ordenação
            array_multisort($medias, SORT_DESC, $fintechs);

            $melhores_fintechs = [];
            $qtd_inseridas = 0;
            foreach ($fintechs as $fintech) {
                if ($qtd_inseridas < $qtd_fintechs_vencedoras) {
                    $melhores_fintechs[] = $fintech;
                    $qtd_inseridas++;
                }
            }
            return $melhores_fintechs;
        }

        return [];
    }

    public function getMelhoresFintechsFull()
    {
        $etapa = 1;
        $this->check();

        $fintechs = (new Factory('fintech'))->get();

        // ordenação
        $medias = [];

        if (isset($fintechs[0])) {
            foreach ($fintechs as $k => $v) {
                $qtd_votos_total = (new Factory('voto'))->where("voto_etapa = {$etapa} AND voto_fintech_id = {$v->fintech_id}")->get();
                if (is_array($qtd_votos_total)) {
                    $qtd_votos_total = sizeof($qtd_votos_total);
                } else {
                    $qtd_votos_total = 0;
                }
                $media_total = (new Factory('voto'))->select("SUM(voto_nota) / {$qtd_votos_total} AS media_total")->where("voto_etapa = {$etapa} AND voto_fintech_id = {$v->fintech_id}")->get();
                if (isset($media_total[0])) {
                    $media_total = $media_total[0]->media_total;
                } else {
                    $media_total = 0;
                }
                $fintechs[$k]->media_total = $media_total;

                // ordenação
                $medias[$k] = $media_total;
            }

            // realiza de fato a ordenação
            array_multisort($medias, SORT_DESC, $fintechs);

            $melhores_fintechs = [];
            foreach ($fintechs as $fintech) {
                $melhores_fintechs[] = $fintech;
            }
            return $melhores_fintechs;
        }

        return [];
    }

    public function getMelhoresFintechsFull2()
    {
        $etapa = 2;
        $this->check();

        $fintechs = (new Factory('fintech_finalista'))->select("fintech_finalista_id AS fintech_id, fintech_finalista_nome AS fintech_nome, fintech_finalista_descricao AS fintech_descricao")->get();

        // ordenação
        $medias = [];

        if (isset($fintechs[0])) {
            foreach ($fintechs as $k => $v) {
                $qtd_votos_total = (new Factory('voto'))->where("voto_etapa = {$etapa} AND voto_fintech_id = {$v->fintech_id}")->get();
                if (is_array($qtd_votos_total)) {
                    $qtd_votos_total = sizeof($qtd_votos_total);
                } else {
                    $qtd_votos_total = 0;
                }
                $media_total = (new Factory('voto'))->select("SUM(voto_nota) / {$qtd_votos_total} AS media_total")->where("voto_etapa = {$etapa} AND voto_fintech_id = {$v->fintech_id}")->get();
                if (isset($media_total[0])) {
                    $media_total = $media_total[0]->media_total;
                } else {
                    $media_total = 0;
                }
                $fintechs[$k]->media_total = $media_total;

                // ordenação
                $medias[$k] = $media_total;
            }

            // realiza de fato a ordenação
            array_multisort($medias, SORT_DESC, $fintechs);

            $melhores_fintechs = [];
            foreach ($fintechs as $fintech) {
                $melhores_fintechs[] = $fintech;
            }
            return $melhores_fintechs;
        }

        return [];
    }

    // detalhes dos votos de uma fintech da 1 etapa
    public function detalhes_fintech_1()
    {
        $this->check();

        $id = Http::get_in_params('detalhes_fintech_1', 'int');
        if (!isset($id->value) || empty($id->value)) {
            Http::redirect_to('/admin/?error');
        }

        $id = $id->value;

        $fintech = (new Factory('fintech'))->find($id);
        if (!isset($fintech->fintech_id)) {
            Http::redirect_to('/admin/?error');
        }

        $criterios = (new Factory('criterio'))->get();
        if (!isset($criterios[0])) {
            Http::redirect_to('/admin/?error');
        }

        // calculo a média total
        $qtd_votos_total = (new Factory('voto'))->where("voto_etapa = 1 AND voto_fintech_id = {$fintech->fintech_id}")->get();        
        if (is_array($qtd_votos_total)) {
            $qtd_votos_total = sizeof($qtd_votos_total);
        } else {
            $qtd_votos_total = 0;
        }
        $media_total = (new Factory('voto'))->select("SUM(voto_nota) / {$qtd_votos_total} AS media_total")->where("voto_etapa = 1 AND voto_fintech_id = {$fintech->fintech_id}")->get();
        if (isset($media_total[0])) {
            $media_total = $media_total[0]->media_total;
        } else {
            $media_total = 0;
        }
        $fintech->media_total = $media_total;

        // calculo a média de cada critério
        foreach ($criterios as $k => $v) {
            $qtd_votos_do_criterio = (new Factory("voto"))->where("voto_etapa = 1 AND voto_criterio_id = {$v->criterio_id} AND voto_fintech_id = {$fintech->fintech_id}")->get();
            if (is_array($qtd_votos_do_criterio) && sizeof($qtd_votos_do_criterio) > 0) {
                $qtd_votos_do_criterio = sizeof($qtd_votos_do_criterio);
            } else {
                $qtd_votos_do_criterio = 0;
            }
            $criterios[$k]->criterio_media_fintech = (new Factory('voto'))
                ->select("SUM(voto_nota) / {$qtd_votos_do_criterio} AS media")
                ->where("voto_criterio_id = {$v->criterio_id} AND voto_etapa = 1 AND voto_fintech_id = {$fintech->fintech_id}")
                ->group_by("voto_criterio_id")
                ->get();
            if (isset($criterios[$k]->criterio_media_fintech[0])) {
                $criterios[$k]->criterio_media_fintech = $criterios[$k]->criterio_media_fintech[0]->media;
            }
        }
        $fintech->fintech_criterios = $criterios;

        // renderiza a página        
        $config = (new Factory('config'))->find(1);
        $data = [
            'config' => $config,
            'fintech' => $fintech,
            'etapa' => 1,
            'mapper' => ['config']
        ];
        Tpl::view('admin.pages.dashboard.detalhes', $data, 1);
    }

    // detalhes dos votos de uma fintech da 2 etapa
    public function detalhes_fintech_2()
    {
        $this->check();

        $id = Http::get_in_params('detalhes_fintech_2', 'int');
        if (!isset($id->value) || empty($id->value)) {
            Http::redirect_to('/admin/?error');
        }

        $id = $id->value;

        $fintech = (new Factory('fintech_finalista'))->select("fintech_finalista_id AS fintech_id, fintech_finalista_nome AS fintech_nome, fintech_finalista_descricao AS fintech_descricao")->find($id);
        if (!isset($fintech->fintech_id)) {
            Http::redirect_to('/admin/?error');
        }

        $criterios = (new Factory('criterio'))->get();
        if (!isset($criterios[0])) {
            Http::redirect_to('/admin/?error');
        }

        // calculo a média total
        $qtd_votos_total = (new Factory('voto'))->where("voto_etapa = 2 AND voto_fintech_id = {$fintech->fintech_id}")->get();        
        if (is_array($qtd_votos_total)) {
            $qtd_votos_total = sizeof($qtd_votos_total);
        } else {
            $qtd_votos_total = 0;
        }
        $media_total = (new Factory('voto'))->select("SUM(voto_nota) / {$qtd_votos_total} AS media_total")->where("voto_etapa = 2 AND voto_fintech_id = {$fintech->fintech_id}")->get();
        if (isset($media_total[0])) {
            $media_total = $media_total[0]->media_total;
        } else {
            $media_total = 0;
        }
        $fintech->media_total = $media_total;

        // calculo a média de cada critério
        foreach ($criterios as $k => $v) {
            $qtd_votos_do_criterio = (new Factory("voto"))->where("voto_etapa = 2 AND voto_criterio_id = {$v->criterio_id} AND voto_fintech_id = {$fintech->fintech_id}")->get();
            if (is_array($qtd_votos_do_criterio) && sizeof($qtd_votos_do_criterio) > 0) {
                $qtd_votos_do_criterio = sizeof($qtd_votos_do_criterio);
            } else {
                $qtd_votos_do_criterio = 0;
            }
            $criterios[$k]->criterio_media_fintech = (new Factory('voto'))
                ->select("SUM(voto_nota) / {$qtd_votos_do_criterio} AS media")
                ->where("voto_criterio_id = {$v->criterio_id} AND voto_etapa = 2 AND voto_fintech_id = {$fintech->fintech_id}")
                ->group_by("voto_criterio_id")
                ->get();
            if (isset($criterios[$k]->criterio_media_fintech[0])) {
                $criterios[$k]->criterio_media_fintech = $criterios[$k]->criterio_media_fintech[0]->media;
            }
        }
        $fintech->fintech_criterios = $criterios;

        // renderiza a página        
        $config = (new Factory('config'))->find(1);
        $data = [
            'config' => $config,
            'fintech' => $fintech,
            'etapa' => 2,
            'mapper' => ['config']
        ];
        Tpl::view('admin.pages.dashboard.detalhes', $data, 1);
    }
}
