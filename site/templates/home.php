<?php snippet('header'); ?>
<div class="template--home">
    <div id="main-slider" class="carousel slide carousel-fade" data-ride="carousel">
        <div class="carousel-inner">
            <?php if ($page->slides()->isNotEmpty()): ?>
                <?php $i = 0; foreach ($page->slides()->toStructure() as $slide): $i++;?>
                    <div class="carousel-item<?= (1 === $i) ? ' active': '';?>">
                        <img src="<?= $slide->sliderimage()->toFile()->url();?>" alt="Slide 1" class="img-fluid">
                        <div class="carousel-caption text-lite-color<?= ($slide->textposition() == 'right') ? ' inverse' : '';?>">
                            <h2 class="text-weight-medium"><?= $slide->title();?></h2>
                            <h5 class="text-weight-light"><?= $slide->text();?></h5>
                            <?php if ($slide->internallink()->isNotEmpty()): ?>
                                <a href="<?= $slide->internallink();?>" class="btn btn-1 animation text-weight-medium">
                                    <?php if ($slide->buttonText()->empty()): ?>
                                        Mehr erfahren
                                    <?php else: ?>
                                        <?= $slide->buttonText();?>
                                    <?php endif; ?>
                                </a>
                            <?php elseif($slide->externallink()->isNotEmpty()): ?>
                                <a href="<?= $slide->externallink();?>" class="btn btn-1 animation text-weight-medium">
                                    <?php if ($slide->buttonText()->empty()): ?>
                                        Mehr erfahren
                                    <?php else: ?>
                                        <?= $slide->buttonText();?>
                                    <?php endif; ?>
                                </a>
                            <?php else: ?>

                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?php if ($page->slides()->toStructure()->count() > 1): ?>
            <a class="carousel-control-prev animation" href="#main-slider" role="button" data-slide="prev">
                <span class="fa fa-chevron-left" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next animation" href="#main-slider" role="button" data-slide="next">
                <span class="fa fa-chevron-right" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        <?php endif; ?>
    </div>

    <div class="page">

        <section class="pt-5">
            <div class="container  pl-sm-0 pr-sm-0 text-xs-center text-sm-center text-md-left">
                <div class="row">
                    <div class="col-12">
                        <h4 class="section-heading-1 text-xs-center text-sm-center text-md-left mt-0">Herzlich <span>willkommen!</span></h4>
                        <p class="lead">
                            <?= $page->zitat();?>
                        </p>
                        <?= $page->text()->kirbytext(); ?>
                    </div>
                </div>
            </div>
        </section>

        <div class="main-container container pl-sm-0 pr-sm-0 pb-0 text-xs-center text-sm-center text-md-left">
            <section class="section-latest-news">
                <h4 class="section-heading-1 text-xs-center text-sm-center text-md-left mb-0"><span>Aktuelles</span> aus dem Verein</h4>
                <div class="row">
                    <div id="blog-post-carousel" class="owl-carousel mt-0">
                        <?php foreach ($page->getLatestNews() as $post): ?>
                            <div class="box-3 news-box">
                                <a href="<?= $post->url();?>" title="Zum Artikel - <?= $post->title();?>">
                                    <?php if ($post->teaserimage()->isNotEmpty()): ?>
                                        <img src="<?= $site->find($post->uri())->images()->find($post->teaserimage())->url(); ?>" alt="Artikelbild - <?= $post->title();?> - Sportfreunde Bronnen" class="img-fluid img-center">
                                    <?php else: ?>
                                        <img src="/assets/images/blog/blog-thumb-2.jpg" alt="Artikelbild - <?= $post->title();?> - Sportfreunde Bronnen" class="img-fluid img-center">
                                    <?php endif; ?>
                                </a>
                                <div class="news-box-content">
                                    <div class="time-stamp text-center">
                                        <span class="text-weight-light"><?= date('d.m.Y', strtotime($post->datum()));?> - <?= $post->parents()->last()->title();?></span>
                                    </div>
                                    <h6 class="text-weight-medium"><a title="Zum Artikel - <?= $post->title();?>" href="<?= $post->url();?>"><?= $post->title();?></a></h6>
                                    <hr/>
                                    <p><?= substr($post->text(), 0, 100); ?>...</p>
                                    <ul class="list-unstyled list-inline text-normal">
                                        <li class="list-inline-item text-weight-medium"><a class="h6" title="Zum Artikel - <?= $post->title();?>" href="<?= $post->url();?>">Weiterlesen</a></li>
                                    </ul>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        </div>

        <div class="main-container container pl-sm-0 pr-sm-0 pt-0 text-xs-center text-sm-center text-md-left">
            <section class="section-upcoming-events">
                <h4 class="section-heading-1 text-xs-center text-sm-center text-md-left mb-0"><span>Kommende</span> Veranstaltungen</h4>
                <div class="row mt-4">
                    <?php foreach ($page->getNextEvents() as $event): ?>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <a href="<?= $event->url();?>">
                            <?php if ($event->vimage()->isNotEmpty()): ?>
                                <img src="<?= $event->vimage()->toFile()->crop(400,200)->url();?>" alt="Veranstaltungsbild - <?= $event->title();?> - Sportfreunde Bronnen" class="img-fluid img-center pb-2">
                            <?php else: ?>
                                <img src="/assets/images/blog/blog-thumb-2.jpg" alt="Veranstaltungsbild - <?= $event->title();?> - Sportfreunde Bronnen" class="img-fluid img-center">
                            <?php endif; ?>
                            </a>
                            <a href="<?= $event->url();?>">
                                <b><?= $page->formatDate($event->datum());?></b>: <?= $event->title();?>
                            </a>
                            <br/>
                            <small><?= $event->shorttitle();?></small>
                            <br/>
                            <hr class="d-block d-sm-none"/>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>

    </div>

</div>
<?php snippet('footer'); ?><?php snippet('close'); ?>