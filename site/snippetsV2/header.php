<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <?php snippet('metatitle');?>
    <!-- SEO Meta Tags-->
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="Sportfreunde Bronnen e.V.">
    <!-- Viewport-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon and Touch Icons-->
    <link href="/assets/images/sfb/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <link rel="apple-touch-icon" sizes="180x180" href="/assetsV2/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assetsV2/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assetsV2/favicon-16x16.png">
    <link rel="manifest" href="/assetsV2/site.webmanifest">
    <link rel="mask-icon" color="#5bbad5" href="/assetsV2/safari-pinned-tab.svg">
    <meta name="msapplication-TileColor" content="#766df4">
    <meta name="theme-color" content="#ffffff">
    <!-- Page loading styles-->
    <style>
        .page-loading {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100%;
            -webkit-transition: all .4s .2s ease-in-out;
            transition: all .4s .2s ease-in-out;
            background-color: #fff;
            opacity: 0;
            visibility: hidden;
            z-index: 9999;
        }
        .page-loading.active {
            opacity: 1;
            visibility: visible;
        }
        .page-loading-inner {
            position: absolute;
            top: 50%;
            left: 0;
            width: 100%;
            text-align: center;
            -webkit-transform: translateY(-50%);
            transform: translateY(-50%);
            -webkit-transition: opacity .2s ease-in-out;
            transition: opacity .2s ease-in-out;
            opacity: 0;
        }
        .page-loading.active > .page-loading-inner {
            opacity: 1;
        }
        .page-loading-inner > span {
            display: block;
            font-family: 'Inter', sans-serif;
            font-size: 1rem;
            font-weight: normal;
            color: #737491;
        }
        .page-spinner {
            display: inline-block;
            width: 2.75rem;
            height: 2.75rem;
            margin-bottom: .75rem;
            vertical-align: text-bottom;
            border: .15em solid #766df4;
            border-right-color: transparent;
            border-radius: 50%;
            -webkit-animation: spinner .75s linear infinite;
            animation: spinner .75s linear infinite;
        }
        @-webkit-keyframes spinner {
            100% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }
        @keyframes spinner {
            100% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

    </style>
    <!-- Page loading scripts-->
    <script>
      (function () {
        window.onload = function () {
          var preloader = document.querySelector('.page-loading');
          preloader.classList.remove('active');
          setTimeout(function () {
            preloader.remove();
          }, 2000);
        };
      })();

    </script>
    <!-- Vendor Styles-->
    <link rel="stylesheet" media="screen" href="/assetsV2/vendor/simplebar/dist/simplebar.min.css"/>
    <link rel="stylesheet" media="screen" href="/assetsV2/vendor/tiny-slider/dist/tiny-slider.css"/>
    <link rel="stylesheet" media="screen" href="/assetsV2/vendor/lightgallery.js/dist/css/lightgallery.min.css"/>
    <!-- Main Theme Styles + Bootstrap-->
    <link rel="stylesheet" media="screen" href="/assetsV2/css/theme.min.css">
</head>
<!-- Body-->
<body class="<?= in_array($page->template(), ['aktuelles']) ? 'is-sidebar' : '';?>">
<!-- Page loading spinner-->
<div class="page-loading active">
    <div class="page-loading-inner">
        <div class="spinner-grow text-info" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</div>

<main class="page-wrapper">
    <!-- Navbar (Floating light)-->
    <!-- Remove "navbar-sticky" class to make navigation bar scrollable with the page.-->
    <?php $headerClasses = $page->disableHeader()->toBool() === true ? [' bg-primary'] : [' navbar-floating']; ?>
    <header class="header navbar navbar-expand-lg navbar-dark navbar-sticky<?= implode(' ', $headerClasses);?>" data-scroll-header data-fixed-element>
        <div class="container px-0 px-xl-3">
            <button class="navbar-toggler ms-n2 me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#primaryMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand flex-shrink-0 order-lg-1 mx-auto ms-lg-0 pe-lg-2 me-lg-4" href="/">
                <img class="navbar-floating-logo d-none d-lg-block w-50" src="/assetsV2/img/logo/sfb.svg" alt="Around" width="153">
                <img class="navbar-stuck-logo w-50" src="/assetsV2/img/logo/sfb.svg" alt="Around" width="153">
                <!--
                <img class="d-lg-none" src="/assetsV2/img/logo/sfb.svg" alt="Around" width="58">
                -->
                <span class="d-lg-none display-6">
                    Sportfreunde Bronnen e.V.
                </span>
            </a>
            <div class="d-flex align-items-center order-lg-3 ms-lg-auto"><a class="btn btn-translucent-light ms-grid-gutter d-none d-lg-inline-block navbar-btn" href="#modal-signin" data-bs-toggle="modal" data-view="#modal-signup-view">Kontakt</a><a class="btn btn-primary ms-grid-gutter d-none d-lg-inline-block navbar-stuck-btn" href="#modal-signin" data-bs-toggle="modal" data-view="#modal-signup-view">Kontakt</a></div>
            <div class="offcanvas offcanvas-collapse order-lg-2" id="primaryMenu">
                <div class="offcanvas-header navbar-shadow">
                    <h5 class="mt-1 mb-0">Menu</h5>
                    <button class="btn-close lead" type="button" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <!-- Menu-->
                    <ul class="navbar-nav">
                        <?php foreach($pages->listed() as $mainCategory): ?>
                        <?php if ($mainCategory->children()->listed()->count()): ?>
                            <li class="nav-item dropdown dropdown-mega">
                                <a class="nav-link dropdown-toggle<?= $mainCategory->isOpen() ? ' show active':'';?>" href="<?= $mainCategory->url();?>" data-bs-toggle="dropdown"><?= $mainCategory->title();?></a>
                                <div class="dropdown-menu<?= $mainCategory->isOpen() ? ' show' : '';?>">
                                <?php $i = 0; foreach ($mainCategory->children()->listed() as $key => $subPage): $i++; ?>
                                    <?php $isDropdownOpen = false;?>
                                    <?php if ($subPage->children()->listed()->count()): ?>
                                        <?php if ($subPage->navType() != 'among'): ?>
                                        <?php if ($isDropdownOpen || $i > 1):?></div><?php endif; ?>
                                        <?php $isDropdownOpen = true;?>
                                        <div class="dropdown-column mb-2 mb-lg-0">
                                        <?php endif; ?>
                                        <h5 class="dropdown-header<?= $subPage->navType() == 'among' ? ' mt-lg-4' : '';?>"><?= $subPage->title();?></h5>
                                        <?php if ($subPage->title() == 'Veranstaltungen'):?>
                                            <?php foreach ($subPage->children()->listed()->filterBy('datum', 'date >=', date('Y-m-d'))->sortBy('datum') as $subSubPage): ?>
                                                <a class="dropdown-item<?= ($subSubPage->isOpen()) ? ' active' : '';?>" href="<?= $subSubPage->url();?>"><?= $subSubPage->title();?><br/><small><?= $subSubPage->datum()->toDate('d.m.Y');?></small></a>
                                            <?php endforeach;?>
                                        <?php else: ?>
                                            <?php foreach ($subPage->children()->listed() as $subSubPage): ?>
                                                <a class="dropdown-item<?= ($subSubPage->isOpen()) ? ' active' : '';?>" href="<?= $subSubPage->url();?>"><?= $subSubPage->title();?></a>
                                            <?php endforeach;?>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <a class="nav-link" href="#"><?= $mainCategory->title();?></a>
                                    <?php endif;?>
                                <?php endforeach;?>
                                </div>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= $mainCategory->url();?>"><?= $mainCategory->title();?></a>
                            </li>
                        <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </header>
    <?php if (!$page->isHomePage()):?>
    <?php if ($page->disableHeader()->toBool() === false): ?>
    <?php if ($page->individualHeader()->toBool() === false):?>
    <section class="d-flex align-items-center jarallax bg-gradient vh-40" data-jarallax="" data-speed="0.25" style="background-image: none;">
        <span class="position-absolute top-0 start-0 w-100 h-100 bg-gradient opacity-80"></span>
        <div class="shape shape-bottom shape-slant bg-body">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 3000 260">
                <polygon fill="currentColor" points="0,257 0,260 3000,260 3000,0"></polygon>
            </svg>
        </div>
        <div class="d-flex justify-content-start container position-relative zindex-5 mt-5 mt-lg-4">
            <div class="row w-100">
                <div class="col-12 col-lg-10">
                    <h1 class="text-light display-1"><?= $page->title();?></h1>
                    <p class="text-light">Test 123</p>
                </div>
            </div>
        </div>
    </section>
    <?php else: ?>
    <?php snippet('headerImage');?>
    <?php endif; ?>
    <?php endif; ?>
    <?php endif; ?>
