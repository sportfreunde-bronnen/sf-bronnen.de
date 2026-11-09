<?php snippet('header'); ?>
<?php
/** @var VeranstaltungPage $page */
$title = (string)$page->title();
$short = trim((string)$page->shorttitle());
$area = $page->getAreaTitle();
$start = $page->getStart();
$time = $page->getTimeLabel();
$place = trim((string)$page->ort());
$image = $page->vimage()->isNotEmpty() ? $page->vimage()->toFile() : null;
$imageAlt = 'Veranstaltungsbild - ' . $title . ' - Sportfreunde Bronnen';
$imageBottom = (string)$page->imageposition() === 'bottom';
$document = $page->doc()->isNotEmpty() ? $page->doc()->toFile() : null;
$documentLabel = trim((string)$page->documentdescription()) ?: 'Dokument';
$related = $page->related()->isNotEmpty() ? $page->related()->toPage() : null;
if (!($related instanceof VeranstaltungPage)) {
    $related = null;
}
$overviewUrl = $site->url() . '#veranstaltungen';
?>
<div class="page">
    <article class="page--veranstaltung">
        <header class="post-hero<?= ($image && !$imageBottom) ? '' : ' post-hero--noimage';?>">
            <div class="container ps-sm-0 pe-sm-0">
                <div class="post-hero-inner">
                    <a class="post-back" href="<?= $overviewUrl;?>"><i class="fa fa-long-arrow-left"></i> Zu den Veranstaltungen</a>
                    <?php if ($area !== ''): ?>
                        <ul class="post-tags">
                            <li><?= esc($area);?></li>
                        </ul>
                    <?php endif; ?>
                    <h1 class="post-title"><?= esc($title);?></h1>
                    <?php if ($short !== ''): ?>
                        <p class="event-lead"><?= esc($short);?></p>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <div class="container ps-sm-0 pe-sm-0">
            <?php if ($image && !$imageBottom): ?>
                <figure class="post-cover">
                    <img src="<?= $image->resize(1500)->url();?>" alt="<?= esc($imageAlt);?>">
                </figure>
            <?php endif; ?>

            <div class="row event-layout">
                <div class="col-12 col-lg-8 order-2 order-lg-1 event-main">
                    <div class="post-content">
                        <?= $page->text()->kirbytext();?>
                    </div>

                    <?php if ($image && $imageBottom): ?>
                        <figure class="event-image">
                            <img src="<?= $image->resize(1500)->url();?>" alt="<?= esc($imageAlt);?>" loading="lazy">
                        </figure>
                    <?php endif; ?>

                    <footer class="post-footer">
                        <a class="btn btn-1" href="<?= $overviewUrl;?>"><i class="fa fa-long-arrow-left"></i> Zu den Veranstaltungen</a>
                    </footer>
                </div>

                <div class="col-12 col-lg-4 order-1 order-lg-2">
                    <aside class="event-facts" aria-label="Kurz und knapp">
                        <div class="event-facts-head">
                            <?php if ($start): ?>
                                <time class="event-facts-badge" datetime="<?= date('Y-m-d', $start);?>">
                                    <span class="event-facts-day"><?= $page->getDay();?></span>
                                    <span class="event-facts-month"><?= $page->getMonthShort();?></span>
                                </time>
                            <?php endif; ?>
                            <h2 class="event-facts-title">Kurz und knapp</h2>
                        </div>

                        <?php if ($page->isPast()): ?>
                            <p class="event-facts-note"><i class="fa fa-history"></i>Diese Veranstaltung hat bereits stattgefunden.</p>
                        <?php endif; ?>

                        <ul class="event-facts-list">
                            <?php if ($start): ?>
                                <li class="event-facts-item">
                                    <span class="event-facts-icon"><i class="fa fa-calendar-o"></i></span>
                                    <span class="event-facts-text">
                                        <span class="event-facts-label">Wann?</span>
                                        <span class="event-facts-value"><?= $page->getDateLabel();?></span>
                                    </span>
                                </li>
                            <?php endif; ?>
                            <?php if ($time !== ''): ?>
                                <li class="event-facts-item">
                                    <span class="event-facts-icon"><i class="fa fa-clock-o"></i></span>
                                    <span class="event-facts-text">
                                        <span class="event-facts-label">Uhrzeit</span>
                                        <span class="event-facts-value"><?= $time;?></span>
                                    </span>
                                </li>
                            <?php endif; ?>
                            <?php if ($place !== ''): ?>
                                <li class="event-facts-item">
                                    <span class="event-facts-icon"><i class="fa fa-map-marker"></i></span>
                                    <span class="event-facts-text">
                                        <span class="event-facts-label">Wo?</span>
                                        <span class="event-facts-value"><?= esc($place);?></span>
                                    </span>
                                </li>
                            <?php endif; ?>
                            <?php if ($document): ?>
                                <li class="event-facts-item">
                                    <span class="event-facts-icon"><i class="fa fa-download"></i></span>
                                    <span class="event-facts-text">
                                        <span class="event-facts-label"><?= esc($documentLabel);?></span>
                                        <span class="event-facts-value"><a href="<?= $document->url();?>">Download</a></span>
                                    </span>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </aside>
                </div>
            </div>
        </div>

        <?php if ($related): ?>
            <section class="post-related">
                <div class="container ps-sm-0 pe-sm-0">
                    <h2 class="blog-section-title">Auch interessant</h2>
                    <div class="row">
                        <div class="col-12 col-md-6 col-lg-4 post-related-item">
                            <?php snippet('event-card', ['event' => $related]); ?>
                        </div>
                    </div>
                </div>
            </section>
        <?php endif; ?>
    </article>
</div>
<?php snippet('footer'); ?><?php snippet('close'); ?>
