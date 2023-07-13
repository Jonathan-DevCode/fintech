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

<body class="body-login">
    <div class="container-fluid">
        <div class="row">
            <?php if (intval($data['config']->config_site_status) == 1) : ?>
                <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right">
                    <div class="row">
                        <div class="col-3 col-lg-3" style="background-color: white; display: flex;flex-direction: column;justify-content: center;">
                            <img src="${baseUri}/media/default/febraban-logo.png" alt="" class="logo-febraban">
                        </div>
                        <div class="col-9 col-lg-9 col-febraban-img">
                            <div class="row">
                                <div class="col-12 col-sm-12">
                                    <img src="${baseUri}/media/default/febraban-2.png" alt="" class="img-febraban">
                                </div>
                                <div class="col-12 col-sm-12" style="margin: 15px 0px;">
                                    <b class="text-right text-yellow-custom">o novo CIAB</b>                            
                                </div>
                            </div>                            
                        </div>
                        <div class="col-12 col-sm-12" style="background-color: var(--blue-color); padding-right: 0px;">
                            <div class="d-flex" style="justify-content: flex-end;">
                                <div class="card-yellow">33ª edição</div>
                            </div>     
                        </div>
                    </div>
                </div>

                <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6 form-desktop">
                    <form id="form-login">
                        <div class="row">
                            <div class="col-12 col-sm-12">
                                <h5 class="title">Seja bem vindo!</h5>
                                <h6 class="subtitle">Insira suas credênciais para acessar a área do jurado</h6>

                                <div class="form-group">
                                    <label for="" style="color: white; font-weight: bold;">Login</label>
                                    <div class="input-field" id="login-field">
                                        <i class="fa fa-user"></i>
                                        <input type="text" class="form-control" id="login">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="" style="color: white; font-weight: bold;">Senha</label>
                                    <div class="input-field" id="senha-field">
                                        <i class="fa fa-lock"></i>
                                        <input type="password" class="form-control" id="senha">                                        
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 col-sm-12 error-message">

                                    </div>
                                </div>
                                <button class="btn btn-login" type="submit">Entrar</button>
                                <button class="btn btn-login-loading" type="button" style="display: none;">Entrando...</button>
                            </div>
                        </div>
                    </form>
                </div>
            <?php else : ?>                
                <div class="col-12 text-center mt-5">
                    <h5 class="text-white"><?= $data['config']->config_site_label_desativado ?></h5>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="${baseUri}/view/tema/optionmaker/assets/js/jquery.js"></script>
    <script src="${baseUri}/view/tema/optionmaker/assets/js/bootstrap.min.js"></script>
    <script src="${baseUri}/view/tema/optionmaker/assets/js/mask.js"></script>
    <script src="${baseUri}/view/tema/optionmaker/assets/js/toast.js"></script>
    <script src="${baseUri}/view/tema/optionmaker/assets/js/main.js"></script>

    <script>
        $(".cnpj").mask("99.999.999/9999-99");

        $("#form-login").on("submit", function(e) {
            e.preventDefault();
            login();
        })

        function login() {
            const login = $("#login").val().trim();
            const senha = $("#senha").val().trim();
            $(".error-message").html("");

            if(!login || login == "") {
                $("#login-field").addClass("error-field");
                $(".error-message").html("* Por favor, preencha o login");
                return false;
            }

            if(!senha || senha == "") {
                $("#senha-field").addClass("error-field");
                $(".error-message").html("* Por favor, preencha a senha");
                return false;
            }

            $(".input-field").removeClass("error-field");

            $(".btn-login").hide();
            $(".btn-login-loading").show();

            const data = {
                login: login,
                senha: senha,
            };
            $.post("${baseUri}/auth/jurado", data)
                .then(res => {
                    res = JSON.parse(res);
                    if(res.status == 200) {
                        window.location.href = "${baseUri}/painel-jurado";
                    } else {
                        $(".btn-login").show();
                        $(".btn-login-loading").hide();
                        $(".error-message").html("* Desculpe, não conseguimos encontrar seu usuário. Por favor, tente novamente.");
                    }
                })
        }
    </script>
</body>

</html>