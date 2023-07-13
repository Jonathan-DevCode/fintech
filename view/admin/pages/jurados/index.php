<!DOCTYPE html>
<html lang="pt-br">

<head>
    <title>Central do cliente</title>
    <!-- Stylesheets -->
    <link href="${baseUri}/view/admin/assets/css/bootstrap.css" rel="stylesheet">
    <link href="${baseUri}/view/admin/assets/css/fontawesome-all.css" rel="stylesheet">
    <link href="${baseUri}/view/admin/assets/plugins/datatable/datatable.css" rel="stylesheet">
    <link href="${baseUri}/view/admin/assets/css/style.css" rel="stylesheet">
    <link href="${baseUri}/view/admin/assets/css/toast.css" rel="stylesheet">
    <link href="${baseUri}/view/admin/assets/css/custom.css" rel="stylesheet">
    <!-- Responsive Settings -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <!--[if lt IE 9]><script src="https:/cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script><![endif]-->
    <!--[if lt IE 9]><script src="js/respond.js"></script><![endif]-->
</head>

<body style="background-image: linear-gradient(to bottom right, rgb(255, 255, 255), rgb(200, 200, 200));min-height: 100vh;">
    @(admin.components.menu-superior)

    <br><br><br>
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-9 col-lg-9">
                <h4><b>Jurados</b></h4>

            </div>
            <div class="col-sm-12 col-md-3 col-ld-3 align-self-center">
                <button class="btn btn-danger btn-block" onclick="show_novo()"><i class="fa fa-plus"></i> Adicionar</button>
            </div>
            <div class="col-sm-12">
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-12">
                <div class="card">
                    <div class="card-body">

                        <table id="datatable" class="datatable display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Votação finalizada</th>
                                    <th>Votos</th>
                                    <th class="d-print-none" width="100">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($data['jurados'][0])) : ?>
                                    <?php foreach ($data['jurados'] as $c) : ?>
                                        <tr>
                                            <td><b>#<?= $c->jurado_id ?></b></td>
                                            <td><?= $c->jurado_nome ?></td>
                                            <td><?= $c->jurado_votou_em_todas ? "Sim" : 'Não' ?></td>
                                            <td><?= $c->jurado_string_votos ?></td>
                                            <td>
                                                <button class="btn btn-primary btn-sm" onclick='show_edit(`<?= json_encode($c) ?>`)'><i class="fa fa-edit"></i></button>
                                                <button class="btn btn-danger btn-sm" onclick="show_remove(<?= $c->jurado_id ?>)"><i class="fa fa-trash"></i></button>
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

    <div class="modal fade" id="modalNovo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Salvar jurado</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="${baseUri}/jurados/gravar" method="post">
                        <input type="hidden" class="form-control" name="jurado_id" id="jurado_id">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12 col-md-12 col-lg-12">
                                <label for="jurado_nome">Nome</label>
                                <input type="text" class="form-control" name="jurado_nome" id="jurado_nome">
                            </div>

                            <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6">
                                <label for="jurado_login">Login</label>
                                <input type="text" class="form-control" name="jurado_login" id="jurado_login">
                            </div>
                            <div class="col-sm-12 col-xs-12 col-md-6 col-lg-6">
                                <label for="jurado_senha">Senha (apenas preencha para alterar)</label>
                                <input type="password" class="form-control" name="jurado_senha" id="jurado_senha">
                            </div>



                            <div class="col-sm-12 mt-3">
                                <button type="submit" class="btn btn-danger btn-block">Salvar jurado</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalRemove" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Remover jurado</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="${baseUri}/jurados/remove" method="post">
                        <input type="hidden" class="form-control" name="id" id="jurado_id_remove">

                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <i class="fa fa-exclamation-triangle fa-3x text-warning" aria-hidden="true"></i>
                                <br>
                                <h4 class="text-warning text-center">Atenção</h4>
                                <p>
                                    Você está prestes a remover um <b>jurado</b> e essa ação não poderá ser desfeita.
                                </p>
                                <button class="btn btn-danger btn-block" type="submit">Remover</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <script src="${baseUri}/view/admin/assets/js/jquery.js"></script>
    <script src="${baseUri}/view/admin/assets/js/bootstrap.min.js"></script>
    <script src="${baseUri}/view/admin/assets/js/toast.js"></script>
    <script src="${baseUri}/view/admin/assets/plugins/datatable/jquery.datatable.js"></script>
    <script src="${baseUri}/view/admin/assets/plugins/datatable/datatable.js"></script>
    <script src="${baseUri}/view/admin/assets/js/mask.js"></script>
    <script src="${baseUri}/view/admin/assets/js/datatable-init.js"></script>
    <script src="${baseUri}/view/admin/assets/js/main.js"></script>

    <script>
        $(document).ready(function() {
            $(".menu-link").addClass("btn btn-danger");
            $(".menu-jurados").removeClass("btn-danger").addClass("btn-outline-danger text-danger");
            datatable_init();
        });

        function show_novo() {
            $("#jurado_id").val("");
            $("#jurado_nome").val("");
            $("#jurado_login").val("");
            $("#jurado_senha").val("");
            $('#modalNovo').modal('show');
        }

        function show_edit(admin) {
            admin = JSON.parse(admin);
            $("#jurado_id").val(admin.jurado_id);
            $("#jurado_nome").val(admin.jurado_nome);
            $("#jurado_login").val(admin.jurado_login);
            $("#jurado_senha").val("");
            $('#modalNovo').modal('show');
        }

        function show_remove(id) {
            $("#jurado_id_remove").val(id);
            $("#modalRemove").modal('show');
        }
    </script>
</body>

</html>