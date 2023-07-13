<?php
/* Configuração de Rotas Alternativas da Aplicação */
/* ROTEAMENTO URL x Controller <-> Action */
$routes = [    
    "painel-jurado" => "Index:painel_jurado",
    "escolhas-jurado" => "Index:escolhas_jurado"
];

/* URLS IGNORADAS PELO LOADER/GETROUTE/REGISTRY */
$ignore = ["page"];

/*PATHS OPCIONAIS*/
$paths = ['fotos' => DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . 'foto'];
