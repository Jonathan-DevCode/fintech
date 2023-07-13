<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Descrição do site">
        <meta name="author" content="desenvolvido por Fulano">
        <title>Oops - Ação ou Método Indisponível</title>
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
        <!-- <link href="//maxcdn.bootstrapcdn.com/bootswatch/3.3.4/paper/bootstrap.min.css" rel="stylesheet"> -->
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
        <!--[if lt IE 9]>
        <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <div class="container">
            <h3 class="well well-sm">
                <?php echo $data; ?> - Método Indisponível!
            </h3>
            <p class="text-center">
                <a href="<?= Http::base() ?>/" class="btn btn-block btn-default"><i class="fa fa-chevron-left"></i> Voltar</a>
            </p>
        </div>
    </body>
</html>
