<?php snippet('header'); ?>
<section class="position-relative pt-7" id="section-home-jarallax">
    <!-- Background-->
    <div id="home-jarallax" class="position-absolute jarallax bg-gradient w-100" style="top: 0; left: 0;" data-jarallax data-speed="0.25"><span class="position-absolute top-0 start-0 w-100 h-100 bg-gradient" style="opacity: .80;"></span>
        <div class="jarallax-img" style="background-image: url(assetsV2/img/sfb/home/svz.jpg);"></div>
    </div>
    <!-- Tabs-->
    <div class="container position-relative zindex-5">
        <div class="row pt-4">
            <div class="col-xl-3 order-2 order-xl-1 pt-7 pt-md-5 pt-xl-0 d-flex align-items-center justify-content-center">
                <?php if ($page->slides()->isNotEmpty()): ?>
                    <ul class="nav nav-tabs media-tabs media-tabs-light justify-content-center justify-content-lg-start pb-3 mb-4 pb-lg-0 mb-lg-0" role="tablist">
                    <?php foreach ($page->slides()->toStructure() as $slide):?>
                        <li class="nav-item me-3 mb-3 w-100">
                            <a class="nav-link<?= $slide->id() == 0 ? ' active': '';?>" href="#s<?= $slide->id();?>" data-bs-toggle="tab" role="tab">
                                <div class="d-flex align-items-center">
                                    <div class="w-100 ps-2 ms-1">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="fs-sm pe-1"><?= $slide->title();?></div><i class="ai-chevron-right lead ms-2 me-1"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
            <div class="col-xl-9 order-1 order-xl-2 vh-25 d-flex align-items-center justify-content-center justify-content-xl-start">
                <?php if ($page->slides()->isNotEmpty()): ?>
                    <div class="tab-content">
                    <?php foreach ($page->slides()->toStructure() as $slide):?>
                        <div class="tab-pane fade show<?= $slide->id() == 0 ? ' active': '';?>" id="s<?= $slide->id();?>">
                            <div class="row align-items-center">
                                <div class="col-12 order-lg-1 text-center text-lg-start">
                                    <div class="ps-xl-5">
                                        <h2 class="h1 text-light"><?= $slide->title();?></h2>
                                        <p class="fs-lg text-light mb-lg-5">
                                            <?= $slide->text();?>
                                        </p>
                                        <?php if ($slide->internallink()->toPage()): ?>
                                            <a href="<?= $slide->internallink()->toPage()->url();?>" class="btn btn-translucent-light">
                                                <?php if ($slide->buttonText()->empty()): ?>
                                                    Mehr erfahren
                                                <?php else: ?>
                                                    <?= $slide->buttonText();?>
                                                <?php endif; ?>
                                            </a>
                                        <?php elseif($slide->externallink()->isNotEmpty()): ?>
                                            <a href="<?= $slide->externallink();?>" class="btn btn-translucent-light">
                                                <?php if ($slide->buttonText()->empty()): ?>
                                                    Mehr erfahren
                                                <?php else: ?>
                                                    <?= $slide->buttonText();?>
                                                <?php endif; ?>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<section class="bg-faded-primary position-relative">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 p-5">
                <h1>Herzlich willkommen bei den Sportfreunden Bronnen</h1>
                <hr>
                <?= $page->text()->kirbytext();?>
                <hr>
            </div>
        </div>
    </div>
</section>
<section class="position-relative">
    <div class="jarallax bg-dark py-7" data-jarallax data-speed="0.3"><span class="position-absolute top-0 w-100 h-100 bg-black" style="opacity: .50;"></span>
        <div class="jarallax-img" style="background-image: url(assetsV2/img/sfb/home/aikido-362959_1920.jpg);"></div>
        <div class="container position-relative zindex-2 py-5 py-sm-7 text-center">
            <h2 class="h1 text-light mb-0">Unsere Abteilungen</h2>
            <h3 class="text-light mt-1">Aikido - Fußball - Gymnastik</h3>
        </div>
    </div>
</section>

<section class="position-relative pt-6" style="z-index: 2;">
    <div class="container pb-5">
        <h2 class="text-center pb-1 mb-5">Aktuelles aus unseren Abteilungen</h2>
        <?php snippet('news-slider', ['news' => $page->getLatestNews()]);?>
    </div>
</section>

<section>
    <div class="container">
        <h2 class="text-center pb-1 mb-5">Kommende Veranstaltungen</h2>
        <div class="tns-carousel-wrapper">
            <div class="tns-carousel-inner" data-carousel-options="{&quot;items&quot;: 3, &quot;nav&quot;: false, &quot;gutter&quot;: 23, &quot;responsive&quot;: {&quot;0&quot;:{&quot;items&quot;:1},&quot;800&quot;:{&quot;items&quot;:2}}}">
                <?php foreach ($page->getNextEvents() as $event): ?>
                <div class="pb-2">
                    <div class="card h-100 border-0 shadow mx-1"><span class="badge badge-lg badge-floating badge-floating-end bg-success">Gesamtverein</span>
                        <div class="card-body py-2 px-4">
                            <div class="d-sm-flex py-sm-4 px-lg-3">
                                <div class="ps-sm-4 ps-lg-5"><a class="meta-link fs-sm mb-2" href="#"><?= $page->formatDate($event->datum());?></a>
                                    <h3 class="h4 nav-heading mb-4"><a href="#"><?= $event->title();?></a></h3>
                                    <p><?= $event->shorttitle();?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php snippet('footer'); ?>