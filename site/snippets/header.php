<?php
// On its own domain the SVZ site brings its own header (no main navigation of the club site), see plugin sfb-svz
$isSvzSite = SvzDomain::isSvzRequest();
// Header variant: 'classic' (default) or 'modern', set with 'sfb.header' in the (gitignored) site/config/config.php.
// ?header=modern / ?header=classic previews a variant for a single request.
$headerVariant = (string)get('header', option('sfb.header', 'classic'));
$useModernHeader = $headerVariant === 'modern';
// The pages of the darts department show the crest of the department instead of the club crest
$isDarts = (string)$page->parents()->last()?->title()->lower() === 'darts';
?>
<!-- Nope, its not wordpress -->
<!DOCTYPE html>
<html lang="de">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Sportfreunde Bronnen 1949 e.V.">
    <?php snippet('og');?>

    <?php snippet('metatitle');?>
    <?php // the SVZ site is reachable via its subdomain and via the main domain, the subdomain is the original ?>
    <?php if ($canonicalUrl = SvzDomain::canonicalUrl($page)): ?>
    <link rel="canonical" href="<?= htmlspecialchars($canonicalUrl, ENT_QUOTES);?>">
    <?php endif; ?>

    <link href="/assets/images/sfb/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <link href="/assets/css/sfb.css?d=<?= filemtime('assets/css/sfb.css');?>" rel="stylesheet">
    <link href="/assets/fonts/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="/assets/js/plugins/magnific-popup/magnific-popup.css" rel="stylesheet">
    <?php foreach (($styles ?? []) as $style): ?>
    <link href="/assets/css/<?= $style;?>.css?d=<?= filemtime('assets/css/' . $style . '.css');?>" rel="stylesheet">
    <?php endforeach; ?>
    <?php if (EXTRACT_CONTENT === true): ?>
    <style type="text/css">
      div.page {
        background: #ffffff !important;
      }
    </style>
    <?php endif; ?>
</head>
<?php // data-1p-ignore: the forms of this site are no login forms, so password managers (1Password) shall not offer to fill or save anything.
// The attributes of the other password managers are only evaluated on the fields and are added by the plugin sfb-password-manager ?>
<body<?= $isSvzSite ? ' class="svz-site"' : '' ?> data-1p-ignore>
<?php if ($isSvzSite): ?>
<?php snippet('svz/header'); ?>
<?php elseif ($useModernHeader): ?>
<?php snippet('header-modern', ['isDarts' => $isDarts]); ?>
<?php else: ?>
<div class="container-fluid ps-0 pe-0 d-none d-md-block">
    <?php if (EXTRACT_CONTENT === false): ?>
      <div class="top-bar">
          <div class="container">
              <div class="container-fluid clearfix text-xs-center text-sm-center text-md-start ps-sm-0 pe-sm-0">
                  <ul class="list-unstyled list-inline float-md-start mb-0 animation">
                      <?php snippet('social-links'); ?>
                  </ul>
                  <p class="float-md-end mb-0 text-xs-center text-sm-center text-md-end text-weight-light">
                      <?php snippet('department');?>
                  </p>
              </div>
          </div>
      </div>
    <?php endif; ?>
</div>
<header class="main-header">
    <?php if (EXTRACT_CONTENT === false): ?>
    <div class="container text-xs-center ps-sm-0 pe-sm-0">
        <div class="row">
            <?php if ($isDarts): ?>
              <div class="col-lg-10 col-sm-12 text-xs-center text-sm-center text-md-start logo align-middle">
                      <a href="/" class="sfb-headline"><img class="sfblogo darts rotate" src="/assets/images/sfb/sfbdarts.svg"/><span class="claim">Sportfreunde Bronnen e.V. - Darts</span></a>
              </div>
            <?php else: ?>
              <div class="col-lg-10 col-sm-12 text-xs-center text-sm-center text-md-start logo align-middle">
                      <a href="/" class="sfb-headline"><img class="sfblogo club rotate" src="/assets/images/sfb/logo.svg"/><span class="claim">Sportfreunde Bronnen e.V.</span></a>
              </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</header>
<?php snippet('navigation'); ?>
<?php endif; ?>