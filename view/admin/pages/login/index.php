<!DOCTYPE html>
<html lang="pt-br">

<head>
    <title>Central do cliente</title>
    <!-- Stylesheets -->
    <link href="${baseUri}/view/tema/optionmaker/assets/css/bootstrap.css" rel="stylesheet">
    <link href="${baseUri}/view/tema/optionmaker/assets/css/fontawesome-all.css" rel="stylesheet">
    <link href="${baseUri}/view/tema/optionmaker/assets/css/style.css" rel="stylesheet">
    <link href="${baseUri}/view/tema/optionmaker/assets/css/toast.css" rel="stylesheet">
    <link href="${baseUri}/view/tema/optionmaker/assets/css/custom.css" rel="stylesheet">
    <!-- Responsive Settings -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <!--[if lt IE 9]><script src="https:/cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script><![endif]-->
    <!--[if lt IE 9]><script src="js/respond.js"></script><![endif]-->
</head>

<body style="background-image: linear-gradient(to bottom right, rgb(255, 255, 255), rgb(200, 200, 200));height: 100vh; overflow-y: hidden !important">

    <div class="container" style="margin-top: 30vh">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-4 col-lg-4 offset-md-4 offset-lg-4 text-center white-panel p-4">
                <form action="${baseUri}/auth/admin" method="post">
                    <h6 class="text-danger"><img src="${baseUri}/media/default/logo.png" alt="Logo Option Maker"> OptionMaker</h6>
                    <h5 class="text-danger"><b>Painel Administrativo</b></h5>

                    <input type="text" class="form-control" name="login" placeholder="LOGIN:">
                    <input type="password" class="form-control mt-2" name="senha" placeholder="SENHA:">
                    <button class="btn btn-red mt-2 btn-block">Entrar</button>
                </form>
            </div>
        </div>
    </div>

    <script src="${baseUri}/view/tema/optionmaker/assets/js/jquery.js"></script>
    <script src="${baseUri}/view/tema/optionmaker/assets/js/bootstrap.min.js"></script>
    <script src="${baseUri}/view/tema/optionmaker/assets/js/toast.js"></script>
    <script src="${baseUri}/view/tema/optionmaker/assets/js/main.js"></script>
</body>

</html>