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
                                <a href="<?= $slide->internallink();?>" class="btn btn-1 animation text-weight-medium">Mehr erfahren</a>
                            <?php elseif($slide->externallink()->isNotEmpty()): ?>
                                <a href="<?= $slide->externallink();?>" class="btn btn-1 animation text-weight-medium">Mehr erfahren</a>
                            <?php else: ?>

                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <a class="carousel-control-prev animation" href="#main-slider" role="button" data-slide="prev">
            <span class="fa fa-chevron-left" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next animation" href="#main-slider" role="button" data-slide="next">
            <span class="fa fa-chevron-right" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
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

        <div class="main-container container pl-sm-0 pr-sm-0 text-xs-center text-sm-center text-md-left">
            <section class="section-latest-news">
                <h4 class="section-heading-1 text-xs-center text-sm-center text-md-left mb-0"><span>Aktuelles</span> aus dem Verein</h4>
                <div class="row">
                    <div id="blog-post-carousel" class="owl-carousel mt-0">
                    <?php foreach ($site->index()->visible()->filterBy('template', 'bericht')->sortBy('datum', 'desc')->limit(3) as $post): ?>
                            <div class="box-3 news-box">
                                <a href="<?= $post->url();?>" title="Zum Artikel - <?= $post->title();?>">
                                <?php if ($post->teaserimage()->isNotEmpty()): ?>
                                    <img src="<?= $post->parent()->images()->find($post->teaserimage())->url();?>" alt="News Images" class="img-fluid img-center">
                                <?php else: ?>
                                    <img src="/assets/images/blog/blog-thumb-2.jpg" alt="News Images" class="img-fluid img-center">
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

    </div>

</div>
<?php snippet('footer'); ?>