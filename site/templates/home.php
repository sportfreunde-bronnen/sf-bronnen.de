<?php
$slides = $page->slides()->toStructure();
$slideCount = $slides->count();
$areas = $page->getAreaLinks();
$newsAreas = $page->getNewsAreas();
$news = $page->getLatestNews();
$newsLimit = HomePage::NEWS_LIMIT;
$events = $page->getNextEvents();
?>
<?php snippet('header'); ?>
<div class="template--home">
    <div class="page">

        <section class="home-intro">
            <div class="container">
                <div class="row">
                    <div class="col-lg-7 home-intro-main home-reveal">
                        <h2 class="home-title">Herzlich <span>willkommen!</span></h2>
                        <?php if ($page->zitat()->isNotEmpty()): ?>
                            <p class="lead"><?= esc((string)$page->zitat());?></p>
                        <?php endif; ?>
                        <div class="home-intro-text">
                            <?= $page->text()->kirbytext(); ?>
                        </div>
                    </div>
                    <?php if ($areas): ?>
                        <div class="col-lg-5">
                            <?php /* no <nav> element: custom.js pins every <nav> to the top of the window while scrolling */ ?>
                            <div class="home-areas home-reveal" role="navigation" aria-label="Schnell zum Ziel">
                                <h3 class="home-areas-title">Schnell zum Ziel</h3>
                                <ul class="home-areas-list">
                                    <?php foreach ($areas as $area): ?>
                                        <li>
                                            <a class="home-area" href="<?= esc($area['url']);?>" title="Zum Bereich <?= esc($area['title']);?>">
                                                <span class="home-area-icon"><i class="fa <?= $area['icon'];?>"></i></span>
                                                <span class="home-area-label"><?= esc($area['title']);?></span>
                                                <i class="fa fa-angle-right home-area-arrow"></i>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <?php if ($slideCount > 0): ?>
            <section class="home-slides home-slides--<?= (1 === $slideCount) ? 'single' : 'multi';?>" aria-label="Aktuelle Themen">
                <?php foreach ($slides as $slide): ?>
                    <?php
                    $image = $slide->sliderimage()->toFile();
                    $text = trim((string)$slide->text());
                    $link = null;
                    if ($slide->internallink()->toPage()) {
                        $link = $slide->internallink()->toPage()->url();
                    } elseif ($slide->externallink()->isNotEmpty()) {
                        $link = (string)$slide->externallink();
                    }
                    $buttonText = $slide->buttonText()->isEmpty() ? 'Mehr erfahren' : (string)$slide->buttonText();

                    $classes = ['home-slide'];
                    if ('right' === (string)$slide->textposition()) {
                        $classes[] = 'home-slide--right';
                    }
                    if (!$image) {
                        $classes[] = 'home-slide--plain';
                    }
                    ?>
                    <article class="<?= implode(' ', $classes);?>">
                        <?php if ($image): ?>
                            <?php
                            $widths = array_values(array_filter([480, 800, 1200, 1600], function ($width) use ($image) {
                                return $width < $image->width();
                            }));
                            $widths[] = $image->width();
                            ?>
                            <img class="home-slide-media"
                                 src="<?= $image->url();?>"
                                 srcset="<?= $image->srcset($widths);?>"
                                 sizes="<?= (1 === $slideCount) ? '100vw' : '(min-width: 768px) ' . round(100 / $slideCount, 2) . 'vw, 100vw';?>"
                                 alt="">
                        <?php endif; ?>
                        <div class="home-slide-content">
                            <h2 class="home-slide-title"><?= esc((string)$slide->title());?></h2>
                            <?php if ($text !== ''): ?>
                                <p class="home-slide-text"><?= esc($text);?></p>
                            <?php endif; ?>
                            <?php if ($link): ?>
                                <a class="home-slide-btn stretched-link" href="<?= $link;?>"><?= esc($buttonText);?><i class="fa fa-long-arrow-right"></i></a>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </section>
        <?php endif; ?>

        <?php if ($upcomingData = $page->getLatestResults() && 1 == 2): ?>
            <section class="football-upcoming">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 pt-2 pb-2 text-center">
                            <i class="fa fa-calendar pr-2"></i><?= $upcomingData;?><i class="fa fa-calendar pl-2"></i>
                        </div>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <section class="home-values">
            <div class="container">
                <?php if ($page->homeBannerText()->isNotEmpty()): ?>
                    <h2 class="home-values-title home-reveal"><?= $page->homeBannerText();?></h2>
                <?php endif; ?>
                <div class="home-values-statement home-reveal">
                    <p class="home-values-welcome">Herzlich willkommen bei den <strong>Sportfreunden Bronnen</strong>!</p>
                    <p class="home-values-claim">Für <strong>Fair-Play</strong>, gegen <strong>Rassismus</strong>, <strong>Ausgrenzung</strong> und <strong>Diskriminierung</strong>!</p>
                </div>
            </div>
        </section>

        <section class="home-news" id="aktuelles" data-news data-limit="<?= $newsLimit;?>">
            <div class="container">
                <h2 class="blog-section-title">Aktuelles aus dem Verein</h2>

                <div class="news-filter" role="group" aria-label="Beiträge nach Bereich filtern" data-news-filter hidden>
                    <button type="button" class="news-filter-card is-active" data-filter="all" aria-pressed="true">
                        <span class="news-filter-icon"><i class="fa fa-th-large"></i></span>
                        <span class="news-filter-label">Alle</span>
                    </button>
                    <?php foreach ($newsAreas as $area): ?>
                        <button type="button" class="news-filter-card" data-filter="<?= $area['slug'];?>" data-archive-url="<?= $area['archiveUrl'];?>" data-archive-label="Alle Beiträge: <?= esc($area['title']);?>" aria-pressed="false">
                            <span class="news-filter-icon"><i class="fa <?= $area['icon'];?>"></i></span>
                            <span class="news-filter-label"><?= esc($area['title']);?></span>
                        </button>
                    <?php endforeach; ?>
                </div>

                <div class="news-grid" data-news-grid>
                    <?php $i = 0; foreach ($news as $post): $i++; ?>
                        <?php
                        $areaSlug = $page->getAreaSlug($post);
                        $areaTitle = isset($newsAreas[$areaSlug]) ? $newsAreas[$areaSlug]['title'] : (string)$post->parents()->last()?->title();
                        ?>
                        <div class="news-grid-item" data-area="<?= $areaSlug;?>"<?= ($i > $newsLimit) ? ' hidden' : '';?>>
                            <?php snippet('post-card', ['post' => $post, 'areaTitle' => $areaTitle]); ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <p class="news-empty" data-news-empty<?= ($news->count() > 0) ? ' hidden' : '';?>>Für diesen Bereich gibt es aktuell keine Beiträge.</p>
                <p class="news-archive" data-news-archive hidden><a class="btn btn-1" href="#"></a></p>
            </div>
        </section>

        <section class="home-events">
            <div class="container">
                <h2 class="blog-section-title">Kommende Veranstaltungen</h2>
                <?php if ($events->isNotEmpty()): ?>
                    <div class="event-grid">
                        <?php foreach ($events as $event): ?>
                            <?php snippet('event-card', ['event' => $event]); ?>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="event-empty">Aktuell sind keine Veranstaltungen geplant.</p>
                <?php endif; ?>
            </div>
        </section>

    </div>

</div>
<?php snippet('footer', ['scripts' => ['home']]); ?><?php snippet('close'); ?>
