<!DOCTYPE html>
<html lang="pt-br">

<head>
    <title>Central do cliente</title>
    <!-- Stylesheets -->
    <link href="${baseUri}/view/admin/assets/css/bootstrap.css" rel="stylesheet">
    <link href="${baseUri}/view/admin/assets/css/fontawesome-all.css" rel="stylesheet">
    <link href="${baseUri}/view/admin/assets/plugins/datatable/datatable.css" rel="stylesheet">
    <link href="${baseUri}/view/admin/assets/plugins/summernote/summernote-lite.css" rel="stylesheet">
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
                <h4><b>Fintechs <?php if ($data['etapa'] == 2) : ?>finalistas<?php endif; ?></b></h4>

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
                                    <th>Descrição</th>
                                    <th class="d-print-none" width="100">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($data['fintechs'][0])) : ?>
                                    <?php foreach ($data['fintechs'] as $c) : ?>
                                        <tr>
                                            <td><b>#<?= $c->fintech_id ?></b></td>
                                            <td><?= $c->fintech_nome ?></td>
                                            <td><?= $c->fintech_descricao ?></td>
                                            <td>
                                                <button class="btn btn-primary btn-sm" onclick='show_edit(<?= $c->fintech_id ?>)'><i class="fa fa-edit"></i></button>
                                                <button class="btn btn-danger btn-sm" onclick="show_remove(<?= $c->fintech_id ?>)"><i class="fa fa-trash"></i></button>
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
                    <h5 class="modal-title">Salvar fintech</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="${baseUri}/fintechs/gravar" method="post">
                        <input type="hidden" class="form-control" name="fintech_id" id="fintech_id">
                        <input type="hidden" value="<?= $data['etapa'] ?>" class="form-control" name="etapa" id="etapa">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12 col-md-12 col-lg-12">
                                <label for="fintech_nome">Nome</label>
                                <input type="text" class="form-control" name="fintech_nome" id="fintech_nome">
                            </div>

                            <div class="col-sm-12 col-xs-12 col-md-12 col-lg-12">
                                <label for="fintech_descricao">Descrição</label>
                                <textarea rows="6" class="form-control summernote" name="fintech_descricao" id="fintech_descricao"></textarea>
                            </div>

                            <div class="col-sm-12 col-xs-12 col-md-12 col-lg-12">
                                <label for="fintech_ordem">Ordem</label>
                                <input type="number" min="0" class="form-control" name="fintech_ordem" id="fintech_ordem">
                            </div>

                            <div class="col-sm-12 mt-3">
                                <button type="submit" class="btn btn-danger btn-block">Salvar fintech</button>
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
                    <h5 class="modal-title">Remover Fintech</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="${baseUri}/fintechs/remove" method="post">
                        <input type="hidden" class="form-control" name="id" id="fintech_id_remove">
                        <input type="hidden" value="<?= $data['etapa'] ?>" class="form-control" name="etapa" id="etapa">
                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <i class="fa fa-exclamation-triangle fa-3x text-warning" aria-hidden="true"></i>
                                <br>
                                <h4 class="text-warning text-center">Atenção</h4>
                                <p>
                                    Você está prestes a remover uma <b>fintech</b> e essa ação não poderá ser desfeita.
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
    <script src="${baseUri}/view/admin/assets/plugins/summernote/summernote-lite.min.js"></script>
    <script src="${baseUri}/view/admin/assets/plugins/summernote/lang/summernote-pt-BR.js"></script>
    <script src="${baseUri}/view/admin/assets/js/mask.js"></script>
    <script src="${baseUri}/view/admin/assets/js/datatable-init.js"></script>
    <script src="${baseUri}/view/admin/assets/js/main.js"></script>

    <script>
        $(document).ready(function() {
            $(".menu-link").addClass("btn btn-danger");
            <?php if (!isset($data['is_finalista']) || $data['is_finalista'] != 1) : ?>
                $(".menu-fintechs").removeClass("btn-danger").addClass("btn-outline-danger text-danger");
            <?php else : ?>
                $(".menu-fintechs-2").removeClass("btn-danger").addClass("btn-outline-danger text-danger");
            <?php endif; ?>
            datatable_init();

        });

        function show_novo() {
            $('.summernote').summernote("destroy");
            $('.summernote').summernote('code', '',{
                placeholder: '',
                lang: 'pt-BR',
                minHeight: 150,
                maxHeight: 550,
                disableDragAndDrop: true,
                toolbar: [
                    ['media', ['link']],
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol']],
                    ['size', ['paragraph', 'height', 'fontsize']],
                    ['misc', ['undo', 'redo']],
                ]
            });
            $("#fintech_id").val("");
            $("#fintech_nome").val("");
            $("#fintech_ordem").val(0);
            $("#fintech_descricao").val("");
            $('#modalNovo').modal('show');
        }

        function show_edit(id) {
            $.post("${baseUri}/Fintechs/find", {
                    id: id,
                    etapa: $("#etapa").val()
                })
                .then(fintech => {
                    console.log(fintech);
                    fintech = JSON.parse(fintech);

                    $('.summernote').summernote("destroy");
                    $('.summernote').summernote('code', fintech.fintech_descricao, {
                        placeholder: '',
                        lang: 'pt-BR',
                        minHeight: 150,
                        maxHeight: 550,
                        disableDragAndDrop: true,
                        toolbar: [
                            ['media', ['link']],
                            ['style', ['bold', 'italic', 'underline', 'clear']],
                            ['para', ['ul', 'ol']],
                            ['size', ['paragraph', 'height', 'fontsize']],
                            ['misc', ['undo', 'redo']],
                        ]
                    });
                    $("#fintech_id").val(fintech.fintech_id);
                    $("#fintech_nome").val(fintech.fintech_nome);
                    
                    $("#fintech_ordem").val(fintech.fintech_ordem);
                    $('#modalNovo').modal('show');
                })
        }

        function show_remove(id) {
            $("#fintech_id_remove").val(id);
            $("#modalRemove").modal('show');
        }
    </script>
</body>

</html>