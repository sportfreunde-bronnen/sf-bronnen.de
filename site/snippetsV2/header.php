<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <?php snippet('metatitle');?>
    <!-- SEO Meta Tags-->
    <meta name="description" content="Around - Multipurpose Bootstrap Template">
    <meta name="keywords" content="bootstrap, business, consulting, coworking space, services, creative agency, dashboard, e-commerce, mobile app showcase, multipurpose, product landing, shop, software, ui kit, web studio, landing, html5, css3, javascript, gallery, slider, touch, creative">
    <meta name="author" content="Createx Studio">
    <!-- Viewport-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon and Touch Icons-->
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
<body class="">
<!-- Page loading spinner-->
<div class="page-loading active">
    <div class="page-loading-inner">
        <div class="page-spinner"></div><span>Loading...</span>
    </div>
</div>
<main class="page-wrapper">
    <!-- Sign In Modal-->
    <div class="modal fade" id="modal-signin" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0">
                <div class="view show" id="modal-signin-view">
                    <div class="modal-header border-0 bg-dark px-4">
                        <h4 class="modal-title text-light">Sign in</h4>
                        <button class="btn-close btn-close-white" type="button" data-bs-dismiss="modal" aria-label="btn-close "></button>
                    </div>
                    <div class="modal-body px-4">
                        <p class="fs-ms text-muted">Sign in to your account using email and password provided during registration.</p>
                        <form class="needs-validation" novalidate>
                            <div class="mb-3">
                                <div class="input-group"><i class="ai-mail position-absolute top-50 start-0 translate-middle-y ms-3"></i>
                                    <input class="form-control rounded" type="email" placeholder="Email" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="input-group"><i class="ai-lock position-absolute top-50 start-0 translate-middle-y ms-3"></i>
                                    <div class="password-toggle w-100">
                                        <input class="form-control" type="password" placeholder="Password" required>
                                        <label class="password-toggle-btn" aria-label="Show/hide password">
                                            <input class="password-toggle-check" type="checkbox"><span class="password-toggle-indicator"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="keep-signed">
                                    <label class="form-check-label fs-sm" for="keep-signed">Keep me signed in</label>
                                </div><a class="nav-link-style fs-ms" href="password-recovery.html">Forgot password?</a>
                            </div>
                            <button class="btn btn-primary d-block w-100" type="submit">Sign in</button>
                            <p class="fs-sm pt-3 mb-0">Don't have an account? <a href='#' class='fw-medium' data-view='#modal-signup-view'>Sign up</a></p>
                        </form>
                    </div>
                </div>
                <div class="view" id="modal-signup-view">
                    <div class="modal-header border-0 bg-dark px-4">
                        <h4 class="modal-title text-light">Sign up</h4>
                        <button class="btn-close btn-close-white" type="button" data-bs-dismiss="modal" aria-label="btn-close"></button>
                    </div>
                    <div class="modal-body px-4">
                        <p class="fs-ms text-muted">Registration takes less than a minute but gives you full control over your orders.</p>
                        <form class="needs-validation" novalidate>
                            <div class="mb-3">
                                <input class="form-control" type="text" placeholder="Full name" required>
                            </div>
                            <div class="mb-3">
                                <input class="form-control" type="text" placeholder="Email" required>
                            </div>
                            <div class="mb-3 password-toggle">
                                <input class="form-control" type="password" placeholder="Password" required>
                                <label class="password-toggle-btn" aria-label="Show/hide password">
                                    <input class="password-toggle-check" type="checkbox"><span class="password-toggle-indicator"></span>
                                </label>
                            </div>
                            <div class="mb-3 password-toggle">
                                <input class="form-control" type="password" placeholder="Confirm password" required>
                                <label class="password-toggle-btn" aria-label="Show/hide password">
                                    <input class="password-toggle-check" type="checkbox"><span class="password-toggle-indicator"></span>
                                </label>
                            </div>
                            <button class="btn btn-primary d-block w-100" type="submit">Sign up</button>
                            <p class="fs-sm pt-3 mb-0">Already have an account? <a href='#' class='fw-medium' data-view='#modal-signin-view'>Sign in</a></p>
                        </form>
                    </div>
                </div>
                <div class="modal-body text-center px-4 pt-2 pb-4">
                    <hr class="my-0">
                    <p class="fs-sm fw-medium text-heading pt-4">Or sign in with</p><a class="btn-social bs-facebook bs-lg mx-1 mb-2" href="#"><i class="ai-facebook"></i></a><a class="btn-social bs-twitter bs-lg mx-1 mb-2" href="#"><i class="ai-twitter"></i></a><a class="btn-social bs-instagram bs-lg mx-1 mb-2" href="#"><i class="ai-instagram"></i></a><a class="btn-social bs-google bs-lg mx-1 mb-2" href="#"><i class="ai-google"></i></a>
                </div>
            </div>
        </div>
    </div>
    <!-- Navbar (Floating light)-->
    <!-- Remove "navbar-sticky" class to make navigation bar scrollable with the page.-->
    <header class="header navbar navbar-expand-lg navbar-dark navbar-floating navbar-sticky" data-scroll-header data-fixed-element>
        <div class="container px-0 px-xl-3">
            <button class="navbar-toggler ms-n2 me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#primaryMenu"><span class="navbar-toggler-icon"></span></button><a class="navbar-brand flex-shrink-0 order-lg-1 mx-auto ms-lg-0 pe-lg-2 me-lg-4" href="/"><img class="navbar-floating-logo d-none d-lg-block w-50" src="/assetsV2/img/logo/sfb.svg" alt="Around" width="153"><img class="navbar-stuck-logo w-50" src="/assetsV2/img/logo/sfb.svg" alt="Around" width="153"><img class="d-lg-none" src="/assetsV2/img/logo/sfb.svg" alt="Around" width="58"></a>
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
                                <a class="nav-link dropdown-toggle<?= $mainCategory->isOpen() ? ' active':'';?>" href="#" data-bs-toggle="dropdown"><?= $mainCategory->title();?></a>
                                <div class="dropdown-menu">
                                <?php $i = 0; foreach ($mainCategory->children()->listed() as $key => $subPage): $i++; ?>
                                    <?php $isDropdownOpen = false;?>
                                    <?php if ($subPage->children()->listed()->count()): ?>
                                        <?php if ($subPage->navType() != 'among'): ?>
                                        <?php if ($isDropdownOpen || $i > 1):?></div><?php endif; ?>
                                        <?php $isDropdownOpen = true;?>
                                        <div class="dropdown-column mb-2 mb-lg-0">
                                        <?php endif; ?>
                                        <h5 class="dropdown-header<?= $subPage->navType() == 'among' ? ' mt-4' : '';?>"><?= $subPage->title();?></h5>
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
    <?php if ($page->individualHeader()->toBool() === false):?>
    <section class="jarallax bg-gradient pt-5 pt-md-7 pb-7" data-jarallax="" data-speed="0.25" style="background-image: none;">
        <span class="position-absolute top-0 start-0 w-100 h-100 bg-gradient opacity-80"></span>
        <div class="shape shape-bottom shape-slant bg-body">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 3000 260">
                <polygon fill="currentColor" points="0,257 0,260 3000,260 3000,0"></polygon>
            </svg>
        </div>
        <div class="container position-relative zindex-5 pt-0 pt-md-3 pt-lg-3 pb-0 pb-md-2 pb-lg-5">
            <div class="row pb-0 pb-md-2 pb-lg-5">
                <div class="col-12 col-lg-10 pt-5 pt-md-3 pt-lg-5">
                    <h1 class="text-light"><?= $page->title();?></h1>
                    <p class="text-light">Test 123</p>
                </div>
            </div>
        </div>
        <div id="jarallax-container-0" style="position: absolute; top: 0px; left: 0px; width: 100%; height: 100%; overflow: hidden; z-index: -100;">
            <div class="jarallax-img" style="background-image: url('/assetsV2/img/pages/contacts/page-title-bdg.jpg'); object-fit: cover; object-position: 50% 50%; max-width: none; position: fixed; top: 0px; left: 0px; overflow: hidden; pointer-events: none; transform-style: preserve-3d; backface-visibility: hidden; will-change: transform, opacity; margin-top: 105.125px; transform: translate3d(0px, -85.375px, 0px);" data-jarallax-original-styles="background-image: url(/assetsV2/img/pages/contacts/page-title-bg.jpg);"></div>
        </div>
    </section>
    <?php else: ?>
    <?php snippet('headerImage');?>
    <?php endif; ?>
    <?php endif; ?>
