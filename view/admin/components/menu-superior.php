<div class="row">
    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
        <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
            <a class="navbar-brand"><img src="${baseUri}/media/default/logo.png" alt="Logo Option Maker" width="30"> <strong>OptionMaker</strong></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <?php if (Session::node('uperms') == 1) : ?>
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a href="${baseUri}/admin" class="pointer menu-dashboard menu-link nav-link text-white ml-2 mb-2">
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="${baseUri}/jurados" class="pointer menu-jurados menu-link nav-link text-white ml-2 mb-2">
                                Jurados
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="${baseUri}/fintechs" class="pointer menu-fintechs menu-link nav-link text-white ml-2 mb-2">
                                Fintechs
                            </a>
                        </li>
                        <?php if ($data['config']->config_etapa_atual == 2) : ?>
                            <li class="nav-item">
                                <a href="${baseUri}/fintechs/finalistas" class="pointer menu-fintechs-2 menu-link nav-link text-white ml-2 mb-2">
                                    Fintechs finalistas
                                </a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a href="${baseUri}/criterios" class="pointer menu-criterios menu-link nav-link text-white ml-2 mb-2">
                                Criterios
                            </a>
                        </li>
                        <?php if (Session::node('uperms') == 1) : ?>
                            <li class="nav-item">
                                <a href="${baseUri}/usuarios" class="pointer menu-usuarios menu-link nav-link text-white ml-2 mb-2">
                                    Usuários
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                <?php endif; ?>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item ">
                        <a class="pointer nav-link">
                            &nbsp;&nbsp;
                            <i class="fa fa-check"></i>
                            Bem vindo, <?= Session::node('unome') ?>!
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="pointer nav-link text-danger" href="${baseUri}/auth/logout_admin">
                            <i class="fa fa-sign-out"></i> Sair
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>