<?php snippet('header');?>
<section>
    <div class="container">
        <div class="row justify-content-center">
            <!-- Content-->
            <div class="col-lg-10 mb-2 mb-sm-0 pb-sm-5">
                <?php if ($page->disableHeader()->toBool() === true): ?>
                <div class="pb-4">
                    <h1><?= $page->title();?></h1>
                </div>
                <?php endif; ?>
                <!-- Post author + Sharing-->
                <div class="row position-relative g-0 align-items-center border-top border-bottom">
                    <div class="col-md-6 py-3 pe-md-3">
                        <div class="d-flex align-items-center justify-content-center justify-content-md-start">
                            <div class="d-flex align-items-center me-grid-gutter">
                                <div class="ps-2">
                                    <h6 class="nav-heading mb-1">Von: <a href="#"><?= $page->author()->toUser()?->alias() ?? 'Autor';?></a></h6>
                                    <div class="text-nowrap">
                                        <div class="meta-link fs-xs"><i class="ai-calendar me-1 align-vertical"></i>&nbsp;<?= $page->datum()->toDate('d.m.Y');?></div></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="py-4">
                    <?php if ($moreImages = $page->getMoreImages()): ?>
                        <?php $i = 0; foreach ($moreImages as $textImage): $i++; ?>
                            <figure class="figure px-4 gallery text-md-start<?= ($i % 2 == 0) ? ' float-none float-md-end pe-0' : ' float-none float-md-start ps-0'; ?>">
                                <a href="<?= $textImage->url();?>" class="gallery-item" data-sub-html='<h6 class="fs-sm text-light"><?= $textImage->caption();?></h6>'>
                                    <img src="<?= $textImage->resize(500)->url();?>" class="figure-img img-fluid mb-0" alt="Bild: <?= $textImage->caption();?>">
                                </a>
                                <figcaption class="figure-caption w-75 py-2"><?= $textImage->caption();?></figcaption>
                            </figure>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?= $page->text()->kirbytext();?>
                </div>

                <?php if ($page->quote()->isNotEmpty()): ?>
                <h2 class="h3 pt-1 pb-4">Stimmen zum Spiel</h2>
                <div class="bg-faded-primary rounded-3 p-4 mb-4">
                    <div class="p-md-3">
                        <blockquote class="blockquote mb-0">
                            <p class="text-nav"><?= $page->quote();?></p>
                            <footer class="blockquote-footer"><?= $page->quoteFrom();?></footer>
                        </blockquote>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($page->galleryImages()->isNotEmpty()): ?>
                    <?php snippet('gallery', ['images' => $page->galleryImages()->toFiles(), 'desc' => $page->galleryName()]); ?>
                <?php endif; ?>

                <div class="row g-0 position-relative align-items-center">
                    <div class="my-5 py-2 py-dm-3 pe-md-3 text-center text-md-start border-top border-bottom">
                        <?php foreach (explode(',', $page->tags()) as $tag): ?>
                            <a class="btn-tag me-2 my-2" href="<?= $page->parent()->url();?>?tag=<?= trim($tag);?>"><?= trim($tag);?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section>
    <div class="container">
        <div class="pb-4 pb-md-5">
            <h2 class="h3 pb-4">Auch interessant</h2>
            <?php snippet('news-slider', ['news' => $page->getLatestNews()]);?>
        </div>
    </div>
</section>
<?php snippet('footer');?>