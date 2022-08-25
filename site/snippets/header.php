<!-- Nope, its not wordpress -->
<!DOCTYPE html>
<html lang="de">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Sportfreunde Bronnen 1949 e.V.">
    <?php snippet('og');?>

    <?php snippet('metatitle');?>

    <link href="/assets/images/sfb/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <link href="/assets/css/sfb.css?d=<?= filemtime('assets/css/sfb.css');?>" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato:400,100,100italic,300,300italic,400italic,700,700italic,900,900italic" rel="stylesheet" type="text/css">
    <link href="/assets/fonts/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="/assets/js/plugins/owl-carousel2/owl.carousel.min.css" rel="stylesheet">
    <link href="/assets/js/plugins/owl-carousel2/owl.theme.default.min.css" rel="stylesheet">
    <link href="/assets/js/plugins/magnific-popup/magnific-popup.css" rel="stylesheet">
    <?php if (EXTRACT_CONTENT === true): ?>
    <style type="text/css">
      div.page {
        background: #ffffff;
      }
    </style>
    <?php endif; ?>
</head>
<body>
<div class="container-fluid pl-0 pr-0 d-none d-md-block">
    <?php if (EXTRACT_CONTENT === false): ?>
      <div class="top-bar">
          <div class="container">
              <div class="container-fluid clearfix text-xs-center text-sm-center text-md-left pl-sm-0 pr-sm-0">
                  <ul class="list-unstyled list-inline float-md-left mb-0 animation">
                      <li class="list-inline-item"><a href="https://www.facebook.com/sportfreundebronnen/" title="Die Sportfreunde Bronnen auf Facebook"><i class="fa fa-facebook"></i></a></li>
                      <li class="list-inline-item"><a href="https://www.instagram.com/sfb1949/" title="Die Sportfreunde Bronnen auf Instagram"><i class="fa fa-instagram"></i></a></li>
                      <li class="list-inline-item"><a href="https://github.com/sportfreunde-bronnen/" title="Die Sportfreunde Bronnen auf Github - Open Source"><i class="fa fa-github"></i></a></li>
                      <li class="list-inline-item"><a href="https://shop.sf-bronnen.de" title="Der Online Shop der Sportfreunde Bronnen"><i class="fa fa-shopping-cart"></i></a></li>
                  </ul>
                  <p class="float-md-right mb-0 text-xs-center text-sm-center text-md-right text-weight-light">
                      <?php snippet('department');?>
                  </p>
              </div>
          </div>
      </div>
    <?php endif; ?>
</div>
<header class="main-header">
    <?php if (EXTRACT_CONTENT === false): ?>
    <div class="container text-xs-center pl-sm-0 pr-sm-0">
        <div class="row">
            <div class="col-lg-10 col-sm-12 text-xs-center text-sm-center text-md-left logo align-middle">
                <a href="/" class="sfb-headline"><img class="sfblogo rotate" src="/assets/images/sfb/logo.svg"/><span class="claim">Sportfreunde Bronnen 1949 e.V.</span></a>
            </div>
        </div>
    </div>
    <?php endif; ?>
</header>
<?php snippet('navigation'); ?>