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
    <div class="container mt-5">
        <div class="row">
            <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <?php if (intval($data['fintech_prev']) > 0) : ?>
                    <!-- <a class="btn btn-prev-fintech" href="${baseUri}/avaliacao/avaliar/<?= $data['fintech_prev'] ?>"><i class="fa fa-chevron-left"></i> Fintech anterior</a> -->
                <?php endif; ?>
            </div>
            <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <?php if (intval($data['fintech_next']) > 0) : ?>
                    <!-- <a class="btn btn-next-fintech" href="${baseUri}/avaliacao/avaliar/<?= $data['fintech_next'] ?>"><i class="fa fa-chevron-right"></i> Pular Fintech</a> -->
                <?php endif; ?>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-sm-12 mt-4 mb-5">
                <div class="card-fintech">
                    <div class="fintech-icon-section">
                        <div class="fintech-icon">
                            <i class="fa fa-users"></i>
                        </div>
                    </div>
                    <div class="fintech-info">
                        <div class="fintech-title"><?= $data['fintech']->fintech_nome ?></div>
                        <div class="fintech-desc"><?= $data['fintech']->fintech_descricao ?></div>
                    </div>
                    <div class="fintech-action"></div>
                </div>

                <div class="criterios-section"></div>

                <button class="btn btn-blue pull-right btn-radius" id="btnModalFinalizaEscolhas" onclick="confirmaFinalizarEscolhas()">Finalizar escolhas</button>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalFinaliza" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <i class="fa fa-exclamation-triangle fa-2x text-warning" aria-hidden="true"></i>
                            <br>
                            <h5 class="text-center">Confirme suas escolhas</h5>

                            <div class="row" id="row-confirmar-escolhas">

                            </div>

                            <p><b>Média da fintech <?= $data['fintech']->fintech_nome ?></b>: <span id="media-value"></span></p>
                            <br>
                            <label for="">Deseja realizar algum comentário?</label>
                            <input type="text" class="form-control" id="comentario" placeholder="Escreva seu comentário (opcional)">
                            <br>
                            <div id="error"></div>
                            <button class="btn btn-blue btn-block" id="salvar-escolhas" onclick="finalizaEscolhas()"><i class="fa fa-check"></i> Confirmar escolhas</button>
                            <button class="btn btn-blue btn-block" id="salvando-escolhas" style="display: none;">Salvando suas escolhas...</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalVotoConcluido" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <i class="fa fa-check fa-2x text-success" aria-hidden="true"></i>
                            <br>
                            <h6 class="text-success text-center">Sua escolha foi salva com sucesso!</h6>

                            <p id="ultima-fintech-texto" style="display: none;">Você escolheu a nota da última fintech da lista! Volte para a lista de fintechs caso tenha pulado alguma.</p>

                            <a class="btn btn-blue btn-block" id="proxima-fintech"><i class="fa fa-arrow-left"></i> Voltar para lista</a>                            
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

        let notas_escolhidas = [];
        let criterios = [];
        let criterios_full = [];
        let media = 0;

        $(document).ready(function() {
            getCriterios();
        })

        function getCriterios() {
            $.post("${baseUri}/avaliacao/get_criterios", {})
                .then(res => {
                    res = JSON.parse(res);
                    if (res.status == 200) {
                        renderCriterios(res.criterios);
                    }
                })
        }

        function renderCriterios(criteriosParam = []) {
            if (criteriosParam.length <= 0) return false;

            $(".criterios-section").html("");
            let criterioHtml = "";
            criteriosParam.map(criterio => {
                criterios.push(criterio.criterio_id);
                criterios_full.push(criterio);
                notas_escolhidas[criterio.criterio_id] = 0;
                criterioHtml += getCriterioTemplate(criterio);
            })

            $(".criterios-section").html(criterioHtml);
        }

        function getCriterioTemplate(criterio) {
            return `
            <div class="card-avaliacao">
                <div class="criterio-info">
                    <h5>${criterio.criterio_nome}</h5>
                    <h6>${criterio.criterio_descricao}</h6>
                </div>
                <div class="criterio-nota">
                    <h5>Escolha sua nota:</h5>
                    <div class="criterio-nota-buttons">
                        <button onclick="setNota(${criterio.criterio_id}, 1)" data-criterio-id="${criterio.criterio_id}" class="btn btn-nota btn-nota-1">1</button>
                        <button onclick="setNota(${criterio.criterio_id}, 2)" data-criterio-id="${criterio.criterio_id}" class="btn btn-nota btn-nota-2">2</button>
                        <button onclick="setNota(${criterio.criterio_id}, 3)" data-criterio-id="${criterio.criterio_id}" class="btn btn-nota btn-nota-3">3</button>
                        <button onclick="setNota(${criterio.criterio_id}, 4)" data-criterio-id="${criterio.criterio_id}" class="btn btn-nota btn-nota-4">4</button>
                        <button onclick="setNota(${criterio.criterio_id}, 5)" data-criterio-id="${criterio.criterio_id}" class="btn btn-nota btn-nota-5">5</button>
                    </div>
                </div>
            </div>
            `;
        }

        $(".btn-nota").on("click", function() {
            let criterio_id = $(this).data("criterio-id");

            // reseta as escolhas desse critério
            $(`.btn-nota-${criterio_id}`).removeClass("selected");
            $(this).addClass("selected");
        })

        function setNota(criterio_id, nota) {
            $(`button[data-criterio-id=${criterio_id}]`).removeClass("selected");
            $(`button[data-criterio-id=${criterio_id}].btn-nota-${nota}`).addClass("selected");

            // insere a nota no array
            notas_escolhidas[criterio_id] = nota;

            calculaMedia();
        }

        function calculaMedia() {
            media = 0;

            criterios.map(criterio_id => {
                media += parseInt(notas_escolhidas[criterio_id]);
            })

            media = media / criterios.length;
            console.log(media);
        }

        function confirmaFinalizarEscolhas() {
            let htmlCriteriosConfirma = "";

            criterios_full.map(criterio => {
                htmlCriteriosConfirma += `
                    <div class="col-12 col-sm-12 mb-1">
                        <b>${criterio.criterio_nome}</b>
                        <br>
                        Nota: ${notas_escolhidas[criterio.criterio_id]}
                        <hr>
                    </div>
                `;
            })

            $("#row-confirmar-escolhas").html(htmlCriteriosConfirma);

            $("#media-value").html(media);

            $("#modalFinaliza").modal('show');
        }

        function finalizaEscolhas() {
            $("#salvar-escolhas").hide();
            $("#salvando-escolhas").show();
            $("#error").html("");
            let data = {
                notas: [],
                fintech_id: "<?= $data['fintech']->fintech_id ?>",
                comentario: $("#comentario").val()
            };
            criterios_full.map(criterio => {
                data.notas.push({
                    criterio_id: criterio.criterio_id,
                    nota: notas_escolhidas[criterio.criterio_id]
                });
            })

            $.post("${baseUri}/avaliacao/setar_nota", data)
                .then(res => {
                    res = JSON.parse(res);
                    if (res.status == 200) {
                        $("#modalFinaliza").modal("hide");

                        // ocultando btn de "finalizar escolhas" para não deixar votar novamente
                        $("#btnModalFinalizaEscolhas").hide();
                        window.location.href = "${baseUri}/painel-jurado/?success";
                        
                    } else {
                        $("#salvar-escolhas").show();
                        $("#salvando-escolhas").hide();
                        if (res.msg) {
                            $("#error").html(res.msg);
                        }
                    }
                })
        }
    </script>

</body>

</html>