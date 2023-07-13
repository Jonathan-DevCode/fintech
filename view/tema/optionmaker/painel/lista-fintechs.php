<!DOCTYPE html>
<html lang="pt-br">

<head>
    <title>Central do cliente</title>
    <!-- Stylesheets -->
    <link href="${baseUri}/view/tema/optionmaker/assets/css/bootstrap.css" rel="stylesheet">
    <link href="${baseUri}/view/tema/optionmaker/assets/css/fontawesome-all.css" rel="stylesheet">
    <link href="${baseUri}/view/tema/optionmaker/assets/css/style.css" rel="stylesheet">
    <link href="${baseUri}/view/tema/optionmaker/assets/css/toast.css" rel="stylesheet">
    <link href="${baseUri}/view/tema/optionmaker/assets/css/custom_front.css" rel="stylesheet">
    <!-- Responsive Settings -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <!--[if lt IE 9]><script src="https:/cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script><![endif]-->
    <!--[if lt IE 9]><script src="js/respond.js"></script><![endif]-->
</head>

<body>
    @(tema.optionmaker.components.menu-superior)
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-8 col-lg-8 mt-4">
                <h5><b>Lista de Fintechs</b></h5>
            </div>
            <div class="col-12 col-sm-12 col-md-4 col-lg-4 mt-4">
                <?php if ($data['hasNextFintech']) : ?>
                    <!-- <a class="btn btn-iniciar-avaliacao" href="${baseUri}/avaliacao/iniciar">Iniciar avaliação</a> -->
                <?php endif; ?>
            </div>
        </div>

        <div class="row">
            <?php if (isset($data['fintechs'][0])) : ?>
                <?php foreach ($data['fintechs'] as $fintech) : ?>
                    <div class="col-12 col-sm-12">
                        <div class="card-fintech">
                            <div class="fintech-icon-section">
                                <div class="fintech-icon">
                                    <i class="fa fa-users"></i>
                                </div>
                            </div>
                            <div class="fintech-info">
                                <div class="fintech-title"><?= $fintech->fintech_nome ?></div>
                                <div class="fintech-desc"><?= $fintech->fintech_descricao ?></div>
                            </div>
                            <div class="fintech-action">
                                <?php if ($fintech->qtd_voto > 0) : ?>
                                    <a class="btn btn-avaliado btn-white"><i class="fa fa-check"></i> Avaliado</a>
                                <?php else : ?>
                                    <a class="btn btn-iniciar-avaliacao" <?php if ($fintech->fintech_status == 1) : ?> href="${baseUri}/avaliacao/avaliar/<?= $fintech->fintech_id ?>" <?php else : ?>onclick="$('#modal-nao-concorre').modal('show')" <?php endif; ?>>Avaliar</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

        </div>
    </div>

    <div class="modal fade" id="modal-nao-concorre" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <i class="fa fa-exclamation-triangle fa-3x text-warning" aria-hidden="true"></i>
                            <br>
                            <h4 class="text-warning text-center">Atenção</h4>
                            <p>
                                Essa fintech não está concorrendo para a avaliação.
                            </p>
                            <button class="btn btn-secondary btn-block" data-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="${baseUri}/view/tema/optionmaker/assets/js/jquery.js"></script>
    <script src="${baseUri}/view/tema/optionmaker/assets/js/bootstrap.min.js"></script>
    <script src="${baseUri}/view/tema/optionmaker/assets/js/mask.js"></script>
    <script src="${baseUri}/view/tema/optionmaker/assets/js/toast.js"></script>
    <script src="${baseUri}/view/tema/optionmaker/assets/js/main.js"></script>

    <script>
        $(".menu-painel").addClass("active");

        // if (window.location.href.indexOf("success") != -1) {
        //     $.toast("Ação realizada com sucesso!");
        // } else if (window.location.href.indexOf("error") != -1) {
        //     $.toast("Não foi possível realizar essa ação!");
        // }
    </script>

</body>

</html>