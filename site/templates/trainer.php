<?php snippet('header'); ?>
<?php snippet('h1');?>
<?php
$currentCoach = $page->getCurrentCoach();
$decades = $page->getFormerCoachDecades();
$stationCount = $page->getCoachStationCount();
$firstYear = $page->getFirstCoachYear();
?>
<div class="page">
    <div class="main-container container ps-sm-0 pe-sm-0 page--coaches">
        <header class="coaches-intro">
            <h2 class="blog-section-title coaches-intro-title"><?= $page->headline()->html();?></h2>
        </header>

        <?php if ($page->text()->isNotEmpty()): ?>
            <div class="coaches-text"><?= $page->text()->kirbytext();?></div>
        <?php endif; ?>

        <?php if ($currentCoach): ?>
            <section class="coach-hero" aria-label="Aktuelles Trainerteam">
                <div class="coach-hero-icon" aria-hidden="true"><i class="fa fa-futbol-o"></i></div>
                <div class="coach-hero-body">
                    <div class="coach-hero-label">Aktuell an der Seitenlinie</div>
                    <h3 class="coach-hero-name"><?= $currentCoach['name'];?></h3>
                    <div class="coach-hero-period"><i class="fa fa-calendar-o" aria-hidden="true"></i><?= esc($currentCoach['period']);?></div>
                </div>
            </section>
        <?php endif; ?>

        <?php if ($decades): ?>
            <h3 class="blog-section-title"><?= $currentCoach ? 'Frühere Trainer' : 'Alle Trainer';?></h3>
            <?php foreach ($decades as $decade): ?>
                <section class="coach-decade">
                    <h4 class="coach-decade-label"><?= esc($decade['label']);?></h4>
                    <ol class="coach-list">
                        <?php foreach ($decade['stations'] as $station): ?>
                            <li class="coach-item">
                                <span class="coach-period"><?= esc($station['period']);?></span>
                                <span class="coach-name"><?= $station['name'];?></span>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                </section>
            <?php endforeach; ?>
        <?php elseif (!$currentCoach): ?>
            <p class="coaches-empty">Aktuell sind keine Trainer hinterlegt.</p>
        <?php endif; ?>
    </div>
</div>
<?php snippet('footer'); ?><?php snippet('close'); ?>
