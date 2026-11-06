<?php
/**
 * Card of an upcoming event ("veranstaltung")
 *
 * @var VeranstaltungPage $event
 */
$title = (string)$event->title();
$image = $event->vimage()->isNotEmpty() ? $event->vimage()->toFile() : null;
$area = $event->getAreaTitle();
$short = trim((string)$event->shorttitle());
$time = $event->getTimeLabel();
$place = trim((string)$event->ort());
?>
<article class="event-card home-reveal">
    <a class="event-card-media" href="<?= $event->url();?>" tabindex="-1" aria-hidden="true">
        <?php if ($image): ?>
            <img src="<?= $image->crop(720, 450)->url();?>" alt="Veranstaltungsbild - <?= esc($title);?> - Sportfreunde Bronnen" loading="lazy">
        <?php else: ?>
            <img src="/assets/images/blog/blog-thumb-2.jpg" alt="Veranstaltungsbild - <?= esc($title);?> - Sportfreunde Bronnen" loading="lazy">
        <?php endif; ?>
        <time class="event-date" datetime="<?= date('Y-m-d', $event->getStart());?>">
            <span class="event-date-day"><?= $event->getDay();?></span>
            <span class="event-date-month"><?= $event->getMonthShort();?></span>
        </time>
    </a>
    <div class="event-card-body">
        <?php if ($area !== ''): ?>
            <ul class="post-tags">
                <li><?= esc($area);?></li>
            </ul>
        <?php endif; ?>
        <h3 class="event-card-title">
            <a href="<?= $event->url();?>" title="Zur Veranstaltung: <?= esc($title);?>"><?= esc($title);?></a>
        </h3>
        <?php if ($short !== ''): ?>
            <p class="event-card-text"><?= esc($short);?></p>
        <?php endif; ?>
        <ul class="event-meta">
            <li><i class="fa fa-calendar-o"></i><?= $event->getDateLabel();?></li>
            <?php if ($time !== ''): ?>
                <li><i class="fa fa-clock-o"></i><?= $time;?></li>
            <?php endif; ?>
            <?php if ($place !== ''): ?>
                <li><i class="fa fa-map-marker"></i><?= esc($place);?></li>
            <?php endif; ?>
        </ul>
        <a class="event-card-more" href="<?= $event->url();?>" title="Zur Veranstaltung: <?= esc($title);?>">Mehr erfahren <i class="fa fa-long-arrow-right"></i></a>
    </div>
</article>
