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
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 mt-4">
                <h5><b>Veja suas escolhas</b></h5>
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
                                <a class="btn btn-white btn-radius" onclick="$('#modal-notas-<?= $fintech->fintech_id ?>').modal('show')"><i class="fa fa-eye"></i> Ver notas</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 col-sm-12">
                    <p>Você ainda não atribuiu uma nota nessa etapa.</p>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <?php if (isset($data['fintechs'][0])) : ?>
        <?php foreach ($data['fintechs'] as $fintech) : ?>
            <div class="modal fade" id="modal-notas-<?= $fintech->fintech_id ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <br>
                                    <h6 class="text-center">Visualize as notas que você deu para a fintech <b><?= $fintech->fintech_nome ?></b></h6>

                                    <div class="row">
                                        <?php if($fintech->votos[0]): ?>
                                            <?php foreach($fintech->votos as $voto): ?>
                                                <div class="col-12 col-sm-12 mb-1">
                                                    <b><?= $voto->criterio_nome ?></b>
                                                    <br>
                                                    Nota: <?= $voto->voto_nota ?>
                                                    <hr>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>

                                    <p><b>Média da fintech</b>: <span><?= $fintech->fintech_media ? $fintech->fintech_media : '0' ?></span></p>
                                    <br>
                                    <button class="btn btn-blue btn-block" data-dismiss="modal">Fechar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <script src="${baseUri}/view/tema/optionmaker/assets/js/jquery.js"></script>
    <script src="${baseUri}/view/tema/optionmaker/assets/js/bootstrap.min.js"></script>
    <script src="${baseUri}/view/tema/optionmaker/assets/js/mask.js"></script>
    <script src="${baseUri}/view/tema/optionmaker/assets/js/toast.js"></script>
    <script src="${baseUri}/view/tema/optionmaker/assets/js/main.js"></script>

    <script>
        $(".menu-escolhas-<?= $data['etapa'] ?>").addClass("active");
    </script>

</body>

</html>