<!DOCTYPE html>
<html lang="pt-br">

<head>
    <title>Central do cliente</title>
    <!-- Stylesheets -->
    <link href="${baseUri}/view/tema/optionmaker/assets/css/bootstrap.css" rel="stylesheet">
    <link href="${baseUri}/view/tema/optionmaker/assets/css/fontawesome-all.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">
    <link href="${baseUri}/view/tema/optionmaker/assets/css/style.css" rel="stylesheet">
    <link href="${baseUri}/view/tema/optionmaker/assets/css/toast.css" rel="stylesheet">
    <link href="${baseUri}/view/tema/optionmaker/assets/css/custom.css" rel="stylesheet">
    <!-- Responsive Settings -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <!--[if lt IE 9]><script src="https:/cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script><![endif]-->
    <!--[if lt IE 9]><script src="js/respond.js"></script><![endif]-->
</head>

<body style="background-image: linear-gradient(to bottom right, rgb(255, 255, 255), rgb(200, 200, 200));min-height: 100vh">
    @(admin.components.menu-superior)

    <br><br><br>
    <div class="container">
        <!-- config -->
        <?php if (Session::node('uperms') == 1) : ?>
            <div class="row">
                <div class="col-sm-12 col-md-8">
                    <h4><b>Configurações</b></h4>
                    <hr>
                </div>
                <div class="col-sm-12 col-md-4 text-right">
                    <?php if ($data['config']->config_etapa_atual == 1) : ?>
                        <button class="btn btn-success btn-lg" onclick="$('#modal-2-etapa').modal('show')">Iniciar 2ª etapa</button>
                    <?php else : ?>
                        <button class="btn btn-primary btn-lg" onclick="$('#modal-1-etapa').modal('show')">Voltar para 1ª etapa</button>
                    <?php endif; ?>
                </div>
            </div>
            <form action="${baseUri}/admin/set_config" method="POST">
                <div class="row">
                    <div class="col-12 col-sm-12 col-xs-12 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label for="config_site_status">Status do site</label>
                            <select name="config_site_status" id="config_site_status" class="form-control">
                                <option value="1">Ativo</option>
                                <option value="2">Inativo</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-xs-12 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label for="config_qtd_fintechs_segunda_etapa">Qtd Fintechs vencedoras (1ª etapa)</label>
                            <input type="text" class="form-control" value="${config_qtd_fintechs_segunda_etapa}" name="config_qtd_fintechs_segunda_etapa">
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-xs-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label for="config_site_label_desativado">Texto principal do site</label>
                            <input type="text" class="form-control" value="${config_site_label_desativado}" name="config_site_label_desativado">
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-xs-12 col-md-12 col-lg-12 align-self-end">
                        <div class="form-group">
                            <button class="btn btn-danger btn-block"><i class="fa fa-save"></i> Salvar</button>
                        </div>
                    </div>
                </div>
            </form>
        <?php endif; ?>
        <!-- config -->

        <div class="row">
            <div class="col-sm-12 mt-5">
                <h4><b>Dashboard</b></h4>
                <hr>
            </div>
        </div>

        <div class="row justify-content-between">
            <div class="col-sm-12 col-md-3 col-lg-3 mt-4 mb-4">
                <?php if (Session::node('uperms') == 1) : ?>
                    <a onclick="$('#modalLimpaBanco').modal('show')">
                        <div class="card" style="background-color: rgb(235, 67, 67) !important;color: white !important;">
                            <div class="card-body text-center text-white">
                                <i class="fa fa-trash"></i> <b>Limpar Banco de Dados</b>
                            </div>
                        </div>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="row">
            <?php if ($data['config']->config_etapa_atual == 2) : ?>
                <div class="col-12 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="text-muted text-center"><b>Ranking 2ª etapa</b></h5>

                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="datatable_2" class="datatable display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Fintech</th>
                                                <th>Média</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (isset($data['fintechs_2'][0])) : ?>
                                                <?php foreach ($data['fintechs_2'] as $fintech) : ?>
                                                    <tr>
                                                        <td><?= $fintech->fintech_nome ?></td>
                                                        <td><?= number_format($fintech->media_total, 2, ',', '.') ?></td>
                                                        <td>
                                                            <a class="btn btn-primary" href="${baseUri}/admin/detalhes_fintech_2/<?= $fintech->fintech_id ?>">Mais informações</a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <div class="col-12 col-sm-12 mt-5">
                <div class="card">
                    <div class="card-body">
                        <h5 class="text-muted text-center"><b>Ranking 1ª etapa</b></h5>

                        <div class="row">
                            <div class="col-sm-12">
                                <table id="datatable_1" class="datatable display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Fintech</th>
                                            <th>Média</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (isset($data['fintechs_1'][0])) : ?>
                                            <?php foreach ($data['fintechs_1'] as $fintech) : ?>
                                                <tr>
                                                    <td><?= $fintech->fintech_nome ?></td>
                                                    <td><?= number_format($fintech->media_total, 2, ',', '.') ?></td>
                                                    <td>
                                                        <a class="btn btn-primary" href="${baseUri}/admin/detalhes_fintech_1/<?= $fintech->fintech_id ?>">Mais informações</a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-2-etapa" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Iniciar 2ª etapa</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="${baseUri}/admin/etapa_2" method="post">
                            <input type="hidden" class="form-control" name="key" value="1">
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <i class="fa fa-exclamation-triangle fa-3x text-warning" aria-hidden="true"></i>
                                    <br>
                                    <h4 class="text-warning text-center">Atenção</h4>
                                    <p>
                                        Você está prestes a <b>INICIAR A 2ª ETAPA</b>.
                                        <br>
                                        Para executar essa ação, digite sua senha no campo abaixo <br>
                                        <input type="password" class="form-control" name="pass" placeholder="Sua senha">
                                    </p>

                                    <button class="btn btn-success btn-block" type="submit">Estou ciente e desejo iniciar a 2ª etapa</button>
                                    <button class="btn btn-secondary btn-block" data-dismiss="modal" type="button">Desejo cancelar esta ação</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-1-etapa" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Voltar para 1ª etapa</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="${baseUri}/admin/etapa_1" method="post">
                            <input type="hidden" class="form-control" name="key" value="1">
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <i class="fa fa-exclamation-triangle fa-3x text-warning" aria-hidden="true"></i>
                                    <br>
                                    <h4 class="text-warning text-center">Atenção</h4>
                                    <p>
                                        Você está prestes a <b>VOLTAR PARA A 1ª ETAPA</b> e isso <b class="text-danger">apagará</b> os resultados e candidatos da 2ª etapa.
                                        <br>
                                        Para executar essa ação, digite sua senha no campo abaixo <br>
                                        <input type="password" class="form-control" name="pass" placeholder="Sua senha">
                                    </p>

                                    <button class="btn btn-success btn-block" type="submit">Estou ciente e desejo voltar para a 1ª etapa</button>
                                    <button class="btn btn-secondary btn-block" data-dismiss="modal" type="button">Desejo cancelar esta ação</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalLimpaBanco" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Limpar Banco de Dados</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="${baseUri}/admin/clear" method="post">
                            <input type="hidden" class="form-control" name="key" value="1">

                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <i class="fa fa-exclamation-triangle fa-3x text-warning" aria-hidden="true"></i>
                                    <br>
                                    <h4 class="text-warning text-center">Atenção</h4>
                                    <p>
                                        Você está prestes a <b>LIMPAR O BANCO DE DADOS</b> e essa ação não poderá ser desfeita.
                                        <br>
                                        Para executar essa ação, digite sua senha no campo abaixo <br>
                                        <input type="password" class="form-control" name="pass" placeholder="Sua senha">
                                    </p>

                                    <button class="btn btn-danger btn-block" type="submit">Estou ciente e desejo limpar o banco de dados</button>
                                    <button class="btn btn-secondary btn-block" type="button">Desejo cancelar esta ação</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>



    <script src="${baseUri}/view/tema/optionmaker/assets/js/jquery.js"></script>
    <script src="${baseUri}/view/tema/optionmaker/assets/js/bootstrap.min.js"></script>
    <script src="${baseUri}/view/tema/optionmaker/assets/js/toast.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>

    <script src="${baseUri}/view/admin/assets/js/datatable-init.js?v=2"></script>
    <script src="${baseUri}/view/tema/optionmaker/assets/js/main.js"></script>

    <script>
        $(document).ready(function() {
            $(".menu-link").addClass("btn btn-danger");
            $(".menu-dashboard").removeClass("btn-danger").addClass("btn-outline-danger text-danger");
            datatable_init("#datatable_1", 1);
            datatable_init("#datatable_2", 1);

            $("#config_site_status").val("${config_site_status}").trigger('change');
        })
    </script>
</body>

</html>