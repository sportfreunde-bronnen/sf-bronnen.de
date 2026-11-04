<?php snippet('header'); ?>
<?php
$title = (string)$page->title();
$titleImage = $page->getTitleImage();
$tags = $page->getDisplayTags();
$overviewUrl = $page->parent()->url();
$imageCaption = static function ($image) {
    $caption = trim((string)$image->caption()->value());
    return $caption !== '' ? $caption : trim((string)$image->description()->value());
};
?>
<div class="page">
    <article class="page--bericht">
        <header class="post-hero<?= $titleImage ? '' : ' post-hero--noimage';?>">
            <div class="container pl-sm-0 pr-sm-0">
                <div class="post-hero-inner">
                    <a class="post-back" href="<?= $overviewUrl;?>"><i class="fa fa-long-arrow-left"></i> Alle Beiträge</a>
                    <?php if ($tags): ?>
                        <ul class="post-tags">
                            <?php foreach ($tags as $tag): ?>
                                <li><a href="<?= $overviewUrl;?>?tag=<?= urlencode($tag);?>"><?= esc($tag);?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                    <h1 class="post-title"><?= $title;?></h1>
                    <div class="post-meta post-meta--lg">
                        <time datetime="<?= $page->getDateIso();?>"><i class="fa fa-calendar-o"></i><?= $page->getDateLong();?></time>
                        <span><i class="fa fa-user-o"></i><?= esc($page->getAuthorName());?></span>
                        <span><i class="fa fa-clock-o"></i><?= $page->getReadingTime();?> Min. Lesezeit</span>
                    </div>
                </div>
            </div>
        </header>

        <div class="container pl-sm-0 pr-sm-0">
            <?php if ($titleImage): ?>
                <figure class="post-cover">
                    <img src="<?= $titleImage->resize(1500)->url();?>" alt="Titelbild - <?= esc($title);?> - Sportfreunde Bronnen">
                    <?php if ($imageCaption($titleImage) !== ''): ?>
                        <figcaption><?= esc($imageCaption($titleImage));?></figcaption>
                    <?php endif; ?>
                </figure>
            <?php endif; ?>

            <div class="post-content-wrap">
                <div class="post-content">
                    <?php if ($moreImages = $page->getMoreImages()): ?>
                        <?php foreach ($moreImages as $textImage): ?>
                            <figure class="post-figure">
                                <img src="<?= $textImage->resize(600)->url();?>" class="img-fluid" alt="Bild: <?= esc($imageCaption($textImage));?>">
                                <?php if ($imageCaption($textImage) !== ''): ?>
                                    <figcaption><?= esc($imageCaption($textImage));?></figcaption>
                                <?php endif; ?>
                            </figure>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?= $page->text()->kirbytext(); ?>
                </div>

                <footer class="post-footer">
                    <?php if ($tags): ?>
                        <div class="post-footer-tags">
                            <span class="post-footer-label"><i class="fa fa-tag"></i> Themen</span>
                            <ul class="post-tags">
                                <?php foreach ($tags as $tag): ?>
                                    <li><a href="<?= $overviewUrl;?>?tag=<?= urlencode($tag);?>"><?= esc($tag);?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <a class="btn btn-1" href="<?= $overviewUrl;?>"><i class="fa fa-long-arrow-left"></i> Zurück zur Übersicht</a>
                </footer>
            </div>
        </div>

        <?php if ($page->galleryImages()->isNotEmpty()): ?>
            <div class="post-gallery">
                <?php snippet('gallery', ['images' => $page->galleryImages()->toFiles(), 'desc' => $page->galleryName()]); ?>
            </div>
        <?php endif; ?>

        <?php $related = $page->getLatestNews()->not($page)->limit(3); ?>
        <?php if ($related->count() > 0): ?>
            <section class="post-related">
                <div class="container pl-sm-0 pr-sm-0">
                    <h2 class="blog-section-title">Auch interessant</h2>
                    <div class="row">
                        <?php foreach ($related as $post): ?>
                            <div class="col-12 col-md-6 col-lg-4 post-related-item">
                                <?php snippet('post-card', ['post' => $post]); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>
    </article>
</div>
<?php snippet('footer'); ?><?php snippet('close'); ?>
