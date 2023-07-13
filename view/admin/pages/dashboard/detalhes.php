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
    <div class="row">
      <div class="col-sm-12 mt-5">
        <h4>Detalhes da fintech: <b><?= $data['fintech']->fintech_nome ?></b> - <?= $data['etapa'] ?>ª etapa</h4>
        <hr>
      </div>
    </div>

    <div class="row">
      <?php if (isset($data['fintech']->fintech_criterios[0])) : ?>
        <?php foreach ($data['fintech']->fintech_criterios as $criterio) : ?>
          <div class="col-12 col-sm-12 mt-5">
            <div class="card">
              <div class="card-body">
                <h5 class="text-"><b><?= $criterio->criterio_nome ?></b></h5>
                <h6>Média: <?= number_format($criterio->criterio_media_fintech, 2, ',', '.') ?></h6>


              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
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