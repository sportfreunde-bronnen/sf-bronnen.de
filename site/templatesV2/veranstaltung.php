<?php snippet('header');?>

<section class="bg-secondary py-5 py-md-6">
    <div class="container py-2 py-md-0">
        <div class="row align-items-center">
            <div class="col-md-5 text-center">
                <div class="h2">Wann?</div>
                <div class="fs-5">
                    <?= $page->datum()->toDate('d.m.Y H:i');?>
                    <?php if ($page->datumbis()->isNotEmpty()): ?> - <?= $page->datumbis()->toDate('d.m.Y H:i');?><?endif; ?>
                </div>
                <hr/>
                <div class="h2">Wo?</div>
                <div class="fs-5">
                    <?= $page->ort();?>
                </div>
            </div>
            <div class="col-md-7 mt-5 mt-md-0">
                <div class="countdown display-1 justify-content-center h4" data-countdown="<?= $page->datum()->toDate('Y-m-d H:i:s');?>">
                    <div class="countdown-days mb-0 mt-3 me-0 px-2 border-end"><span class="countdown-value px-4">0</span><span class="countdown-label fs-lg text-body">Tage</span></div>
                    <div class="countdown-hours mb-0 mt-3 me-0 px-2 border-end"><span class="countdown-value px-4">0</span><span class="countdown-label fs-lg text-body">Stunden</span></div>
                    <div class="countdown-minutes mb-0 mt-3 me-0 px-2 border-end"><span class="countdown-value px-4">0</span><span class="countdown-label fs-lg text-body">Minuten</span></div>
                    <div class="countdown-seconds mb-0 mt-3 me-0 px-2"><span class="countdown-value px-4">0</span><span class="countdown-label fs-lg text-body">Sekunden</span></div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="bg-secondary my-5">
    <div class="row g-0">
        <div class="col-md-6 jarallax py-7" data-jarallax="" data-speed="0.3"><span class="position-absolute top-0 start-0 w-100 h-100 bg-black" style="opacity: 40%;"></span>
            <div class="position-relative zindex-5 d-flex flex-column align-items-center justify-content-center h-100 px-3 py-4 py-lg-7"></div>
            <div id="jarallax-container-0" style="position: absolute; top: 0px; left: 0px; width: 100%; height: 100%; overflow: hidden; z-index: -100;"><div class="jarallax-img" style="background-image: url(<?= $page->vimage()->toFile()->url();?>); object-fit: cover; object-position: 50% 50%; max-width: none; position: fixed; top: 0px; left: 0px; width: 1002.5px; height: 1104.47px; overflow: hidden; pointer-events: none; transform-style: preserve-3d; backface-visibility: hidden; will-change: transform, opacity; margin-top: 112.266px; transform: translate3d(0px, -7.425px, 0px);" data-jarallax-original-styles="background-image: url(img/demo/event-landing/video-bg.jpg);"></div></div></div>
            <div class="col-md-6 px-3 pe-xl-3 ps-lg-5 ps-xl-6 py-2 py-lg-4">
                <div class="ms-sm-4 py-5 my-sm-0 py-md-6 py-lg-7 px-4">
                    <?= $page->text()->kirbytext();?>
                </div>
            </div>
    </div>
</section>

<?php if ($page->doc()->isNotEmpty()): ?>
<section class="container position-relative zindex-5 my-5" id="tickets">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-md-11">
            <div class="bg-secondary rounded-3 pt-5 px-4 pb-4 p-sm-5">
                <div class="px-md-4">
                    <h2 class="text-center pb-4">Downloads</h2>
                    <div class="d-sm-flex justify-content-between align-items-center bg-light rounded-3 px-3 py-4 p-sm-4 mb-grid-gutter text-center text-sm-start">
                        <div class="me-sm-3 mb-4 mb-sm-0">
                            <h3 class="h6 mb-2"><?= $page->documentdescription();?></h3>
                        </div>
                        <a class="btn btn-primary" href="<?= $page->doc()->toFile()->url();?>">Download</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="my-4">
    <div class="container">
        <h2 class="text-center pb-1 mb-5 pt-3">Weitere Veranstaltungen</h2>
        <div class="tns-carousel-wrapper">
            <div class="tns-carousel-inner" data-carousel-options="{&quot;items&quot;: 3, &quot;nav&quot;: false, &quot;gutter&quot;: 23, &quot;responsive&quot;: {&quot;0&quot;:{&quot;items&quot;:1},&quot;800&quot;:{&quot;items&quot;:2}}}">
                <?php foreach ($page->getNextEvents() as $event): ?>
                    <div class="pb-2">
                        <div class="card h-100 border-0 shadow mx-1">
                            <span class="badge badge-lg badge-floating badge-floating-end bg-success">Gesamtverein</span>
                            <div class="card-body py-2 px-4">
                                <div class="d-sm-flex py-sm-4 px-lg-3">
                                    <div class="ps-sm-4 ps-lg-5 pt-5 pt-md-0">
                                        <a class="meta-link fs-sm mb-2" href="<?= $event->url();?>"><?= $event->datum()->toDate('d.m.Y');?></a>
                                        <h3 class="h4 nav-heading mb-4"><a href="<?= $event->url();?>"><?= $event->title();?></a></h3>
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

<?php snippet('footer');?>