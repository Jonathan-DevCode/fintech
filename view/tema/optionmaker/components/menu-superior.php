<div class="row">
    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand">
                <img src="${baseUri}/media/default/febraban-logo.png" alt="Logo Option Maker" width="30">
                <strong>Febraban Tech</strong>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a href="${baseUri}/painel-jurado" class="pointer menu-painel menu-link nav-link ml-2 mb-2">
                            Fintechs
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="${baseUri}/escolhas-jurado/1" class="pointer menu-escolhas-1 menu-link nav-link ml-2 mb-2">
                            Suas escolhas (1ª etapa)
                        </a>
                    </li>
                    <?php if ($data['config']->config_etapa_atual == 2) : ?>
                        <li class="nav-item">
                            <a href="${baseUri}/escolhas-jurado/2" class="pointer menu-escolhas-2 menu-link nav-link ml-2 mb-2">
                                Suas escolhas (2ª etapa)
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item ">
                        <a class="pointer nav-link">
                            &nbsp;&nbsp;
                            <i class="fa fa-check"></i>
                            Bem vindo, <?= ClientSession::node('unome') ?>!
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="pointer nav-link" href="${baseUri}/auth/logout">
                            <i class="fa fa-sign-out"></i> Sair
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>