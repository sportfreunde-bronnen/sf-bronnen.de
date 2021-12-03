<?php snippet('header'); ?>
<section class="position-relative pt-7 mb-5">
    <!-- Background-->
    <div class="position-absolute jarallax bg-gradient w-100" style="top: 0; left: 0; height: 667px;" data-jarallax data-speed="0.25"><span class="position-absolute top-0 start-0 w-100 h-100 bg-gradient" style="opacity: .80;"></span>
        <div class="jarallax-img" style="background-image: url(assetsV2/img/sfb/home/svz.jpg);"></div>
        <div class="shape shape-bottom shape-curve-side bg-body">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 3000 250">
                <path fill="currentColor" d="M3000,0v250H0v-51c572.7,34.3,1125.3,34.3,1657.8,0C2190.3,164.8,2637.7,98.4,3000,0z"></path>
            </svg>
        </div>
    </div>
    <!-- Tabs-->
    <div class="container position-relative zindex-5 pt-lg-7 mb-5">
        <div class="row pt-4">
            <div class="col-lg-3">
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
            <div class="col-lg-9">
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
<section class="bg-faded-primary position-relative py-md-6 py-3" style="margin-top: 300px;">
    <div class="container my-2 my-md-0">
        <div class="row align-items-center">
            <div class="col-12 p-5">
                <h1 class="text-primary">Herzlich willkommen bei den Sportfreunden Bronnen</h1>
                <hr>
                <h3 class="text-primary">Wir stehen für Spaß und Freude am Sport und an der Bewegung.</h3>
                <p class="text-primary">In unseren Abteilungen Fitness/Gymnastik, Fußball und Aikido trainieren unsere Mitglieder gemeinsam für ihre sportlichen Erfolge und Ziele. Unser Ziel ist es, für Mitglieder jeden Alters die bestmöglichen Voraussetzungen zu schaffen. Dafür setzen sich unsere ehrenamtlichen Helfer mit viel Einsatz, Freude und Herzblut ein. Kameradschaft, Engagement und gegenseitiger Respekt sind unsere Leitsätze.</p>
                <p class="text-primary">Mit unseren tollen Sponsoren und Unterstützern und in Zusammenarbeit mit unserer Heimatgemeinde wollen wir diesen Weg auch in Zukunft zielstrebig weiter gehen. Werden Sie Teil der Sportfreunde Familie und begleiten Sie uns auf diesem Weg.</p>
                <p class="text-primary">Nun wünschen wir Ihnen viel Spaß beim Stöbern auf unserer Homepage.</p>
                <h3 class="text-primary">Respekt. Engagement. Kameradschaft.</h3>
                <hr>
            </div>
        </div>
        <div class="shape shape-top shape-curve bg-body">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 3000 185.4">
                <path fill="currentColor" d="M3000,185.4V0H0v185.4C496.4,69.8,996.4,12,1500,12S2503.6,69.8,3000,185.4z"></path>
            </svg>
        </div>
    </div>
</section>
<section class="position-relative">
    <div class="jarallax bg-dark py-7" data-jarallax data-speed="0.3"><span class="position-absolute top-0 start-0 w-100 h-100 bg-black" style="opacity: .50;"></span>
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
        <!-- Blog grid-->
        <div class="masonry-grid overflow-hidden" data-columns="4">
            <!-- Post-->
            <?php foreach ($page->getLatestNews() as $post): ?>
                <div class="masonry-grid-item">
                    <article class="card card-hover"><a class="card-img-top" href="<?= $post->url();?>"><img src="<?= $post->teaserimage()->toFile()->crop(510, 340, 'top left')->url(); ?>" alt="Artikelbild - <?= $post->title();?> - Sportfreunde Bronnen"></a>
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <i class="ai-calendar"></i><span class="p-2 d-flex"><small><?= $page->formatDate($post->datum());?></small></span>
                            </div>
                            <h2 class="h5 nav-heading mb-4">
                                <a href="<?= $post->url();?>" title="Zum Artikel - <?= $post->title();?>" href="<?= $post->url();?>">
                                    <?= $post->title();?>
                                </a>
                            </h2>
                            <a class="meta-link fs-sm mb-2" href="<?= $post->url();?>" title="Zum Artikel - <?= $post->title();?>" href="<?= $post->url();?>">
                                <?= substr(strip_tags($post->text()->kirbytext()), 0, 100); ?>...
                            </a>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<section class="pb-7">
    <div class="container pb-5">
        <h2 class="text-center pb-1 mb-5">Kommende Veranstaltungen</h2>
    </div>
</section>
<div class="position-relative zindex-5 container" style="margin-top: -150px;">
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
<?php snippet('footer'); ?>