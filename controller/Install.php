<?php

class Install
{

    public function __construct($is_callable = false)
    {
        if(!$is_callable) {
            die();
        }
    }
    public function up()
    {
        $this->config_create();
        $this->admin_create();
        $this->jurado_create();
        $this->criterio_create();
        $this->fintech_create();
        $this->fintech_finalista_create();
        $this->voto_create();
    }

    public function reset()
    {
        // $this->criterio_create();
        // $this->jurado_create();
        // $this->fintech_create();
        // $this->fintech_finalista_create();
        $this->voto_create();
    }

    public function config_create()
    {
        $columns = [
            ['name' => 'id', 'type' => 'int(11)', 'key' => true],
            ['name' => 'site_status', 'type' => 'int(1)'],
            ['name' => 'site_label_desativado', 'type' => 'text'],
            ['name' => 'qtd_fintechs_segunda_etapa', 'type' => 'int(11)'],
            ['name' => 'etapa_atual', 'type' => 'int(11)'],
        ];
        $with = [
            'site_status' => 1,
            'site_label_desativado' => 'Ambiente desativado!',
            "qtd_fintechs_segunda_etapa" => 12,
            "etapa_atual" => 1,
        ];
        (new DB)->drop_table('config');
        (new DB)->create_table('config', $columns);
        (new Factory('config'))->with($with)->save();
    }

    public function admin_create()
    {
        $columns = [
            ['name' => 'id', 'type' => 'int(11)', 'key' => true],
            ['name' => 'login', 'type' => 'varchar(255)'],
            ['name' => 'senha', 'type' => 'varchar(255)'],
            ['name' => 'nome', 'type' => 'varchar(255)'],
            ['name' => 'permissao', 'type' => 'int'],
        ];
        (new DB)->create_table('admin', $columns);

        $with = [
            'login' => 'admin',
            'senha' => md5('admin'),
            'nome' => 'Administrador 01',
            'permissao' => 1
        ];
        (new Factory('admin'))->with($with)->save();
    }

    public function jurado_create()
    {
        $columns = [
            ['name' => 'id', 'type' => 'int(11)', 'key' => true],
            ['name' => 'nome', 'type' => 'varchar(255)'],
            ['name' => 'login', 'type' => 'varchar(255)'],
            ['name' => 'senha', 'type' => 'varchar(255)'],
        ];
        (new DB)->drop_table('jurado');
        (new DB)->create_table('jurado', $columns);
    }

    public function criterio_create()
    {
        $columns = [
            ['name' => 'id', 'type' => 'int(11)', 'key' => true],
            ['name' => 'nome', 'type' => 'text'],
            ['name' => 'descricao', 'type' => 'text'],
        ];
        (new DB)->drop_table('criterio');
        (new DB)->create_table('criterio', $columns);
    }

    public function fintech_create()
    {
        $columns = [
            ['name' => 'id', 'type' => 'int(11)', 'key' => true],
            ['name' => 'nome', 'type' => 'text'],
            ['name' => 'descricao', 'type' => 'text'],
            ['name' => 'ordem', 'type' => 'int(11)'],
        ];
        (new DB)->drop_table('fintech');
        (new DB)->create_table('fintech', $columns);
    }

    public function fintech_finalista_create()
    {
        $columns = [
            ['name' => 'id', 'type' => 'int(11)', 'key' => true],
            ['name' => 'id_anterior', 'type' => 'int(11)'],
            ['name' => 'nome', 'type' => 'text'],
            ['name' => 'descricao', 'type' => 'text'],
            ['name' => 'ordem', 'type' => 'int(11)'],
        ];
        (new DB)->drop_table('fintech_finalista');
        (new DB)->create_table('fintech_finalista', $columns);
    }

    public function voto_create()
    {
        $columns = [
            ['name' => 'id', 'type' => 'int(11)', 'key' => true],
            ['name' => 'jurado_id', 'type' => 'int(11)'],
            ['name' => 'fintech_id', 'type' => 'int(11)'],
            ['name' => 'criterio_id', 'type' => 'int(11)'],
            ['name' => 'nota', 'type' => 'decimal(10,2)'],
            ['name' => 'etapa', 'type' => 'int(1)', 'default' => '1'],
            ['name' => 'comentario', 'type' => 'text'],
        ];
        (new DB)->drop_table('voto');
        (new DB)->create_table('voto', $columns);
    }

}
